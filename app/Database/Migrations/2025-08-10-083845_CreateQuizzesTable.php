<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizzesTable extends Migration
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
            'lesson_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'question' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'question_type' => [
                'type' => 'ENUM',
                'constraint' => ['multiple_choice', 'true_false', 'essay', 'short_answer'],
                'default' => 'multiple_choice',
            ],
            'options' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of answer options for multiple choice',
            ],
            'correct_answer' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'points' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'comment' => 'Points for correct answer',
            ],
            'time_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Time limit in seconds',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'active', 'inactive'],
                'default' => 'draft',
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
        $this->forge->addKey('lesson_id');
        $this->forge->addKey('question_type');
        $this->forge->addKey('status');
        $this->forge->createTable('quizzes');
    }

    public function down()
    {
        $this->forge->dropTable('quizzes');
    }
}
