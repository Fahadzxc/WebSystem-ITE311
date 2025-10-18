<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the Learning Management System',
                'content' => 'Welcome to our new Learning Management System! This platform will help you manage your courses, track your progress, and stay connected with your instructors and fellow students.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'System Maintenance Notice',
                'content' => 'Please be informed that the system will undergo scheduled maintenance on Sunday, October 20, 2025, from 2:00 AM to 4:00 AM. During this time, the system may be temporarily unavailable.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'New Course Available',
                'content' => 'We are excited to announce that a new course "Advanced Web Development" is now available for enrollment. This course covers modern web technologies including React, Node.js, and database integration.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}
