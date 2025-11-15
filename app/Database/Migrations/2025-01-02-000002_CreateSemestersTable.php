<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSemestersTable extends Migration
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
            'academic_year_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Semester Ganjil / Semester Genap',
            ],
            'start_date' => [
                'type'    => 'DATE',
                'comment' => 'Tanggal mulai semester',
            ],
            'end_date' => [
                'type'    => 'DATE',
                'comment' => 'Tanggal selesai semester',
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
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('semesters');
    }

    public function down()
    {
        $this->forge->dropTable('semesters');
    }
}
