<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\AttendanceSessionModel;
use App\Models\AttendanceRecordModel;

class RecapController extends BaseController
{
    /**
     * Rekap untuk Admin
     */
    public function adminRecap()
    {
        // Hanya admin yang bisa akses
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $classModel = new ClassModel();
        $subjectModel = new SubjectModel();
        $recordModel = new AttendanceRecordModel();

        // Get filter parameters
        $classId = $this->request->getGet('class_id');
        $subjectId = $this->request->getGet('subject_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Build filters
        $filters = [];
        if ($classId) {
            $filters['class_id'] = $classId;
        }
        if ($subjectId) {
            $filters['subject_id'] = $subjectId;
        }
        if ($dateFrom) {
            $filters['date_from'] = $dateFrom;
        }
        if ($dateTo) {
            $filters['date_to'] = $dateTo;
        }

        // Get recap data with full details
        $records = [];
        $summary = ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0, 'T' => 0, 'total' => 0];

        if (!empty($filters) || true) { // Show all by default
            $records = $recordModel->getRecordsWithDetails($filters ?? []);

            // Calculate summary
            foreach ($records as $record) {
                $status = $record['status'] ?? '';
                if (isset($summary[$status])) {
                    $summary[$status]++;
                }
                $summary['total']++;
            }
        }

        $data = [
            'title'       => 'Rekap Absensi',
            'classes'     => $classModel->findAll(),
            'subjects'    => $subjectModel->getSubjectsWithTeacher(),
            'records'     => $records,
            'summary'     => $summary,
            'filters'     => $filters,
            'period'      => $this->request->getGet('period'),
            'start_date'  => $dateFrom,
            'end_date'    => $dateTo,
            'class_id'    => $classId,
        ];

        return view('recap/enterprise_admin', $data);
    }

    /**
     * Rekap untuk Guru
     */
    public function teacherRecap()
    {
        // Hanya guru yang bisa akses
        if (session()->get('role') !== 'guru') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak');
        }

        $teacherId = session()->get('teacher_id');
        $classModel = new ClassModel();
        $subjectModel = new SubjectModel();
        $sessionModel = new AttendanceSessionModel();
        $recordModel = new AttendanceRecordModel();

        // Get filter parameters
        $classId = $this->request->getGet('class_id');
        $subjectId = $this->request->getGet('subject_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Build filters (always filter by teacher_id)
        $filters = ['teacher_id' => $teacherId];

        if ($classId) {
            $filters['class_id'] = $classId;
        }
        if ($subjectId) {
            $filters['subject_id'] = $subjectId;
        }
        if ($dateFrom) {
            $filters['date_from'] = $dateFrom;
        }
        if ($dateTo) {
            $filters['date_to'] = $dateTo;
        }

        // Get attendance records with full details
        $records = $recordModel->getRecordsWithDetails($filters);

        // Get recap data (summary per student)
        $recapData = [];
        if (count($filters) > 1) { // Lebih dari 1 karena teacher_id selalu ada
            $recapData = $recordModel->getRecapByStudent($filters);
        }

        $data = [
            'title'       => 'Rekap Absensi Guru',
            'classes'     => $classModel->findAll(),
            'subjects'    => $subjectModel->getSubjectsByTeacher($teacherId),
            'records'     => $records,
            'recap_data'  => $recapData,
            'filters'     => $filters,
        ];

        return view('recap/enterprise_teacher', $data);
    }
}
