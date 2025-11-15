<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run()
    {
        // Academic Year 2024/2025
        $academicYear = [
            'name'       => '2024/2025',
            'start_date' => '2024-07-15',
            'end_date'   => '2025-06-30',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('academic_years')->insert($academicYear);
        $academicYearId = $this->db->insertID();

        // Semesters
        $semesters = [
            [
                'academic_year_id' => $academicYearId,
                'name'             => 'Semester Ganjil',
                'start_date'       => '2024-07-15',
                'end_date'         => '2024-12-31',
                'is_active'        => 1,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'academic_year_id' => $academicYearId,
                'name'             => 'Semester Genap',
                'start_date'       => '2025-01-02',
                'end_date'         => '2025-06-30',
                'is_active'        => 0,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($semesters as $semester) {
            $this->db->table('semesters')->insert($semester);
        }
    }
}
