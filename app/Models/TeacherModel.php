<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table            = 'teachers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'nip',
        'phone',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama guru harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get teachers dengan jumlah mata pelajaran
     */
    public function getTeachersWithSubjectCount()
    {
        return $this->select('teachers.*, COUNT(subjects.id) as subject_count')
                    ->join('subjects', 'subjects.teacher_id = teachers.id', 'left')
                    ->groupBy('teachers.id')
                    ->orderBy('teachers.name', 'ASC')
                    ->findAll();
    }
}
