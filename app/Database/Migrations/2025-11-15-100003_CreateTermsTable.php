<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTermsTable extends Migration
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
            'term_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'e.g., "1st Term", "2nd Term", "3rd Term", "Summer"',
            ],
            'term_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => 'Numeric: 1, 2, 3, or 4 (for summer)',
            ],
            'is_summer' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 if this is the summer term',
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
        $this->forge->addKey('term_number');
        $this->forge->addKey('is_summer');
        $this->forge->createTable('terms');
    }

    public function down()
    {
        $this->forge->dropTable('terms');
    }
}

