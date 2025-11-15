<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin default
            [
                'username'      => 'admin',
                'email'         => 'admin@smanu-kaplongan.sch.id',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'name'          => 'Administrator TU',
                'role'          => 'admin',
                'teacher_id'    => null,
            ],
            // Guru default (terhubung dengan teacher id 1)
            [
                'username'      => 'guru',
                'email'         => 'guru@smanu-kaplongan.sch.id',
                'password_hash' => password_hash('guru123', PASSWORD_DEFAULT),
                'name'          => 'Drs. Ahmad Fauzi, M.Pd',
                'role'          => 'guru',
                'teacher_id'    => 1,
            ],
            // Guru lainnya
            [
                'username'      => 'siti.nurjanah',
                'email'         => 'siti@smanu-kaplongan.sch.id',
                'password_hash' => password_hash('guru123', PASSWORD_DEFAULT),
                'name'          => 'Siti Nurjanah, S.Pd',
                'role'          => 'guru',
                'teacher_id'    => 2,
            ],
            [
                'username'      => 'budi.santoso',
                'email'         => 'budi@smanu-kaplongan.sch.id',
                'password_hash' => password_hash('guru123', PASSWORD_DEFAULT),
                'name'          => 'Budi Santoso, S.Pd',
                'role'          => 'guru',
                'teacher_id'    => 3,
            ],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('users')->insert($row);
        }
    }
}
