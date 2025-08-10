<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubmissionsTable extends Migration
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
            'quiz_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'answer' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_correct' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'points_earned' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'time_taken' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Time taken in seconds',
            ],
            'submitted_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'graded_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Instructor feedback on submission',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['submitted', 'graded', 'late'],
                'default' => 'submitted',
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
        $this->forge->addKey('quiz_id');
        $this->forge->addKey('submitted_at');
        $this->forge->addKey('status');
        $this->forge->addUniqueKey(['student_id', 'quiz_id']);
        $this->forge->createTable('submissions');
    }

    public function down()
    {
        $this->forge->dropTable('submissions');
    }
}
