<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
<<<<<<< HEAD
            // Admin Users
            [
                'username' => 'admin',
                'email' => 'admin@lms.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'role' => 'admin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'superadmin',
                'email' => 'superadmin@lms.com',
                'password' => password_hash('super123', PASSWORD_DEFAULT),
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'role' => 'admin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Instructor Users
            [
                'username' => 'instructor1',
                'email' => 'john.doe@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'role' => 'instructor',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'instructor2',
                'email' => 'jane.smith@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'role' => 'instructor',
                'status' => 'active',
=======
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
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
<<<<<<< HEAD
                'username' => 'instructor3',
                'email' => 'mike.wilson@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'first_name' => 'Mike',
                'last_name' => 'Wilson',
                'role' => 'instructor',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Student Users
            [
                'username' => 'student1',
                'email' => 'alice.johnson@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'role' => 'student',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'student2',
                'email' => 'bob.brown@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Bob',
                'last_name' => 'Brown',
                'role' => 'student',
                'status' => 'active',
=======
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
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
<<<<<<< HEAD
                'username' => 'student3',
                'email' => 'carol.davis@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Carol',
                'last_name' => 'Davis',
                'role' => 'student',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'student4',
                'email' => 'david.miller@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'David',
                'last_name' => 'Miller',
                'role' => 'student',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'student5',
                'email' => 'emma.wilson@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Emma',
                'last_name' => 'Wilson',
                'role' => 'student',
                'status' => 'active',
=======
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'email' => 'bob.wilson@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456794',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
<<<<<<< HEAD
                'username' => 'student6',
                'email' => 'frank.garcia@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Frank',
                'last_name' => 'Garcia',
                'role' => 'student',
                'status' => 'active',
=======
                'first_name' => 'Sarah',
                'last_name' => 'Davis',
                'email' => 'sarah.davis@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456795',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
<<<<<<< HEAD
                'username' => 'student7',
                'email' => 'grace.lee@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Grace',
                'last_name' => 'Lee',
                'role' => 'student',
                'status' => 'active',
=======
                'first_name' => 'Michael',
                'last_name' => 'Miller',
                'email' => 'michael.miller@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student',
                'phone' => '09123456796',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
<<<<<<< HEAD
                'username' => 'student8',
                'email' => 'henry.taylor@student.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'first_name' => 'Henry',
                'last_name' => 'Taylor',
                'role' => 'student',
                'status' => 'active',
=======
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
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

<<<<<<< HEAD
        // Insert all users
        $this->db->table('users')->insertBatch($data);
        
        echo "Sample users created successfully!\n";
=======
        // Insert users
        $this->db->table('users')->insertBatch($data);
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
    }
}
