<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'enrollment_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'completion_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'progress' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'comment' => 'Progress percentage (0-100)',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'completed', 'dropped'],
                'default' => 'active',
            ],
            'grade' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'Final grade (0.00-100.00)',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('student_id');
        $this->forge->addKey('course_id');
        $this->forge->addKey('status');
        $this->forge->addUniqueKey(['student_id', 'course_id']);
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
    }
}
