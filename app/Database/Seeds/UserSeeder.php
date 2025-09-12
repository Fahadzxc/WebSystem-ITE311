<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin User
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@lms.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'phone' => '09123456789',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Instructor Users
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'role' => 'instructor',
                'phone' => '09123456790',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'maria.garcia@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'role' => 'instructor',
                'phone' => '09123456791',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'email' => 'david.johnson@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'role' => 'instructor',
                'phone' => '09123456792',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Student Users
            [
                'first_name' => 'Alice',
                'last_name' => 'Brown',
                'email' => 'alice.brown@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456793',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'email' => 'bob.wilson@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456794',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Davis',
                'email' => 'sarah.davis@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456795',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Miller',
                'email' => 'michael.miller@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456796',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Taylor',
                'email' => 'emily.taylor@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456797',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Anderson',
                'email' => 'james.anderson@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456798',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Thomas',
                'email' => 'lisa.thomas@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456799',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Jackson',
                'email' => 'robert.jackson@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456800',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Jennifer',
                'last_name' => 'White',
                'email' => 'jennifer.white@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456801',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert users
        $this->db->table('users')->insertBatch($data);
    }
}
