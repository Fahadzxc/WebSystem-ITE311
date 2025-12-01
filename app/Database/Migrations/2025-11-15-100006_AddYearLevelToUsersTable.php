<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddYearLevelToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'year_level_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'role',
                'comment' => 'Year level for students (1st Year, 2nd Year, etc.)',
            ],
        ];

        $this->forge->addColumn('users', $fields);

        // Add foreign key
        $this->forge->addForeignKey('year_level_id', 'year_levels', 'id', 'CASCADE', 'SET NULL');

        // Add index
        $this->forge->addKey('year_level_id');
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('users', 'users_year_level_id_foreign');

        // Drop column
        $this->forge->dropColumn('users', 'year_level_id');
    }
}

