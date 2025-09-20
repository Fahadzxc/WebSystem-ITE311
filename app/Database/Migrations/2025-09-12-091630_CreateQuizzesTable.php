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
            'question' => [
                'type' => 'TEXT',
            ],
            'question_type' => [
                'type' => 'ENUM',
                'constraint' => ['multiple_choice', 'true_false', 'short_answer'],
                'default' => 'multiple_choice',
            ],
            'options' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON array of answer options for multiple choice',
            ],
            'correct_answer' => [
                'type' => 'TEXT',
            ],
            'points' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'order_index' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
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
        $this->forge->createTable('quizzes');
    }

    public function down()
    {
        $this->forge->dropTable('quizzes');
    }
}
