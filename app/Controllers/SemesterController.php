<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SemesterModel;
use App\Models\AcademicYearModel;
use App\Models\ActivityLogModel;

class SemesterController extends BaseController
{
    /**
     * Display semesters list
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $semesterModel = new SemesterModel();
        $yearModel = new AcademicYearModel();

        $yearId = $this->request->getGet('year_id');

        if ($yearId) {
            $semesters = $semesterModel->getByAcademicYear($yearId);
            $year = $yearModel->find($yearId);
        } else {
            $semesters = $semesterModel->getSemestersWithAcademicYear();
            $year = null;
        }

        $data = [
            'title'          => 'Semester',
            'semesters'      => $semesters,
            'years'          => $yearModel->orderBy('start_date', 'DESC')->findAll(),
            'selectedYear'   => $year,
            'filter_year_id' => $yearId,
        ];

        return view('academic/semesters/enterprise_index', $data);
    }

    /**
     * Create semester form
     */
    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();
        $yearId = $this->request->getGet('year_id');

        $data = [
            'title' => 'Tambah Semester',
            'years' => $yearModel->orderBy('start_date', 'DESC')->findAll(),
            'selectedYearId' => $yearId,
        ];

        return view('academic/semesters/enterprise_form', $data);
    }

    /**
     * Store new semester
     */
    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $semesterModel = new SemesterModel();

        $data = [
            'academic_year_id' => $this->request->getPost('academic_year_id'),
            'name'             => $this->request->getPost('name'),
            'start_date'       => $this->request->getPost('start_date'),
            'end_date'         => $this->request->getPost('end_date'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // If set as active, deactivate others first
        if ($data['is_active']) {
            $semesterModel->where('is_active', 1)->set(['is_active' => 0])->update();
        }

        if ($semesterModel->insert($data)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('CREATE_SEMESTER', "Menambah semester: {$data['name']}");

            session()->setFlashdata('success', 'Semester berhasil ditambahkan');
            return redirect()->to('/academic/semesters?year_id=' . $data['academic_year_id']);
        }

        session()->setFlashdata('error', 'Gagal menambahkan semester: ' . implode(', ', $semesterModel->errors()));
        return redirect()->back()->withInput();
    }

    /**
     * Edit semester form
     */
    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $semesterModel = new SemesterModel();
        $yearModel = new AcademicYearModel();

        $semester = $semesterModel->find($id);

        if (!$semester) {
            session()->setFlashdata('error', 'Semester tidak ditemukan');
            return redirect()->to('/academic/semesters');
        }

        $data = [
            'title'    => 'Edit Semester',
            'semester' => $semester,
            'years'    => $yearModel->orderBy('start_date', 'DESC')->findAll(),
        ];

        return view('academic/semesters/enterprise_form', $data);
    }

    /**
     * Update semester
     */
    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $semesterModel = new SemesterModel();

        $data = [
            'academic_year_id' => $this->request->getPost('academic_year_id'),
            'name'             => $this->request->getPost('name'),
            'start_date'       => $this->request->getPost('start_date'),
            'end_date'         => $this->request->getPost('end_date'),
            'is_active'        => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // If set as active, deactivate others first
        if ($data['is_active']) {
            $semesterModel->where('is_active', 1)->where('id !=', $id)->set(['is_active' => 0])->update();
        }

        if ($semesterModel->update($id, $data)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('UPDATE_SEMESTER', "Mengupdate semester: {$data['name']}");

            session()->setFlashdata('success', 'Semester berhasil diupdate');
            return redirect()->to('/academic/semesters?year_id=' . $data['academic_year_id']);
        }

        session()->setFlashdata('error', 'Gagal mengupdate semester: ' . implode(', ', $semesterModel->errors()));
        return redirect()->back()->withInput();
    }

    /**
     * Delete semester
     */
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $semesterModel = new SemesterModel();
        $semester = $semesterModel->find($id);

        if ($semester && $semesterModel->delete($id)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('DELETE_SEMESTER', "Menghapus semester: {$semester['name']}");

            session()->setFlashdata('success', 'Semester berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus semester');
        }

        return redirect()->back();
    }

    /**
     * Set active semester
     */
    public function setActive($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $semesterModel = new SemesterModel();
        $semester = $semesterModel->find($id);

        if ($semester && $semesterModel->setActive($id)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('ACTIVATE_SEMESTER', "Mengaktifkan semester: {$semester['name']}");

            session()->setFlashdata('success', 'Semester berhasil diaktifkan');
        } else {
            session()->setFlashdata('error', 'Gagal mengaktifkan semester');
        }

        return redirect()->back();
    }
}
