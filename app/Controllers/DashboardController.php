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
        $sessionModel = new AttendanceSessionModel();

        // Basic stats
        $data = [
            'title'         => 'Dashboard Admin',
            'total_classes' => $classModel->countAll(),
            'total_students' => $studentModel->where('status', 'active')->countAllResults(),
            'total_teachers' => $teacherModel->countAll(),
            'total_subjects' => $subjectModel->countAll(),
            'alpa_today'    => $recordModel->getTodayAlpaStudents(),
        ];

        // Chart data: Attendance by Status (Last 30 days)
        $db = \Config\Database::connect();
        $attendanceStats = $db->query("
            SELECT
                ar.status,
                COUNT(*) as count
            FROM attendance_records ar
            JOIN attendance_sessions asess ON asess.id = ar.attendance_session_id
            WHERE asess.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY ar.status
        ")->getResultArray();

        $statusData = ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0, 'T' => 0];
        foreach ($attendanceStats as $stat) {
            $statusData[$stat['status']] = (int)$stat['count'];
        }
        $data['chart_status_data'] = $statusData;

        // Chart data: Attendance by Class (Last 30 days)
        $attendanceByClass = $db->query("
            SELECT
                c.name as class_name,
                COUNT(CASE WHEN ar.status = 'H' THEN 1 END) as hadir,
                COUNT(*) as total
            FROM classes c
            LEFT JOIN students s ON s.class_id = c.id
            LEFT JOIN attendance_records ar ON ar.student_id = s.id
            LEFT JOIN attendance_sessions asess ON asess.id = ar.attendance_session_id
            WHERE asess.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY c.id, c.name
            ORDER BY c.name
            LIMIT 10
        ")->getResultArray();

        $data['chart_class_data'] = $attendanceByClass;

        // Chart data: Trend 7 hari terakhir
        $trendData = $db->query("
            SELECT
                asess.date,
                COUNT(CASE WHEN ar.status = 'H' THEN 1 END) as hadir,
                COUNT(*) as total
            FROM attendance_sessions asess
            JOIN attendance_records ar ON ar.attendance_session_id = asess.id
            WHERE asess.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY asess.date
            ORDER BY asess.date ASC
        ")->getResultArray();

        $data['chart_trend_data'] = $trendData;

        return view('dashboard/enterprise_admin', $data);
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

        // Chart data untuk guru: Status attendance dari sessions yang dibuat
        $db = \Config\Database::connect();
        $teacherStats = $db->query("
            SELECT
                ar.status,
                COUNT(*) as count
            FROM attendance_records ar
            JOIN attendance_sessions asess ON asess.id = ar.attendance_session_id
            WHERE asess.teacher_id = ? AND asess.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY ar.status
        ", [$teacherId])->getResultArray();

        $statusData = ['H' => 0, 'I' => 0, 'S' => 0, 'A' => 0, 'T' => 0];
        foreach ($teacherStats as $stat) {
            $statusData[$stat['status']] = (int)$stat['count'];
        }
        $data['chart_status_data'] = $statusData;

        // Trend 7 hari terakhir untuk guru
        $teacherTrend = $db->query("
            SELECT
                asess.date,
                COUNT(CASE WHEN ar.status = 'H' THEN 1 END) as hadir,
                COUNT(*) as total
            FROM attendance_sessions asess
            JOIN attendance_records ar ON ar.attendance_session_id = asess.id
            WHERE asess.teacher_id = ? AND asess.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY asess.date
            ORDER BY asess.date ASC
        ", [$teacherId])->getResultArray();

        $data['chart_trend_data'] = $teacherTrend;

        return view('dashboard/enterprise_guru', $data);
    }
}
