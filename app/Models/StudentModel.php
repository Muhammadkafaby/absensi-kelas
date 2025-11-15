<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nis',
        'nisn',
        'name',
        'class_id',
        'gender',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nis'      => 'required|max_length[50]',
        'name'     => 'required|max_length[255]',
        'class_id' => 'required|integer',
        'gender'   => 'required|in_list[L,P]',
        'status'   => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'nis' => [
            'required' => 'NIS harus diisi',
        ],
        'name' => [
            'required' => 'Nama siswa harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get students dengan relasi kelas
     */
    public function getStudentsWithClass($classId = null)
    {
        $builder = $this->select('students.*, classes.name as class_name, classes.level, classes.major')
                        ->join('classes', 'classes.id = students.class_id')
                        ->orderBy('students.name', 'ASC');

        if ($classId) {
            $builder->where('students.class_id', $classId);
        }

        return $builder->findAll();
    }

    /**
     * Get active students by class
     */
    public function getActiveStudentsByClass($classId)
    {
        return $this->where('class_id', $classId)
                    ->where('status', 'active')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
