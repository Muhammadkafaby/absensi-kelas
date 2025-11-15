<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceSessionModel extends Model
{
    protected $table            = 'attendance_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'date',
        'class_id',
        'subject_id',
        'teacher_id',
        'lesson_hour',
        'created_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'date'       => 'required|valid_date',
        'class_id'   => 'required|integer',
        'subject_id' => 'required|integer',
        'teacher_id' => 'required|integer',
        'created_by' => 'required|integer',
    ];

    protected $validationMessages = [
        'date' => [
            'required' => 'Tanggal harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get sessions dengan relasi lengkap
     */
    public function getSessionsWithRelations($filters = [])
    {
        $builder = $this->select('attendance_sessions.*,
                                  classes.name as class_name,
                                  subjects.name as subject_name,
                                  subjects.code as subject_code,
                                  teachers.name as teacher_name,
                                  users.name as created_by_name')
                        ->join('classes', 'classes.id = attendance_sessions.class_id')
                        ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                        ->join('teachers', 'teachers.id = attendance_sessions.teacher_id')
                        ->join('users', 'users.id = attendance_sessions.created_by')
                        ->orderBy('attendance_sessions.date', 'DESC')
                        ->orderBy('attendance_sessions.created_at', 'DESC');

        // Filter by teacher
        if (isset($filters['teacher_id'])) {
            $builder->where('attendance_sessions.teacher_id', $filters['teacher_id']);
        }

        // Filter by class
        if (isset($filters['class_id'])) {
            $builder->where('attendance_sessions.class_id', $filters['class_id']);
        }

        // Filter by subject
        if (isset($filters['subject_id'])) {
            $builder->where('attendance_sessions.subject_id', $filters['subject_id']);
        }

        // Filter by date range
        if (isset($filters['date_from'])) {
            $builder->where('attendance_sessions.date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $builder->where('attendance_sessions.date <=', $filters['date_to']);
        }

        return $builder->findAll();
    }

    /**
     * Get session dengan detail attendance records
     */
    public function getSessionWithRecords($sessionId)
    {
        $session = $this->select('attendance_sessions.*,
                                  classes.name as class_name,
                                  subjects.name as subject_name,
                                  subjects.code as subject_code,
                                  teachers.name as teacher_name')
                        ->join('classes', 'classes.id = attendance_sessions.class_id')
                        ->join('subjects', 'subjects.id = attendance_sessions.subject_id')
                        ->join('teachers', 'teachers.id = attendance_sessions.teacher_id')
                        ->where('attendance_sessions.id', $sessionId)
                        ->first();

        if ($session) {
            $recordModel = new AttendanceRecordModel();
            $session['records'] = $recordModel->getRecordsBySession($sessionId);
        }

        return $session;
    }
}
