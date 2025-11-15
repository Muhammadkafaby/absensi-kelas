<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table            = 'semesters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'academic_year_id',
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
        'academic_year_id' => 'required|integer',
        'name'             => 'required|max_length[50]',
        'start_date'       => 'required|valid_date',
        'end_date'         => 'required|valid_date',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama semester harus diisi',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get active semester
     */
    public function getActive()
    {
        return $this->where('is_active', 1)->first();
    }

    /**
     * Set active semester
     */
    public function setActive($id)
    {
        // Deactivate all
        $this->where('is_active', 1)->set(['is_active' => 0])->update();

        // Activate selected
        return $this->update($id, ['is_active' => 1]);
    }

    /**
     * Get semesters with academic year info
     */
    public function getSemestersWithAcademicYear()
    {
        return $this->select('semesters.*, academic_years.name as year_name')
                    ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
                    ->orderBy('semesters.start_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get semesters by academic year
     */
    public function getByAcademicYear($academicYearId)
    {
        return $this->select('semesters.*, academic_years.name as year_name')
                    ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
                    ->where('semesters.academic_year_id', $academicYearId)
                    ->orderBy('semesters.start_date', 'ASC')
                    ->findAll();
    }
}
