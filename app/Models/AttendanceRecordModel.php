<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceRecordModel extends Model
{
    protected $table            = 'attendance_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'attendance_session_id',
        'student_id',
        'status',
        'note',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'attendance_session_id' => 'required|integer',
        'student_id'            => 'required|integer',
        'status'                => 'required|in_list[H,I,S,A,T]',
    ];

    protected $validationMessages = [
        'status' => [
            'required' => 'Status kehadiran harus diisi',
            'in_list'  => 'Status harus salah satu dari: H, I, S, A, T',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get records by session dengan relasi student
     */
    public function getRecordsBySession($sessionId)
    {
        return $this->select('attendance_records.*,
                              students.nis,
                              students.name as student_name,
                              students.gender')
                    ->join('students', 'students.id = attendance_records.student_id')
                    ->where('attendance_records.attendance_session_id', $sessionId)
                    ->orderBy('students.name', 'ASC')
                    ->findAll();
    }

    /**
     * Get rekap kehadiran per siswa
     * Menghitung H, I, S, A, T untuk setiap siswa dalam range tertentu
     */
    public function getRecapByStudent($filters = [])
    {
        $builder = $this->db->table('attendance_records')
                            ->select('students.id as student_id,
                                     students.nis,
                                     students.name as student_name,
                                     classes.name as class_name,
                                     SUM(CASE WHEN attendance_records.status = "H" THEN 1 ELSE 0 END) as hadir,
                                     SUM(CASE WHEN attendance_records.status = "I" THEN 1 ELSE 0 END) as izin,
                                     SUM(CASE WHEN attendance_records.status = "S" THEN 1 ELSE 0 END) as sakit,
                                     SUM(CASE WHEN attendance_records.status = "A" THEN 1 ELSE 0 END) as alpa,
                                     SUM(CASE WHEN attendance_records.status = "T" THEN 1 ELSE 0 END) as terlambat,
                                     COUNT(*) as total_pertemuan')
                            ->join('students', 'students.id = attendance_records.student_id')
                            ->join('classes', 'classes.id = students.class_id')
                            ->join('attendance_sessions', 'attendance_sessions.id = attendance_records.attendance_session_id');

        // Filter by class
        if (isset($filters['class_id'])) {
            $builder->where('students.class_id', $filters['class_id']);
        }

        // Filter by subject
        if (isset($filters['subject_id'])) {
            $builder->where('attendance_sessions.subject_id', $filters['subject_id']);
        }

        // Filter by teacher
        if (isset($filters['teacher_id'])) {
            $builder->where('attendance_sessions.teacher_id', $filters['teacher_id']);
        }

        // Filter by date range
        if (isset($filters['date_from'])) {
            $builder->where('attendance_sessions.date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $builder->where('attendance_sessions.date <=', $filters['date_to']);
        }

        $builder->groupBy('students.id')
               ->orderBy('classes.name', 'ASC')
               ->orderBy('students.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get records with full details untuk recap admin
     */
    public function getRecordsWithDetails($filters = [])
    {
        $builder = $this->db->table('attendance_records')
                            ->select('attendance_records.*,
                                     attendance_records.attendance_session_id as session_id,
                                     students.nis,
                                     students.name as student_name,
                                     classes.name as class_name,
                                     subjects.name as subject_name,
                                     subjects.code as subject_code,
                                     teachers.name as teacher_name,
                                     attendance_sessions.date,
                                     attendance_sessions.lesson_hour')
                            ->join('students', 'students.id = attendance_records.student_id')
                            ->join('classes', 'classes.id = students.class_id')
                            ->join('attendance_sessions', 'attendance_sessions.id = attendance_records.attendance_session_id')
                            ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                            ->join('teachers', 'teachers.id = attendance_sessions.teacher_id');

        // Apply filters
        if (isset($filters['class_id'])) {
            $builder->where('students.class_id', $filters['class_id']);
        }

        if (isset($filters['subject_id'])) {
            $builder->where('attendance_sessions.subject_id', $filters['subject_id']);
        }

        if (isset($filters['teacher_id'])) {
            $builder->where('attendance_sessions.teacher_id', $filters['teacher_id']);
        }

        if (isset($filters['date_from'])) {
            $builder->where('attendance_sessions.date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $builder->where('attendance_sessions.date <=', $filters['date_to']);
        }

        $builder->orderBy('attendance_sessions.date', 'DESC')
               ->orderBy('attendance_sessions.lesson_hour', 'ASC')
               ->orderBy('classes.name', 'ASC')
               ->orderBy('students.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get siswa yang alpa hari ini
     */
    public function getTodayAlpaStudents()
    {
        return $this->select('attendance_records.*,
                              students.nis,
                              students.name as student_name,
                              classes.name as class_name,
                              subjects.name as subject_name,
                              attendance_sessions.lesson_hour,
                              teachers.name as teacher_name,
                              attendance_sessions.date')
                    ->join('students', 'students.id = attendance_records.student_id')
                    ->join('classes', 'classes.id = students.class_id')
                    ->join('attendance_sessions', 'attendance_sessions.id = attendance_records.attendance_session_id')
                    ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                    ->join('teachers', 'teachers.id = attendance_sessions.teacher_id')
                    ->where('attendance_records.status', 'A')
                    ->where('attendance_sessions.date', date('Y-m-d'))
                    ->orderBy('classes.name', 'ASC')
                    ->orderBy('students.name', 'ASC')
                    ->findAll();
    }
}
