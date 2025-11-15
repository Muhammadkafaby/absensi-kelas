<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table            = 'subjects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'code',
        'teacher_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'code' => 'required|max_length[20]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama mata pelajaran harus diisi',
        ],
        'code' => [
            'required' => 'Kode mata pelajaran harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get subjects dengan relasi teacher
     */
    public function getSubjectsWithTeacher()
    {
        return $this->select('subjects.*, teachers.name as teacher_name')
                    ->join('teachers', 'teachers.id = subjects.teacher_id', 'left')
                    ->orderBy('subjects.name', 'ASC')
                    ->findAll();
    }

    /**
     * Get subjects by teacher
     */
    public function getSubjectsByTeacher($teacherId)
    {
        return $this->where('teacher_id', $teacherId)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
