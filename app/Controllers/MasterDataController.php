<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\SubjectModel;

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

        $data = [
            'title' => 'Master Data',
        ];

        return view('master_data/index', $data);
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

        return view('master_data/classes/index', $data);
    }

    public function createClass()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = ['title' => 'Tambah Kelas'];
        return view('master_data/classes/form', $data);
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

        return view('master_data/classes/form', $data);
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
        $data = [
            'title'    => 'Data Siswa',
            'students' => $studentModel->getStudentsWithClass(),
        ];

        return view('master_data/students/index', $data);
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

        return view('master_data/students/form', $data);
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

        return view('master_data/students/form', $data);
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

        return view('master_data/teachers/index', $data);
    }

    public function createTeacher()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = ['title' => 'Tambah Guru'];
        return view('master_data/teachers/form', $data);
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

        return view('master_data/teachers/form', $data);
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

        return view('master_data/subjects/index', $data);
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

        return view('master_data/subjects/form', $data);
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

        return view('master_data/subjects/form', $data);
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
