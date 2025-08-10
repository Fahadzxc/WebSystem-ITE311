<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonsTable extends Migration
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
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'content' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'lesson_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'comment' => 'Order of lesson within course',
            ],
            'duration' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Duration in minutes',
            ],
            'video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'attachments' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON array of attachment URLs',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published', 'archived'],
                'default' => 'draft',
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
        $this->forge->addKey('course_id');
        $this->forge->addKey('lesson_order');
        $this->forge->addKey('status');
        $this->forge->createTable('lessons');
    }

    public function down()
    {
        $this->forge->dropTable('lessons');
    }
}
