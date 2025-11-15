<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Urutan seeding penting karena foreign keys
        $this->call('ClassesSeeder');
        $this->call('TeachersSeeder');
        $this->call('SubjectsSeeder');
        $this->call('UsersSeeder');
        $this->call('StudentsSeeder');
    }
}
