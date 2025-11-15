<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSemesterToAttendanceSessions extends Migration
{
    public function up()
    {
        $fields = [
            'semester_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'teacher_id',
            ],
        ];

        $this->forge->addColumn('attendance_sessions', $fields);

        // Add foreign key
        $this->forge->processIndexes('attendance_sessions');
        $this->db->query('ALTER TABLE attendance_sessions ADD CONSTRAINT fk_attendance_sessions_semester FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key first
        $this->db->query('ALTER TABLE attendance_sessions DROP FOREIGN KEY fk_attendance_sessions_semester');

        $this->forge->dropColumn('attendance_sessions', 'semester_id');
    }
}
