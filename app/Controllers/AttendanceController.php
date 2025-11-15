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

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert attendance session
        $sessionModel = new AttendanceSessionModel();
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
}
