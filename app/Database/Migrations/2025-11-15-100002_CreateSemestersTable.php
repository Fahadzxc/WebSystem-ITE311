<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSemestersTable extends Migration
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
            'semester_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'e.g., "First Semester", "Second Semester"',
            ],
            'semester_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => 'Numeric: 1 or 2',
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
        $this->forge->addKey('semester_number');
        $this->forge->createTable('semesters');
    }

    public function down()
    {
        $this->forge->dropTable('semesters');
    }
}

