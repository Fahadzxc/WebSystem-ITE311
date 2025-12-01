<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateYearLevelsTable extends Migration
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
            'level_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'e.g., "1st Year", "2nd Year", "3rd Year", "4th Year"',
            ],
            'level_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => 'Numeric level: 1, 2, 3, 4',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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
        $this->forge->addKey('level_number');
        $this->forge->createTable('year_levels');
    }

    public function down()
    {
        $this->forge->dropTable('year_levels');
    }
}

