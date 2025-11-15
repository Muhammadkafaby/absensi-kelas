<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentsSeeder extends Seeder
{
    public function run()
    {
        // Sample students untuk kelas X-1 (class_id = 1)
        $students_x1 = [
            ['nis' => '2024001', 'nisn' => '0051234567', 'name' => 'Ahmad Rizki Pratama', 'gender' => 'L'],
            ['nis' => '2024002', 'nisn' => '0051234568', 'name' => 'Siti Aisyah Rahmawati', 'gender' => 'P'],
            ['nis' => '2024003', 'nisn' => '0051234569', 'name' => 'Budi Santosa', 'gender' => 'L'],
            ['nis' => '2024004', 'nisn' => '0051234570', 'name' => 'Dewi Lestari', 'gender' => 'P'],
            ['nis' => '2024005', 'nisn' => '0051234571', 'name' => 'Eko Prasetyo', 'gender' => 'L'],
            ['nis' => '2024006', 'nisn' => '0051234572', 'name' => 'Fitri Handayani', 'gender' => 'P'],
            ['nis' => '2024007', 'nisn' => '0051234573', 'name' => 'Hadi Wijaya', 'gender' => 'L'],
            ['nis' => '2024008', 'nisn' => '0051234574', 'name' => 'Indah Permata Sari', 'gender' => 'P'],
            ['nis' => '2024009', 'nisn' => '0051234575', 'name' => 'Joko Susanto', 'gender' => 'L'],
            ['nis' => '2024010', 'nisn' => '0051234576', 'name' => 'Kartika Putri', 'gender' => 'P'],
        ];

        foreach ($students_x1 as $student) {
            $student['class_id'] = 1; // X-1
            $student['status'] = 'active';
            $student['created_at'] = date('Y-m-d H:i:s');
            $student['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('students')->insert($student);
        }

        // Sample students untuk kelas XI IPA 1 (class_id = 5)
        $students_xi_ipa1 = [
            ['nis' => '2023001', 'nisn' => '0041234567', 'name' => 'Muhammad Farhan', 'gender' => 'L'],
            ['nis' => '2023002', 'nisn' => '0041234568', 'name' => 'Nur Azizah', 'gender' => 'P'],
            ['nis' => '2023003', 'nisn' => '0041234569', 'name' => 'Reza Firmansyah', 'gender' => 'L'],
            ['nis' => '2023004', 'nisn' => '0041234570', 'name' => 'Sarah Amelia', 'gender' => 'P'],
            ['nis' => '2023005', 'nisn' => '0041234571', 'name' => 'Taufik Hidayat', 'gender' => 'L'],
            ['nis' => '2023006', 'nisn' => '0041234572', 'name' => 'Vina Maulida', 'gender' => 'P'],
            ['nis' => '2023007', 'nisn' => '0041234573', 'name' => 'Wahyu Nugroho', 'gender' => 'L'],
            ['nis' => '2023008', 'nisn' => '0041234574', 'name' => 'Yuni Kartika', 'gender' => 'P'],
        ];

        foreach ($students_xi_ipa1 as $student) {
            $student['class_id'] = 5; // XI IPA 1
            $student['status'] = 'active';
            $student['created_at'] = date('Y-m-d H:i:s');
            $student['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('students')->insert($student);
        }

        // Sample students untuk kelas XII IPA 1 (class_id = 9)
        $students_xii_ipa1 = [
            ['nis' => '2022001', 'nisn' => '0031234567', 'name' => 'Andi Setiawan', 'gender' => 'L'],
            ['nis' => '2022002', 'nisn' => '0031234568', 'name' => 'Bella Safitri', 'gender' => 'P'],
            ['nis' => '2022003', 'nisn' => '0031234569', 'name' => 'Candra Kirana', 'gender' => 'L'],
            ['nis' => '2022004', 'nisn' => '0031234570', 'name' => 'Dini Anggraini', 'gender' => 'P'],
            ['nis' => '2022005', 'nisn' => '0031234571', 'name' => 'Erlangga Putra', 'gender' => 'L'],
        ];

        foreach ($students_xii_ipa1 as $student) {
            $student['class_id'] = 9; // XII IPA 1
            $student['status'] = 'active';
            $student['created_at'] = date('Y-m-d H:i:s');
            $student['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('students')->insert($student);
        }
    }
}
