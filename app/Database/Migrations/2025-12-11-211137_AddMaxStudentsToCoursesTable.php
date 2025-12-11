<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMaxStudentsToCoursesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'max_students' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'default' => null,
                'comment' => 'Maximum number of students allowed to enroll in this course'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'max_students');
    }
}
