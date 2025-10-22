<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the New Academic Year!',
                'content' => 'We are excited to welcome all students, teachers, and staff to the new academic year. This year brings new opportunities for learning and growth. Please make sure to review your course schedules and familiarize yourself with the updated learning management system.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'System Maintenance Scheduled',
                'content' => 'Please be informed that our learning management system will undergo scheduled maintenance on Sunday, October 20th, from 2:00 AM to 6:00 AM. During this time, the system will be temporarily unavailable. We apologize for any inconvenience this may cause.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'title' => 'Midterm Examination Schedule Released',
                'content' => 'The midterm examination schedule has been released and is now available in your student portal. Please check your individual exam schedules and prepare accordingly. If you have any conflicts or concerns, please contact your course instructors immediately.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Library Hours Extended',
                'content' => 'Good news! The library will now be open until 10:00 PM on weekdays to accommodate students who need extra study time. Weekend hours remain 9:00 AM to 5:00 PM. Please make use of these extended hours for your academic success.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
        ];

        // Insert all announcements
        $this->db->table('announcements')->insertBatch($data);
        
        echo "Sample announcements created successfully!\n";
    }
}
