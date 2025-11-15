<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\SubjectModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MasterDataController extends BaseController
{
    /**
     * Master Data Index - menampilkan semua entitas
     */
    public function index()
    {
        // Hanya admin yang bisa akses
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $studentModel = new StudentModel();
        $teacherModel = new TeacherModel();
        $subjectModel = new SubjectModel();

        $data = [
            'title' => 'Master Data',
            'total_classes' => $classModel->countAll(),
            'total_students' => $studentModel->where('status', 'active')->countAllResults(),
            'total_teachers' => $teacherModel->countAll(),
            'total_subjects' => $subjectModel->countAll(),
        ];

        return view('master_data/enterprise_index', $data);
    }

    // ==================== CLASSES ====================

    public function classes()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $data = [
            'title'   => 'Data Kelas',
            'classes' => $classModel->getClassesWithStudentCount(),
        ];

        return view('master_data/classes/enterprise_index', $data);
    }

    public function createClass()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = ['title' => 'Tambah Kelas'];
        return view('master_data/classes/enterprise_form', $data);
    }

    public function storeClass()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $data = [
            'name'  => $this->request->getPost('name'),
            'level' => $this->request->getPost('level'),
            'major' => $this->request->getPost('major'),
        ];

        if ($classModel->insert($data)) {
            session()->setFlashdata('success', 'Kelas berhasil ditambahkan');
            return redirect()->to('/master/classes');
        }

        session()->setFlashdata('error', 'Gagal menambahkan kelas: ' . implode(', ', $classModel->errors()));
        return redirect()->back()->withInput();
    }

    public function editClass($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $class = $classModel->find($id);

        if (!$class) {
            session()->setFlashdata('error', 'Kelas tidak ditemukan');
            return redirect()->to('/master/classes');
        }

        $data = [
            'title' => 'Edit Kelas',
            'class' => $class,
        ];

        return view('master_data/classes/enterprise_form', $data);
    }

    public function updateClass($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $data = [
            'name'  => $this->request->getPost('name'),
            'level' => $this->request->getPost('level'),
            'major' => $this->request->getPost('major'),
        ];

        if ($classModel->update($id, $data)) {
            session()->setFlashdata('success', 'Kelas berhasil diupdate');
            return redirect()->to('/master/classes');
        }

        session()->setFlashdata('error', 'Gagal mengupdate kelas: ' . implode(', ', $classModel->errors()));
        return redirect()->back()->withInput();
    }

    public function deleteClass($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();

        if ($classModel->delete($id)) {
            session()->setFlashdata('success', 'Kelas berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus kelas');
        }

        return redirect()->to('/master/classes');
    }

    // ==================== STUDENTS ====================

    public function students()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $studentModel = new StudentModel();
        $classModel = new ClassModel();

        // Get filter parameters
        $filterClassId = $this->request->getGet('class_id');
        $filterStatus = $this->request->getGet('status');

        // Build query with filters
        $builder = $studentModel->select('students.*, classes.name as class_name, classes.level, classes.major')
                                ->join('classes', 'classes.id = students.class_id');

        if ($filterClassId) {
            $builder->where('students.class_id', $filterClassId);
        }

        if ($filterStatus) {
            $builder->where('students.status', $filterStatus);
        }

        $students = $builder->orderBy('students.name', 'ASC')->findAll();

        $data = [
            'title'           => 'Data Siswa',
            'students'        => $students,
            'classes'         => $classModel->findAll(),
            'filter_class_id' => $filterClassId,
            'filter_status'   => $filterStatus,
        ];

        return view('master_data/students/enterprise_index', $data);
    }

    public function createStudent()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $data = [
            'title'   => 'Tambah Siswa',
            'classes' => $classModel->findAll(),
        ];

        return view('master_data/students/enterprise_form', $data);
    }

    public function storeStudent()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $studentModel = new StudentModel();
        $data = [
            'nis'      => $this->request->getPost('nis'),
            'nisn'     => $this->request->getPost('nisn'),
            'name'     => $this->request->getPost('name'),
            'class_id' => $this->request->getPost('class_id'),
            'gender'   => $this->request->getPost('gender'),
            'status'   => $this->request->getPost('status') ?: 'active',
        ];

        if ($studentModel->insert($data)) {
            session()->setFlashdata('success', 'Siswa berhasil ditambahkan');
            return redirect()->to('/master/students');
        }

        session()->setFlashdata('error', 'Gagal menambahkan siswa: ' . implode(', ', $studentModel->errors()));
        return redirect()->back()->withInput();
    }

    public function editStudent($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $studentModel = new StudentModel();
        $classModel = new ClassModel();

        $student = $studentModel->find($id);

        if (!$student) {
            session()->setFlashdata('error', 'Siswa tidak ditemukan');
            return redirect()->to('/master/students');
        }

        $data = [
            'title'   => 'Edit Siswa',
            'student' => $student,
            'classes' => $classModel->findAll(),
        ];

        return view('master_data/students/enterprise_form', $data);
    }

    public function updateStudent($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $studentModel = new StudentModel();
        $data = [
            'nis'      => $this->request->getPost('nis'),
            'nisn'     => $this->request->getPost('nisn'),
            'name'     => $this->request->getPost('name'),
            'class_id' => $this->request->getPost('class_id'),
            'gender'   => $this->request->getPost('gender'),
            'status'   => $this->request->getPost('status'),
        ];

        if ($studentModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data siswa berhasil diupdate');
            return redirect()->to('/master/students');
        }

        session()->setFlashdata('error', 'Gagal mengupdate data siswa: ' . implode(', ', $studentModel->errors()));
        return redirect()->back()->withInput();
    }

    public function deleteStudent($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $studentModel = new StudentModel();

        if ($studentModel->delete($id)) {
            session()->setFlashdata('success', 'Siswa berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus siswa');
        }

        return redirect()->to('/master/students');
    }

    /**
     * Export students to Excel
     */
    public function exportStudents()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $studentModel = new StudentModel();
        $students = $studentModel->getStudentsWithClass();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'NISN');
        $sheet->setCellValue('D1', 'Nama');
        $sheet->setCellValue('E1', 'Kelas');
        $sheet->setCellValue('F1', 'Jenis Kelamin');
        $sheet->setCellValue('G1', 'Status');

        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10b981']
            ],
        ]);

        // Data
        $row = 2;
        foreach ($students as $index => $student) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $student['nis']);
            $sheet->setCellValue('C' . $row, $student['nisn'] ?? '');
            $sheet->setCellValue('D' . $row, $student['name']);
            $sheet->setCellValue('E' . $row, $student['class_name'] ?? '');
            $sheet->setCellValue('F' . $row, $student['gender'] == 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('G' . $row, $student['status'] == 'active' ? 'Aktif' : 'Tidak Aktif');
            $row++;
        }

        // Auto-size
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Siswa_' . date('Y-m-d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Form import siswa dari Excel
     */
    public function importStudentsForm()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = ['title' => 'Import Data Siswa'];
        return view('master_data/students/enterprise_import', $data);
    }

    /**
     * Proses import siswa dari Excel
     */
    public function importStudentsProcess()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid()) {
            session()->setFlashdata('error', 'File tidak valid');
            return redirect()->back();
        }

        // Validate file extension
        $extension = $file->getClientExtension();
        if (!in_array($extension, ['xlsx', 'xls'])) {
            session()->setFlashdata('error', 'File harus berformat Excel (.xlsx atau .xls)');
            return redirect()->back();
        }

        try {
            // Load Excel file
            $spreadsheet = IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            $studentModel = new StudentModel();
            $classModel = new ClassModel();

            // Get all classes for mapping
            $classes = $classModel->findAll();
            $classMap = [];
            foreach ($classes as $class) {
                $classMap[strtolower($class['name'])] = $class['id'];
            }

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $rowNum = $index + 2; // +2 karena array start dari 0 dan skip header

                // Validate required fields
                if (empty($row[0]) || empty($row[2]) || empty($row[3])) {
                    $errors[] = "Baris $rowNum: NIS, Nama, dan Kelas wajib diisi";
                    continue;
                }

                // Map class name to ID
                $className = strtolower(trim($row[3]));
                if (!isset($classMap[$className])) {
                    $errors[] = "Baris $rowNum: Kelas '{$row[3]}' tidak ditemukan";
                    continue;
                }

                // Validate gender
                $gender = strtoupper(trim($row[4]));
                if (!in_array($gender, ['L', 'P'])) {
                    $errors[] = "Baris $rowNum: Jenis kelamin harus L atau P";
                    continue;
                }

                // Prepare data
                $data = [
                    'nis'      => trim($row[0]),
                    'nisn'     => !empty($row[1]) ? trim($row[1]) : null,
                    'name'     => trim($row[2]),
                    'class_id' => $classMap[$className],
                    'gender'   => $gender,
                    'status'   => 'active',
                ];

                // Check if NIS already exists
                $existing = $studentModel->where('nis', $data['nis'])->first();
                if ($existing) {
                    $errors[] = "Baris $rowNum: NIS {$data['nis']} sudah terdaftar";
                    continue;
                }

                // Insert student
                if ($studentModel->insert($data)) {
                    $imported++;
                } else {
                    $validationErrors = $studentModel->errors();
                    $errors[] = "Baris $rowNum: " . implode(', ', $validationErrors);
                }
            }

            // Prepare result message
            $message = "Berhasil import $imported siswa";
            if (!empty($errors)) {
                $message .= ". " . count($errors) . " baris gagal diimport.";
                session()->setFlashdata('import_errors', $errors);
            }

            session()->setFlashdata($imported > 0 ? 'success' : 'error', $message);
            return redirect()->to('/master/students');

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Download template Excel untuk import siswa
     */
    public function downloadStudentTemplate()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Kelas');
        $sheet->setCellValue('E1', 'JK');

        // Style header
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366F1']
            ],
            'font' => ['color' => ['rgb' => 'FFFFFF']],
        ]);

        // Sample data
        $sheet->setCellValue('A2', '2024001');
        $sheet->setCellValue('B2', '0012345678');
        $sheet->setCellValue('C2', 'Contoh Siswa');
        $sheet->setCellValue('D2', 'X-1');
        $sheet->setCellValue('E2', 'L');

        // Auto-size
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Template_Import_Siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ==================== TEACHERS ====================

    public function teachers()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherModel = new TeacherModel();
        $data = [
            'title'    => 'Data Guru',
            'teachers' => $teacherModel->getTeachersWithSubjectCount(),
        ];

        return view('master_data/teachers/enterprise_index', $data);
    }

    public function createTeacher()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = ['title' => 'Tambah Guru'];
        return view('master_data/teachers/enterprise_form', $data);
    }

    public function storeTeacher()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherModel = new TeacherModel();
        $data = [
            'name'  => $this->request->getPost('name'),
            'nip'   => $this->request->getPost('nip'),
            'phone' => $this->request->getPost('phone'),
        ];

        if ($teacherModel->insert($data)) {
            session()->setFlashdata('success', 'Guru berhasil ditambahkan');
            return redirect()->to('/master/teachers');
        }

        session()->setFlashdata('error', 'Gagal menambahkan guru: ' . implode(', ', $teacherModel->errors()));
        return redirect()->back()->withInput();
    }

    public function editTeacher($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->find($id);

        if (!$teacher) {
            session()->setFlashdata('error', 'Guru tidak ditemukan');
            return redirect()->to('/master/teachers');
        }

        $data = [
            'title'   => 'Edit Guru',
            'teacher' => $teacher,
        ];

        return view('master_data/teachers/enterprise_form', $data);
    }

    public function updateTeacher($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherModel = new TeacherModel();
        $data = [
            'name'  => $this->request->getPost('name'),
            'nip'   => $this->request->getPost('nip'),
            'phone' => $this->request->getPost('phone'),
        ];

        if ($teacherModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data guru berhasil diupdate');
            return redirect()->to('/master/teachers');
        }

        session()->setFlashdata('error', 'Gagal mengupdate data guru: ' . implode(', ', $teacherModel->errors()));
        return redirect()->back()->withInput();
    }

    public function deleteTeacher($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherModel = new TeacherModel();

        if ($teacherModel->delete($id)) {
            session()->setFlashdata('success', 'Guru berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus guru');
        }

        return redirect()->to('/master/teachers');
    }

    // ==================== SUBJECTS ====================

    public function subjects()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $subjectModel = new SubjectModel();
        $data = [
            'title'    => 'Data Mata Pelajaran',
            'subjects' => $subjectModel->getSubjectsWithTeacher(),
        ];

        return view('master_data/subjects/enterprise_index', $data);
    }

    public function createSubject()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherModel = new TeacherModel();
        $data = [
            'title'    => 'Tambah Mata Pelajaran',
            'teachers' => $teacherModel->findAll(),
        ];

        return view('master_data/subjects/enterprise_form', $data);
    }

    public function storeSubject()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $subjectModel = new SubjectModel();
        $data = [
            'name'       => $this->request->getPost('name'),
            'code'       => $this->request->getPost('code'),
            'teacher_id' => $this->request->getPost('teacher_id') ?: null,
        ];

        if ($subjectModel->insert($data)) {
            session()->setFlashdata('success', 'Mata pelajaran berhasil ditambahkan');
            return redirect()->to('/master/subjects');
        }

        session()->setFlashdata('error', 'Gagal menambahkan mata pelajaran: ' . implode(', ', $subjectModel->errors()));
        return redirect()->back()->withInput();
    }

    public function editSubject($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $subjectModel = new SubjectModel();
        $teacherModel = new TeacherModel();

        $subject = $subjectModel->find($id);

        if (!$subject) {
            session()->setFlashdata('error', 'Mata pelajaran tidak ditemukan');
            return redirect()->to('/master/subjects');
        }

        $data = [
            'title'    => 'Edit Mata Pelajaran',
            'subject'  => $subject,
            'teachers' => $teacherModel->findAll(),
        ];

        return view('master_data/subjects/enterprise_form', $data);
    }

    public function updateSubject($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $subjectModel = new SubjectModel();
        $data = [
            'name'       => $this->request->getPost('name'),
            'code'       => $this->request->getPost('code'),
            'teacher_id' => $this->request->getPost('teacher_id') ?: null,
        ];

        if ($subjectModel->update($id, $data)) {
            session()->setFlashdata('success', 'Mata pelajaran berhasil diupdate');
            return redirect()->to('/master/subjects');
        }

        session()->setFlashdata('error', 'Gagal mengupdate mata pelajaran: ' . implode(', ', $subjectModel->errors()));
        return redirect()->back()->withInput();
    }

    public function deleteSubject($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $subjectModel = new SubjectModel();

        if ($subjectModel->delete($id)) {
            session()->setFlashdata('success', 'Mata pelajaran berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus mata pelajaran');
        }

        return redirect()->to('/master/subjects');
    }
}
