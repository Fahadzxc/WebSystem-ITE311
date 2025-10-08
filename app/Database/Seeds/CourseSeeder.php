<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for beginners who want to start their journey in web development.',
                'instructor_id' => 3, // teacher user ID
                'category' => 'Web Development',
                'level' => 'beginner',
                'duration' => 1800, // 30 minutes
                'price' => 0.00,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Database Management Systems',
                'description' => 'Comprehensive course covering database design, SQL queries, and database administration. Learn to work with MySQL and other database systems.',
                'instructor_id' => 3, // teacher user ID
                'category' => 'Database',
                'level' => 'intermediate',
                'duration' => 2400, // 40 minutes
                'price' => 0.00,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'PHP Programming Fundamentals',
                'description' => 'Master PHP programming from basics to advanced concepts. Learn server-side scripting, database integration, and web application development.',
                'instructor_id' => 3, // teacher user ID
                'category' => 'Programming',
                'level' => 'beginner',
                'duration' => 2100, // 35 minutes
                'price' => 0.00,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'CodeIgniter Framework',
                'description' => 'Learn to build web applications using the CodeIgniter PHP framework. Cover MVC architecture, routing, and best practices.',
                'instructor_id' => 3, // teacher user ID
                'category' => 'Framework',
                'level' => 'intermediate',
                'duration' => 2700, // 45 minutes
                'price' => 0.00,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'JavaScript Advanced Concepts',
                'description' => 'Deep dive into advanced JavaScript concepts including ES6+, async programming, and modern web development practices.',
                'instructor_id' => 3, // teacher user ID
                'category' => 'Programming',
                'level' => 'advanced',
                'duration' => 3000, // 50 minutes
                'price' => 0.00,
                'status' => 'published',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Insert all courses
        $this->db->table('courses')->insertBatch($data);
        
        echo "Sample courses created successfully!\n";
    }
}
