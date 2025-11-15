<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicYearModel extends Model
{
    protected $table            = 'academic_years';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'       => 'required|max_length[100]',
        'start_date' => 'required|valid_date',
        'end_date'   => 'required|valid_date',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama tahun ajaran harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get active academic year
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->first();
    }

    /**
     * Set active academic year
     */
    public function setActive($id)
    {
        // Deactivate all
        $this->where('is_active', 1)->set(['is_active' => 0])->update();

        // Activate selected
        return $this->update($id, ['is_active' => 1]);
    }

    /**
     * Get academic years with semester count
     */
    public function getWithSemesterCount()
    {
        return $this->select('academic_years.*, COUNT(semesters.id) as semester_count')
                    ->join('semesters', 'semesters.academic_year_id = academic_years.id', 'left')
                    ->groupBy('academic_years.id')
                    ->orderBy('academic_years.start_date', 'DESC')
                    ->findAll();
    }
}
