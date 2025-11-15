<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\AttendanceSessionModel;
use App\Models\AttendanceRecordModel;

class AttendanceController extends BaseController
{
    /**
     * Display attendance input page (untuk guru)
     */
    public function index()
    {
        // Hanya guru yang bisa akses
        if (session()->get('role') !== 'guru') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherId = session()->get('teacher_id');

        $classModel = new ClassModel();
        $subjectModel = new SubjectModel();

        $data = [
            'title'    => 'Input Absensi',
            'classes'  => $classModel->findAll(),
            'subjects' => $subjectModel->getSubjectsByTeacher($teacherId),
        ];

        return view('attendance/enterprise_index', $data);
    }

    /**
     * Get students by class (AJAX)
     */
    public function getStudentsByClass($classId)
    {
        $studentModel = new StudentModel();
        $students = $studentModel->getActiveStudentsByClass($classId);

        return $this->response->setJSON($students);
    }

    /**
     * Store attendance data
     */
    public function store()
    {
        // Validasi role
        if (session()->get('role') !== 'guru') {
            session()->setFlashdata('error', 'Akses ditolak');
            return redirect()->to('/dashboard');
        }

        // Get POST data
        $date = $this->request->getPost('date');
        $classId = $this->request->getPost('class_id');
        $subjectId = $this->request->getPost('subject_id');
        $lessonHour = $this->request->getPost('lesson_hour');
        $attendanceData = $this->request->getPost('attendance'); // Array of student_id => status

        // Validasi input
        if (empty($date) || empty($classId) || empty($subjectId)) {
            session()->setFlashdata('error', 'Data tidak lengkap. Harap isi tanggal, kelas, dan mata pelajaran.');
            return redirect()->back();
        }

        if (empty($attendanceData)) {
            session()->setFlashdata('error', 'Belum ada data absensi yang diisi.');
            return redirect()->back();
        }

        $teacherId = session()->get('teacher_id');
        $userId = session()->get('user_id');

        // Check for duplicate session
        $sessionModel = new AttendanceSessionModel();

        // Validasi 1: Cek exact duplicate (kelas + mapel + guru + jam + tanggal yang sama persis)
        $exactDuplicate = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->first();

        if ($exactDuplicate) {
            session()->setFlashdata('error', 'Absensi untuk kelas, mapel, dan jam ini sudah pernah dibuat. Silakan edit absensi yang sudah ada.');
            return redirect()->to('/recap/teacher');
        }

        // Validasi 2: Satu guru tidak boleh mengajar di 2 tempat berbeda pada jam yang sama
        $teacherConflict = $sessionModel->where([
            'date'       => $date,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->first();

        if ($teacherConflict) {
            session()->setFlashdata('error', 'Anda sudah mengajar di kelas/mata pelajaran lain pada jam yang sama hari ini. Satu guru tidak bisa mengajar di 2 tempat pada waktu bersamaan.');
            return redirect()->to('/attendance');
        }

        // Validasi 3: Satu kelas tidak boleh belajar 2 mata pelajaran berbeda pada jam yang sama
        $classConflict = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'lesson_hour' => $lessonHour
        ])->first();

        if ($classConflict) {
            session()->setFlashdata('error', 'Kelas ini sudah memiliki jadwal mata pelajaran lain pada jam yang sama hari ini. Satu kelas tidak bisa belajar 2 mapel sekaligus.');
            return redirect()->to('/attendance');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert attendance session
        $sessionData = [
            'date'        => $date,
            'class_id'    => $classId,
            'subject_id'  => $subjectId,
            'teacher_id'  => $teacherId,
            'lesson_hour' => $lessonHour,
            'created_by'  => $userId,
        ];

        if (!$sessionModel->insert($sessionData)) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan sesi absensi: ' . implode(', ', $sessionModel->errors()));
            return redirect()->back()->withInput();
        }

        $sessionId = $sessionModel->getInsertID();

        // Insert attendance records
        $recordModel = new AttendanceRecordModel();
        $notes = $this->request->getPost('note'); // Array of student_id => note

        foreach ($attendanceData as $studentId => $status) {
            $recordData = [
                'attendance_session_id' => $sessionId,
                'student_id'            => $studentId,
                'status'                => $status,
                'note'                  => isset($notes[$studentId]) ? $notes[$studentId] : null,
            ];

            if (!$recordModel->insert($recordData)) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menyimpan data absensi siswa');
                return redirect()->back()->withInput();
            }
        }

