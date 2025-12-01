<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\MaterialModel;
use App\Models\NotificationModel;

class Student extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $materialModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->materialModel = new MaterialModel();
        $this->notificationModel = new NotificationModel();
    }

    public function dashboard()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        $user_id = session()->get('user_id');

        // Get user's enrollments
        $enrollments = $this->enrollmentModel->getUserEnrollments($user_id);

        // Get available courses (courses not enrolled in)
        $enrolled_course_ids = array_column($enrollments, 'course_id');
        $available_courses = [];
        
        if (!empty($enrolled_course_ids)) {
            $available_courses = $this->courseModel->whereNotIn('id', $enrolled_course_ids)->findAll();
        } else {
            $available_courses = $this->courseModel->findAll();
        }

        // Calculate overall progress
        $overall_progress = 0;
        if (!empty($enrollments)) {
            $total_progress = array_sum(array_column($enrollments, 'progress'));
            $overall_progress = $total_progress / count($enrollments);
        }

        return view('auth/dashboard', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'enrollments' => $enrollments,
            'available_courses' => $available_courses,
            'overall_progress' => $overall_progress,
            'unread_count' => $this->data['unread_count'] ?? 0
        ]);
    }

    public function enroll()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to enroll in courses.');
            return redirect()->to('login');
        }

        // Check if user is a student
        if (session()->get('role') !== 'student') {
            session()->setFlashdata('error', 'Access denied. Student role required.');
            return redirect()->to('login');
        }

        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        if (!$course_id) {
            session()->setFlashdata('error', 'Course ID is required.');
            return redirect()->to('student/dashboard');
        }

        // Get user ID from session only (never trust client data)
        $user_id = session()->get('user_id');
        
        if (!$user_id) {
            session()->setFlashdata('error', 'User session not found.');
            return redirect()->to('login');
        }

        // Validate course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->to('student/dashboard');
        }

        // Check if user is already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            session()->setFlashdata('error', 'You are already enrolled in this course.');
            return redirect()->to('student/dashboard');
        }

        // Prepare enrollment data
        $enrollmentData = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'active',
            'progress' => 0.00
        ];

        // Insert new enrollment record
        $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);

        if ($enrollmentId) {
            // Create notification for successful enrollment
            try {
                $enrollmentMessage = "You have been successfully enrolled in '{$course['title']}'. Welcome to the course!";
                $this->notificationModel->createNotification($user_id, $enrollmentMessage);
                
                // Also create a welcome notification with course details
                $welcomeMessage = "Welcome to {$course['title']}! You can now access course materials, assignments, and participate in discussions.";
                $this->notificationModel->createNotification($user_id, $welcomeMessage);
                
                // Create a getting started notification
                $gettingStartedMessage = "Getting started with {$course['title']}: Check out the course materials and don't forget to introduce yourself to your classmates!";
            } catch (\Exception $e) {
                // Log error but don't fail the enrollment
                log_message('error', 'Failed to create enrollment notification: ' . $e->getMessage());
            }
            
            session()->setFlashdata('success', 'Successfully enrolled in ' . $course['title'] . '!');
            return redirect()->to('student/dashboard');
        } else {
            session()->setFlashdata('error', 'Failed to enroll in course. Please try again.');
            return redirect()->to('student/dashboard');
        }
    }

    /**
     * Display materials for enrolled courses
     */
    public function materials()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        // Check if user is a student
        if (session('role') !== 'student') {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('login'));
        }

        $user_id = session()->get('user_id');

        // Get user's enrollments
        $enrollments = $this->enrollmentModel->getUserEnrollments($user_id);

        if (empty($enrollments)) {
            return view('student/materials', [
                'user' => [
                    'name' => session('name'),
                    'email' => session('email'),
                    'role' => session('role'),
                ],
                'enrollments' => [],
                'materials_by_course' => []
            ]);
        }

        // Get course IDs from enrollments
        $course_ids = array_column($enrollments, 'course_id');

        // Get all materials for enrolled courses
        $materials_by_course = [];
        foreach ($enrollments as $enrollment) {
            $course_id = $enrollment['course_id'];
            $materials = $this->materialModel->getMaterialsByCourse($course_id);
            
            if (!empty($materials)) {
                $materials_by_course[] = [
                    'course_id' => $course_id,
                    'course_title' => $enrollment['course_title'],
                    'course_description' => $enrollment['course_description'],
                    'materials' => $materials
                ];
            }
        }

        return view('student/materials', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'enrollments' => $enrollments,
            'materials_by_course' => $materials_by_course
        ]);
    }

    /**
     * Display materials for a specific course
     */
    public function courseMaterials($course_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        // Check if user is a student
        if (session('role') !== 'student') {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('login'));
        }

        $user_id = session()->get('user_id');

        // Check if user is enrolled in this course
        if (!$this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            session()->setFlashdata('error', 'You are not enrolled in this course.');
            return redirect()->to(base_url('student/materials'));
        }

        // Get course information with instructor name
        $course = $this->courseModel->getCourseWithInstructor($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->to(base_url('student/materials'));
        }

        // Get materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        return view('student/course_materials', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'course' => $course,
            'materials' => $materials
        ]);
    }

    /**
     * Create additional test notifications for demonstration
     * Access via: /student/create-test-notifications
     */
    public function createTestNotifications()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to('login');
        }
        
        $user_id = session()->get('user_id');
        
        try {
            // Create various test notifications
            $testNotifications = [
                " New assignment has been posted in your enrolled course. Check it out!",
                " Your quiz submission has been graded. View your results now.",
                " Course materials have been updated. New resources are available.",
                " Reminder: Assignment deadline is approaching in 2 days.",
                " Welcome to the Learning Management System! Explore your dashboard.",
                " You have a new message from your instructor.",
                " Congratulations! You've completed 50% of the course."
            ];
            
            foreach ($testNotifications as $message) {
                $this->notificationModel->createNotification($user_id, $message);
            }
            
            session()->setFlashdata('success', 'Test notifications created successfully! Check your notification dropdown.');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to create test notifications: ' . $e->getMessage());
        }
        
        return redirect()->to('student/dashboard');
    }
}