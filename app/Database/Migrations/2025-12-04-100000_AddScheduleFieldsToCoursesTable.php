<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddScheduleFieldsToCoursesTable extends Migration
{
    public function up()
    {
        $fields = [
            'day_of_week' => [
                'type' => 'ENUM',
                'constraint' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'null' => true,
                'after' => 'instructor_id'
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'day_of_week'
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => true,
                'after' => 'start_time'
            ]
        ];

        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', ['day_of_week', 'start_time', 'end_time']);
    }
}
