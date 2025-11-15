<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClassesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Kelas X
            ['name' => 'X-1', 'level' => 'X', 'major' => null],
            ['name' => 'X-2', 'level' => 'X', 'major' => null],
            ['name' => 'X-3', 'level' => 'X', 'major' => null],
            ['name' => 'X-4', 'level' => 'X', 'major' => null],

            // Kelas XI
            ['name' => 'XI IPA 1', 'level' => 'XI', 'major' => 'IPA'],
            ['name' => 'XI IPA 2', 'level' => 'XI', 'major' => 'IPA'],
            ['name' => 'XI IPS 1', 'level' => 'XI', 'major' => 'IPS'],
            ['name' => 'XI IPS 2', 'level' => 'XI', 'major' => 'IPS'],

            // Kelas XII
            ['name' => 'XII IPA 1', 'level' => 'XII', 'major' => 'IPA'],
            ['name' => 'XII IPA 2', 'level' => 'XII', 'major' => 'IPA'],
            ['name' => 'XII IPS 1', 'level' => 'XII', 'major' => 'IPS'],
            ['name' => 'XII IPS 2', 'level' => 'XII', 'major' => 'IPS'],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('classes')->insert($row);
        }
    }
}
