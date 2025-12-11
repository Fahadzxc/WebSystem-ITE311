<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPendingStatusToEnrollmentsTable extends Migration
{
    public function up()
    {
        // Update status ENUM to include 'pending' and 'rejected'
        $this->db->query("ALTER TABLE enrollments MODIFY COLUMN status ENUM('pending', 'active', 'completed', 'dropped', 'suspended', 'rejected') DEFAULT 'pending'");
        
        // Add rejection_reason field
        $fields = [
            'rejection_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status'
            ]
        ];
        
        $this->forge->addColumn('enrollments', $fields);
    }

    public function down()
    {
        // Remove rejection_reason column
        $this->forge->dropColumn('enrollments', ['rejection_reason']);
        
        // Revert status ENUM (remove pending and rejected)
        $this->db->query("ALTER TABLE enrollments MODIFY COLUMN status ENUM('active', 'completed', 'dropped', 'suspended') DEFAULT 'active'");
    }
}
