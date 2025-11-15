<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;
use App\Models\ActivityLogModel;

class AcademicYearController extends BaseController
{
    /**
     * Display academic years list
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();
        $data = [
            'title' => 'Tahun Ajaran',
            'years' => $yearModel->getWithSemesterCount(),
        ];

        return view('academic/years/enterprise_index', $data);
    }

    /**
     * Create academic year form
     */
    public function create()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $data = ['title' => 'Tambah Tahun Ajaran'];
        return view('academic/years/enterprise_form', $data);
    }

    /**
     * Store new academic year
     */
    public function store()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // If set as active, deactivate others first
        if ($data['is_active']) {
            $yearModel->where('is_active', 1)->set(['is_active' => 0])->update();
        }

        if ($yearModel->insert($data)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('CREATE_ACADEMIC_YEAR', "Menambah tahun ajaran: {$data['name']}");

            session()->setFlashdata('success', 'Tahun ajaran berhasil ditambahkan');
            return redirect()->to('/academic/years');
        }

        session()->setFlashdata('error', 'Gagal menambahkan tahun ajaran: ' . implode(', ', $yearModel->errors()));
        return redirect()->back()->withInput();
    }

    /**
     * Edit academic year form
     */
    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();
        $year = $yearModel->find($id);

        if (!$year) {
            session()->setFlashdata('error', 'Tahun ajaran tidak ditemukan');
            return redirect()->to('/academic/years');
        }

        $data = [
            'title' => 'Edit Tahun Ajaran',
            'year'  => $year,
        ];

        return view('academic/years/enterprise_form', $data);
    }

    /**
     * Update academic year
     */
    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
            'is_active'  => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // If set as active, deactivate others first
        if ($data['is_active']) {
            $yearModel->where('is_active', 1)->where('id !=', $id)->set(['is_active' => 0])->update();
        }

        if ($yearModel->update($id, $data)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('UPDATE_ACADEMIC_YEAR', "Mengupdate tahun ajaran: {$data['name']}");

            session()->setFlashdata('success', 'Tahun ajaran berhasil diupdate');
            return redirect()->to('/academic/years');
        }

        session()->setFlashdata('error', 'Gagal mengupdate tahun ajaran: ' . implode(', ', $yearModel->errors()));
        return redirect()->back()->withInput();
    }

    /**
     * Delete academic year
     */
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();
        $year = $yearModel->find($id);

        if ($year && $yearModel->delete($id)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('DELETE_ACADEMIC_YEAR', "Menghapus tahun ajaran: {$year['name']}");

            session()->setFlashdata('success', 'Tahun ajaran berhasil dihapus');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus tahun ajaran');
        }

        return redirect()->to('/academic/years');
    }

    /**
     * Set active academic year
     */
    public function setActive($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $yearModel = new AcademicYearModel();
        $year = $yearModel->find($id);

        if ($year && $yearModel->setActive($id)) {
            // Log activity
            $logModel = new ActivityLogModel();
            $logModel->log('ACTIVATE_ACADEMIC_YEAR', "Mengaktifkan tahun ajaran: {$year['name']}");

            session()->setFlashdata('success', 'Tahun ajaran berhasil diaktifkan');
        } else {
            session()->setFlashdata('error', 'Gagal mengaktifkan tahun ajaran');
        }

        return redirect()->to('/academic/years');
    }
}
