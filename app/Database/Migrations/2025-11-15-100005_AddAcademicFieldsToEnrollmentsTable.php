<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAcademicFieldsToEnrollmentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'academic_year_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'course_id',
            ],
            'semester_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'academic_year_id',
            ],
            'term_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'semester_id',
            ],
        ];

        $this->forge->addColumn('enrollments', $fields);

        // Add foreign keys
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('semester_id', 'semesters', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('term_id', 'terms', 'id', 'CASCADE', 'SET NULL');

        // Add indexes
        $this->forge->addKey('academic_year_id');
        $this->forge->addKey('semester_id');
        $this->forge->addKey('term_id');
    }

    public function down()
    {
        // Drop foreign keys first
        $this->forge->dropForeignKey('enrollments', 'enrollments_academic_year_id_foreign');
        $this->forge->dropForeignKey('enrollments', 'enrollments_semester_id_foreign');
        $this->forge->dropForeignKey('enrollments', 'enrollments_term_id_foreign');

        // Drop columns
        $this->forge->dropColumn('enrollments', ['academic_year_id', 'semester_id', 'term_id']);
    }
}

