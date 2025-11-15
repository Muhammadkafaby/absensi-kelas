<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table            = 'classes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'level',
        'major',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'  => 'required|min_length[1]|max_length[100]',
        'level' => 'required|max_length[10]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama kelas harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get kelas dengan jumlah siswa
     */
    public function getClassesWithStudentCount()
    {
        return $this->select('classes.*, COUNT(students.id) as student_count')
                    ->join('students', 'students.class_id = classes.id', 'left')
                    ->groupBy('classes.id')
                    ->orderBy('classes.level', 'ASC')
                    ->orderBy('classes.name', 'ASC')
                    ->findAll();
    }
}
