<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQuestionTypeToAssignmentQuestionsTable extends Migration
{
    public function up()
    {
        $fields = [
            'question_type' => [
                'type' => 'ENUM',
                'constraint' => ['multiple_choice', 'essay', 'file_upload'],
                'default' => 'multiple_choice',
                'after' => 'assignment_id'
            ]
        ];

        $this->forge->addColumn('assignment_questions', $fields);
        
        // Make option fields nullable for non-multiple choice questions
        $this->db->query("ALTER TABLE assignment_questions MODIFY option_a VARCHAR(255) NULL");
        $this->db->query("ALTER TABLE assignment_questions MODIFY option_b VARCHAR(255) NULL");
        $this->db->query("ALTER TABLE assignment_questions MODIFY option_c VARCHAR(255) NULL");
        $this->db->query("ALTER TABLE assignment_questions MODIFY option_d VARCHAR(255) NULL");
        $this->db->query("ALTER TABLE assignment_questions MODIFY correct_answer ENUM('a', 'b', 'c', 'd') NULL");
    }

    public function down()
    {
        $this->forge->dropColumn('assignment_questions', ['question_type']);
    }
}
