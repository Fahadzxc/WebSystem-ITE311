<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignmentAnswersTable extends Migration
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
            'submission_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'question_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'selected_answer' => [
                'type' => 'ENUM',
                'constraint' => ['a', 'b', 'c', 'd'],
                'null' => true,
            ],
            'is_correct' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'points_earned' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
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
        $this->forge->addKey(['submission_id', 'question_id']);
        $this->forge->addForeignKey('submission_id', 'assignment_submissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('question_id', 'assignment_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assignment_answers');
    }

    public function down()
    {
        $this->forge->dropTable('assignment_answers');
    }
}