        // Complete transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Data absensi berhasil disimpan');
        return redirect()->to('/attendance');
    }

    /**
     * Edit attendance data
     */
    public function edit($sessionId)
    {
        // Hanya guru yang bisa akses
        if (session()->get('role') !== 'guru') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherId = session()->get('teacher_id');
        $sessionModel = new AttendanceSessionModel();
        $recordModel = new AttendanceRecordModel();
        $classModel = new ClassModel();
        $subjectModel = new SubjectModel();

        // Get session data
        $session = $sessionModel->find($sessionId);

        if (!$session) {
            session()->setFlashdata('error', 'Data absensi tidak ditemukan');
            return redirect()->to('/recap/teacher');
        }

        // Pastikan session ini milik guru yang login
        if ($session['teacher_id'] != $teacherId) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk mengedit absensi ini');
            return redirect()->to('/recap/teacher');
        }

        // Get attendance records
        $records = $recordModel->getRecordsBySession($sessionId);

        $data = [
            'title'    => 'Edit Absensi',
            'session'  => $session,
            'records'  => $records,
            'classes'  => $classModel->findAll(),
            'subjects' => $subjectModel->getSubjectsByTeacher($teacherId),
        ];

        return view('attendance/enterprise_edit', $data);
    }

    /**
     * Update attendance data
     */
    public function update($sessionId)
    {
        // Validasi role
        if (session()->get('role') !== 'guru') {
            session()->setFlashdata('error', 'Akses ditolak');
            return redirect()->to('/dashboard');
        }

        $teacherId = session()->get('teacher_id');
        $sessionModel = new AttendanceSessionModel();
        $recordModel = new AttendanceRecordModel();

        // Get session data
        $session = $sessionModel->find($sessionId);

        if (!$session || $session['teacher_id'] != $teacherId) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau akses ditolak');
            return redirect()->to('/recap/teacher');
        }

        // Get POST data
        $date = $this->request->getPost('date');
        $classId = $this->request->getPost('class_id');
        $subjectId = $this->request->getPost('subject_id');
        $lessonHour = $this->request->getPost('lesson_hour');
        $attendanceData = $this->request->getPost('attendance');

        // Validasi input
        if (empty($date) || empty($classId) || empty($subjectId)) {
            session()->setFlashdata('error', 'Data tidak lengkap');
            return redirect()->back();
        }

        if (empty($attendanceData)) {
            session()->setFlashdata('error', 'Belum ada data absensi yang diisi');
            return redirect()->back();
        }

        // Check for duplicate (exclude current session)
        // Validasi 1: Cek exact duplicate
        $exactDuplicate = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->where('id !=', $sessionId)->first();

        if ($exactDuplicate) {
            session()->setFlashdata('error', 'Sudah ada absensi untuk kelas, mapel, tanggal, dan jam ini');
            return redirect()->back();
        }

        // Validasi 2: Satu guru tidak boleh mengajar di 2 tempat berbeda pada jam yang sama
        $teacherConflict = $sessionModel->where([
            'date'       => $date,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->where('id !=', $sessionId)->first();

        if ($teacherConflict) {
            session()->setFlashdata('error', 'Anda sudah mengajar di kelas/mata pelajaran lain pada jam yang sama hari ini. Satu guru tidak bisa mengajar di 2 tempat pada waktu bersamaan.');
            return redirect()->back();
        }

        // Validasi 3: Satu kelas tidak boleh belajar 2 mata pelajaran berbeda pada jam yang sama
        $classConflict = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'lesson_hour' => $lessonHour
        ])->where('id !=', $sessionId)->first();

        if ($classConflict) {
            session()->setFlashdata('error', 'Kelas ini sudah memiliki jadwal mata pelajaran lain pada jam yang sama hari ini. Satu kelas tidak bisa belajar 2 mapel sekaligus.');
            return redirect()->back();
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Update session
        $sessionData = [
            'date'        => $date,
            'class_id'    => $classId,
            'subject_id'  => $subjectId,
            'lesson_hour' => $lessonHour,
        ];

        if (!$sessionModel->update($sessionId, $sessionData)) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal mengupdate sesi absensi');
            return redirect()->back();
        }

        // Delete existing records
        $recordModel->where('attendance_session_id', $sessionId)->delete();

        // Insert updated records
        $notes = $this->request->getPost('note');

        foreach ($attendanceData as $studentId => $status) {
            $recordData = [
                'attendance_session_id' => $sessionId,
                'student_id'            => $studentId,
                'status'                => $status,
                'note'                  => isset($notes[$studentId]) ? $notes[$studentId] : null,
            ];

            if (!$recordModel->insert($recordData)) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menyimpan data absensi siswa');
                return redirect()->back();
            }
        }

        // Complete transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data');
            return redirect()->back();
        }

        session()->setFlashdata('success', 'Data absensi berhasil diupdate');
        return redirect()->to('/recap/teacher');
    }

    /**
     * Admin attendance index
     */
    public function adminIndex()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $subjectModel = new SubjectModel();
        $teacherModel = new \App\Models\TeacherModel();

        $data = [
            'title'    => 'Input Absensi (Admin)',
            'classes'  => $classModel->findAll(),
            'subjects' => $subjectModel->findAll(),
            'teachers' => $teacherModel->findAll(),
        ];

        return view('attendance/enterprise_admin_index', $data);
    }

    /**
     * Admin store attendance
     */
    public function adminStore()
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak');
            return redirect()->to('/dashboard');
        }

        // Get POST data
        $date = $this->request->getPost('date');
        $classId = $this->request->getPost('class_id');
        $subjectId = $this->request->getPost('subject_id');
        $teacherId = $this->request->getPost('teacher_id'); // Admin selects teacher
        $lessonHour = $this->request->getPost('lesson_hour');
        $attendanceData = $this->request->getPost('attendance');

        // Validasi input
        if (empty($date) || empty($classId) || empty($subjectId) || empty($teacherId)) {
            session()->setFlashdata('error', 'Data tidak lengkap. Harap isi semua field required.');
            return redirect()->back();
        }

        if (empty($attendanceData)) {
            session()->setFlashdata('error', 'Belum ada data absensi yang diisi.');
            return redirect()->back();
        }

        $userId = session()->get('user_id');

        // Check for duplicate session
        $sessionModel = new AttendanceSessionModel();

        // Validasi 1: Cek exact duplicate (kelas + mapel + guru + jam + tanggal yang sama persis)
        $exactDuplicate = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->first();

        if ($exactDuplicate) {
            session()->setFlashdata('error', 'Absensi untuk kombinasi ini sudah ada. Silakan edit absensi yang sudah ada.');
            return redirect()->to('/recap/admin');
        }

        // Validasi 2: Satu guru tidak boleh mengajar di 2 tempat berbeda pada jam yang sama
        $teacherConflict = $sessionModel->where([
            'date'       => $date,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->first();

        if ($teacherConflict) {
            session()->setFlashdata('error', 'Guru ini sudah mengajar di kelas/mata pelajaran lain pada jam yang sama hari ini. Satu guru tidak bisa mengajar di 2 tempat pada waktu bersamaan.');
            return redirect()->to('/attendance/admin');
        }

        // Validasi 3: Satu kelas tidak boleh belajar 2 mata pelajaran berbeda pada jam yang sama
        $classConflict = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'lesson_hour' => $lessonHour
        ])->first();

        if ($classConflict) {
            session()->setFlashdata('error', 'Kelas ini sudah memiliki jadwal mata pelajaran lain pada jam yang sama hari ini. Satu kelas tidak bisa belajar 2 mapel sekaligus.');
            return redirect()->to('/attendance/admin');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert attendance session
        $sessionData = [
            'date'        => $date,
            'class_id'    => $classId,
            'subject_id'  => $subjectId,
            'teacher_id'  => $teacherId,
            'lesson_hour' => $lessonHour,
            'created_by'  => $userId,
        ];

        if (!$sessionModel->insert($sessionData)) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal menyimpan sesi absensi: ' . implode(', ', $sessionModel->errors()));
            return redirect()->back()->withInput();
        }

        $sessionId = $sessionModel->getInsertID();

        // Insert attendance records
        $recordModel = new AttendanceRecordModel();
        $notes = $this->request->getPost('note');

        foreach ($attendanceData as $studentId => $status) {
            $recordData = [
                'attendance_session_id' => $sessionId,
                'student_id'            => $studentId,
                'status'                => $status,
                'note'                  => isset($notes[$studentId]) ? $notes[$studentId] : null,
            ];

            if (!$recordModel->insert($recordData)) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menyimpan data absensi siswa');
                return redirect()->back()->withInput();
            }
        }

        // Complete transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Data absensi berhasil disimpan');
        return redirect()->to('/attendance/admin');
    }

    /**
     * Admin edit attendance
     */
    public function adminEdit($sessionId)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $sessionModel = new AttendanceSessionModel();
        $recordModel = new AttendanceRecordModel();
        $classModel = new ClassModel();
        $subjectModel = new SubjectModel();
        $teacherModel = new \App\Models\TeacherModel();

        // Get session data
        $session = $sessionModel->find($sessionId);

        if (!$session) {
            session()->setFlashdata('error', 'Data absensi tidak ditemukan');
            return redirect()->to('/recap/admin');
        }

        // Get attendance records
        $records = $recordModel->getRecordsBySession($sessionId);

        $data = [
            'title'    => 'Edit Absensi (Admin)',
            'session'  => $session,
            'records'  => $records,
            'classes'  => $classModel->findAll(),
            'subjects' => $subjectModel->findAll(),
            'teachers' => $teacherModel->findAll(),
        ];

        return view('attendance/enterprise_admin_edit', $data);
    }

    /**
     * Admin update attendance
     */
    public function adminUpdate($sessionId)
    {
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak');
            return redirect()->to('/dashboard');
        }

        $sessionModel = new AttendanceSessionModel();
        $recordModel = new AttendanceRecordModel();

        // Get session data
        $session = $sessionModel->find($sessionId);

        if (!$session) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/recap/admin');
        }

        // Get POST data
        $date = $this->request->getPost('date');
        $classId = $this->request->getPost('class_id');
        $subjectId = $this->request->getPost('subject_id');
        $teacherId = $this->request->getPost('teacher_id');
        $lessonHour = $this->request->getPost('lesson_hour');
        $attendanceData = $this->request->getPost('attendance');

        // Validasi input
        if (empty($date) || empty($classId) || empty($subjectId) || empty($teacherId)) {
            session()->setFlashdata('error', 'Data tidak lengkap');
            return redirect()->back();
        }

        if (empty($attendanceData)) {
            session()->setFlashdata('error', 'Belum ada data absensi yang diisi');
            return redirect()->back();
        }

        // Check for duplicate (exclude current session)
        // Validasi 1: Cek exact duplicate
        $exactDuplicate = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->where('id !=', $sessionId)->first();

        if ($exactDuplicate) {
            session()->setFlashdata('error', 'Sudah ada absensi untuk kombinasi ini');
            return redirect()->back();
        }

        // Validasi 2: Satu guru tidak boleh mengajar di 2 tempat berbeda pada jam yang sama
        $teacherConflict = $sessionModel->where([
            'date'       => $date,
            'teacher_id' => $teacherId,
            'lesson_hour' => $lessonHour
        ])->where('id !=', $sessionId)->first();

        if ($teacherConflict) {
            session()->setFlashdata('error', 'Guru ini sudah mengajar di kelas/mata pelajaran lain pada jam yang sama hari ini. Satu guru tidak bisa mengajar di 2 tempat pada waktu bersamaan.');
            return redirect()->back();
        }

        // Validasi 3: Satu kelas tidak boleh belajar 2 mata pelajaran berbeda pada jam yang sama
        $classConflict = $sessionModel->where([
            'date'       => $date,
            'class_id'   => $classId,
            'lesson_hour' => $lessonHour
        ])->where('id !=', $sessionId)->first();

        if ($classConflict) {
            session()->setFlashdata('error', 'Kelas ini sudah memiliki jadwal mata pelajaran lain pada jam yang sama hari ini. Satu kelas tidak bisa belajar 2 mapel sekaligus.');
            return redirect()->back();
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Update session
        $sessionData = [
            'date'        => $date,
            'class_id'    => $classId,
            'subject_id'  => $subjectId,
            'teacher_id'  => $teacherId,
            'lesson_hour' => $lessonHour,
        ];

        if (!$sessionModel->update($sessionId, $sessionData)) {
            $db->transRollback();
            session()->setFlashdata('error', 'Gagal mengupdate sesi absensi');
            return redirect()->back();
        }

        // Delete existing records
        $recordModel->where('attendance_session_id', $sessionId)->delete();

        // Insert updated records
        $notes = $this->request->getPost('note');

        foreach ($attendanceData as $studentId => $status) {
            $recordData = [
                'attendance_session_id' => $sessionId,
                'student_id'            => $studentId,
                'status'                => $status,
                'note'                  => isset($notes[$studentId]) ? $notes[$studentId] : null,
            ];

            if (!$recordModel->insert($recordData)) {
                $db->transRollback();
                session()->setFlashdata('error', 'Gagal menyimpan data absensi siswa');
                return redirect()->back();
            }
        }

        // Complete transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data');
            return redirect()->back();
        }

        session()->setFlashdata('success', 'Data absensi berhasil diupdate');
        return redirect()->to('/recap/admin');
    }
}
