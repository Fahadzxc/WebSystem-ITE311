<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTextAndFileFieldsToAssignmentAnswersTable extends Migration
{
    public function up()
    {
        $fields = [
            'text_answer' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'selected_answer'
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'text_answer'
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'file_path'
            ],
            'teacher_feedback' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'points_earned'
            ]
        ];

        $this->forge->addColumn('assignment_answers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('assignment_answers', ['text_answer', 'file_path', 'file_name', 'teacher_feedback']);
    }
}
