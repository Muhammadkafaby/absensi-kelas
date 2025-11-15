<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TeachersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'Drs. Ahmad Fauzi, M.Pd', 'nip' => '196701011990031001', 'phone' => '081234567890'],
            ['name' => 'Siti Nurjanah, S.Pd', 'nip' => '197205101995122001', 'phone' => '081234567891'],
            ['name' => 'Budi Santoso, S.Pd', 'nip' => '198003151999031002', 'phone' => '081234567892'],
            ['name' => 'Dewi Lestari, S.Si, M.Pd', 'nip' => '198506202008012003', 'phone' => '081234567893'],
            ['name' => 'Agus Setiawan, S.Pd', 'nip' => '197812121999121001', 'phone' => '081234567894'],
            ['name' => 'Rina Wati, S.Pd', 'nip' => '198209052005012004', 'phone' => '081234567895'],
            ['name' => 'Hendra Gunawan, S.Kom', 'nip' => '199001152015031001', 'phone' => '081234567896'],
            ['name' => 'Maya Sari, S.Pd', 'nip' => '198804102010012005', 'phone' => '081234567897'],
        ];

        foreach ($data as $row) {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('teachers')->insert($row);
        }
    }
}
