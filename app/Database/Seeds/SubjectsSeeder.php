<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Matematika', 'code' => 'MTK', 'teacher_id' => 1],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN', 'teacher_id' => 2],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'teacher_id' => 3],
            ['name' => 'Fisika', 'code' => 'FIS', 'teacher_id' => 4],
            ['name' => 'Kimia', 'code' => 'KIM', 'teacher_id' => 4],
            ['name' => 'Biologi', 'code' => 'BIO', 'teacher_id' => 5],
            ['name' => 'Sejarah', 'code' => 'SEJ', 'teacher_id' => 6],
            ['name' => 'Geografi', 'code' => 'GEO', 'teacher_id' => 6],
            ['name' => 'Ekonomi', 'code' => 'EKO', 'teacher_id' => 7],
            ['name' => 'Sosiologi', 'code' => 'SOS', 'teacher_id' => 7],
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI', 'teacher_id' => 8],
            ['name' => 'Pendidikan Kewarganegaraan', 'code' => 'PKN', 'teacher_id' => 2],
            ['name' => 'Seni Budaya', 'code' => 'SB', 'teacher_id' => 8],
            ['name' => 'Pendidikan Jasmani', 'code' => 'PENJAS', 'teacher_id' => 5],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('subjects')->insert($row);
        }
    }
}
