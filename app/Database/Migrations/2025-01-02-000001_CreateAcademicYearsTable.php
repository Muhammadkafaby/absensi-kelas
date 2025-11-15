<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAcademicYearsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'Contoh: 2024/2025',
            ],
            'start_date' => [
                'type'    => 'DATE',
                'comment' => 'Tanggal mulai tahun ajaran',
            ],
            'end_date' => [
                'type'    => 'DATE',
                'comment' => 'Tanggal selesai tahun ajaran',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1=aktif, 0=tidak aktif',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('academic_years');
    }

    public function down()
    {
        $this->forge->dropTable('academic_years');
    }
}
