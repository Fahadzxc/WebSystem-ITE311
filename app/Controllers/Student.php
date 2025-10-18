<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Student extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
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
            'overall_progress' => $overall_progress
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
            session()->setFlashdata('success', 'Successfully enrolled in ' . $course['title'] . '!');
            return redirect()->to('student/dashboard');
        } else {
            session()->setFlashdata('error', 'Failed to enroll in course. Please try again.');
            return redirect()->to('student/dashboard');
        }
    }
}