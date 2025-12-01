<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignmentQuestionsTable extends Migration
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
            'assignment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'question_text' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'option_a' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'option_b' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'option_c' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'option_d' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'correct_answer' => [
                'type' => 'ENUM',
                'constraint' => ['a', 'b', 'c', 'd'],
            ],
            'points' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 1.00,
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
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
        $this->forge->addKey('assignment_id');
        $this->forge->addForeignKey('assignment_id', 'assignments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assignment_questions');
    }

    public function down()
    {
        $this->forge->dropTable('assignment_questions');
    }
}

