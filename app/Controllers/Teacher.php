<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\NotificationModel;

class Teacher extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
    }

    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        $teacher_id = session()->get('user_id');

        // Get all published courses (teachers can manage all courses)
        $courses = $this->courseModel->where('status', 'published')->orderBy('title', 'ASC')->findAll();
        
        // Also get courses specifically assigned to this teacher
        $myCourses = $this->courseModel->getCoursesByInstructor($teacher_id);

        return view('auth/dashboard', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'courses' => $courses, // Show all courses
            'myCourses' => $myCourses // For reference
        ]);
    }

    /**
     * Display courses and students for enrollment management
     */
    public function enrollments()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        if (session()->get('role') !== 'teacher') {
            session()->setFlashdata('error', 'Access denied. Teacher role required.');
            return redirect()->to(base_url('login'));
        }

        // Get ALL courses (like students see) - teachers can enroll students in any course
        $courses = $this->courseModel->where('status', 'published')->findAll();

        // Get all students with their enrollments
        $students = $this->userModel->where('role', 'student')->orderBy('name', 'ASC')->findAll();
        
        // Get enrollments for each student
        $studentsWithEnrollments = [];
        foreach ($students as $student) {
            $enrollments = $this->enrollmentModel->getUserEnrollments($student['id']);
            $studentsWithEnrollments[] = [
                'id' => $student['id'],
                'name' => $student['name'],
                'email' => $student['email'],
                'enrollments' => $enrollments
            ];
        }

        return view('teacher/enrollments', [
            'courses' => $courses,
            'students' => $students,
            'studentsWithEnrollments' => $studentsWithEnrollments
        ]);
    }

    /**
     * Enroll a student in a course
     */
    public function enrollStudent()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login first.'
            ])->setStatusCode(401);
        }

        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Teacher role required.'
            ])->setStatusCode(403);
        }

        $student_id = $this->request->getPost('student_id');
        $course_id = $this->request->getPost('course_id');

        if (!$student_id || !$course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student ID and Course ID are required.'
            ])->setStatusCode(400);
        }

        // Verify course exists (teachers can enroll students in any course)
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Verify student exists and is a student
        $student = $this->userModel->find($student_id);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid student.'
            ])->setStatusCode(400);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($student_id, $course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student is already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // Enroll student
        $enrollmentData = [
            'user_id' => $student_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'active',
            'progress' => 0.00
        ];

        $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);

        if ($enrollmentId) {
            // Create notification for student
            try {
                $message = "You have been enrolled in '{$course['title']}' by your instructor. Welcome to the course!";
                $this->notificationModel->createNotification($student_id, $message);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create enrollment notification: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student enrolled successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll student. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Get enrolled students for a course
     */
    public function getCourseStudents($course_id)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login first.'
            ])->setStatusCode(401);
        }

        if (session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(403);
        }

        // Verify course exists (teachers can view students in any course)
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Get enrolled students
        $enrollments = $this->enrollmentModel->getCourseEnrollments($course_id);

        return $this->response->setJSON([
            'success' => true,
            'students' => $enrollments
        ]);
    }
}
