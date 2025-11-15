<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\SubjectModel;
use App\Models\AttendanceSessionModel;
use App\Models\AttendanceRecordModel;

class DashboardController extends BaseController
{
    /**
     * Dashboard index - berbeda berdasarkan role
     */
    public function index()
    {
        $role = session()->get('role');

        if ($role === 'admin') {
            return $this->adminDashboard();
        } elseif ($role === 'guru') {
            return $this->guruDashboard();
        }

        return redirect()->to('/login');
    }

    /**
     * Dashboard untuk Admin
     */
    private function adminDashboard()
    {
        $classModel = new ClassModel();
        $studentModel = new StudentModel();
        $teacherModel = new TeacherModel();
        $subjectModel = new SubjectModel();
        $recordModel = new AttendanceRecordModel();

        $data = [
            'title'         => 'Dashboard Admin',
            'total_classes' => $classModel->countAll(),
            'total_students' => $studentModel->where('status', 'active')->countAllResults(),
            'total_teachers' => $teacherModel->countAll(),
            'total_subjects' => $subjectModel->countAll(),
            'alpa_today'    => $recordModel->getTodayAlpaStudents(),
        ];

        return view('dashboard/admin', $data);
    }

    /**
     * Dashboard untuk Guru
     */
    private function guruDashboard()
    {
        $teacherId = session()->get('teacher_id');
        $sessionModel = new AttendanceSessionModel();
        $subjectModel = new SubjectModel();

        // Get mata pelajaran yang diampu
        $subjects = $subjectModel->getSubjectsByTeacher($teacherId);

        // Get recent sessions dari guru ini
        $recentSessions = $sessionModel->getSessionsWithRelations([
            'teacher_id' => $teacherId,
        ]);

        // Limit ke 10 terakhir
        $recentSessions = array_slice($recentSessions, 0, 10);

        $data = [
            'title'           => 'Dashboard Guru',
            'subjects'        => $subjects,
            'recent_sessions' => $recentSessions,
            'total_sessions'  => count($recentSessions),
        ];

        return view('dashboard/guru', $data);
    }
}
