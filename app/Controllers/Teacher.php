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

        // Get only courses assigned to this teacher
        $courses = $this->courseModel->where('instructor_id', $teacher_id)
                                    ->where('status', 'published')
                                    ->orderBy('title', 'ASC')
                                    ->findAll();

        return view('auth/dashboard', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'courses' => $courses // Only show courses assigned to this teacher
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

        $teacher_id = session()->get('user_id');

        // Get only courses assigned to this teacher
        $courses = $this->courseModel->where('instructor_id', $teacher_id)
                                    ->where('status', 'published')
                                    ->findAll();

        // Get pending enrollments for all teacher's courses
        $pendingEnrollments = [];
        
        if (!empty($courses)) {
            $course_ids = array_column($courses, 'id');
            
            // Use direct database query to ensure we get all pending enrollments
            $db = \Config\Database::connect();
            $builder = $db->table('enrollments');
            $builder->select('enrollments.*, users.name as student_name, users.email as student_email, courses.title as course_title, courses.instructor_id');
            $builder->join('users', 'users.id = enrollments.user_id', 'left');
            $builder->join('courses', 'courses.id = enrollments.course_id', 'left');
            $builder->whereIn('enrollments.course_id', $course_ids);
            $builder->where('enrollments.status', 'pending');
            $builder->orderBy('enrollments.enrollment_date', 'DESC');
            $pendingEnrollments = $builder->get()->getResultArray();
            
            // Debug logging
            log_message('debug', 'Teacher enrollments - Teacher ID: ' . $teacher_id);
            log_message('debug', 'Teacher enrollments - Course IDs: ' . json_encode($course_ids));
            log_message('debug', 'Teacher enrollments - Pending count: ' . count($pendingEnrollments));
            if (!empty($pendingEnrollments)) {
                log_message('debug', 'Teacher enrollments - Pending data: ' . json_encode($pendingEnrollments));
            }
        } else {
            log_message('debug', 'Teacher enrollments - Teacher ID: ' . $teacher_id . ' has no courses assigned');
        }

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
            'studentsWithEnrollments' => $studentsWithEnrollments,
            'pendingEnrollments' => $pendingEnrollments
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
     * Unenroll a student from a course
     */
    public function unenrollStudent()
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

        $enrollment_id = $this->request->getPost('enrollment_id');
        $student_id = $this->request->getPost('student_id');
        $course_id = $this->request->getPost('course_id');

        if (!$enrollment_id || !$student_id || !$course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment ID, Student ID, and Course ID are required.'
            ])->setStatusCode(400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Verify enrollment belongs to the specified student and course
        if ($enrollment['user_id'] != $student_id || $enrollment['course_id'] != $course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment mismatch.'
            ])->setStatusCode(400);
        }

        // Get course
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Verify teacher owns this course
        $teacher_id = session()->get('user_id');
        if ($course['instructor_id'] != $teacher_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only unenroll students from your own courses.'
            ])->setStatusCode(403);
        }

        // Update enrollment status to 'dropped'
        $updated = $this->enrollmentModel->update($enrollment_id, [
            'status' => 'dropped'
        ]);

        if ($updated) {
            // Get student name for notification
            $student = $this->userModel->find($student_id);
            $studentName = $student ? $student['name'] : 'Student';

            // Create notification for student
            try {
                $message = "You have been unenrolled from '{$course['title']}' by your instructor.";
                $this->notificationModel->createNotification($student_id, $message);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create unenrollment notification: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student unenrolled successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll student. Please try again.'
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

    /**
     * Approve enrollment request
     */
    public function approveEnrollment()
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

        $enrollment_id = $this->request->getPost('enrollment_id');
        
        if (!$enrollment_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment ID is required.'
            ])->setStatusCode(400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Get course
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Verify teacher owns this course
        $teacher_id = session()->get('user_id');
        if ($course['instructor_id'] != $teacher_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only approve enrollments for your own courses.'
            ])->setStatusCode(403);
        }

        // Update enrollment status to active
        $updated = $this->enrollmentModel->update($enrollment_id, [
            'status' => 'active',
            'rejection_reason' => null
        ]);

        if ($updated) {
            // Create notification for student
            try {
                $studentMessage = "Your enrollment request for '{$course['title']}' has been approved! Welcome to the course.";
                $this->notificationModel->createNotification($enrollment['user_id'], $studentMessage);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create approval notification: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment approved successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to approve enrollment. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Reject enrollment request
     */
    public function rejectEnrollment()
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

        $enrollment_id = $this->request->getPost('enrollment_id');
        $rejection_reason = $this->request->getPost('rejection_reason');
        
        if (!$enrollment_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment ID is required.'
            ])->setStatusCode(400);
        }

        if (empty($rejection_reason)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Rejection reason is required.'
            ])->setStatusCode(400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Get course
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Verify teacher owns this course
        $teacher_id = session()->get('user_id');
        if ($course['instructor_id'] != $teacher_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You can only reject enrollments for your own courses.'
            ])->setStatusCode(403);
        }

        // Update enrollment status to rejected
        $updated = $this->enrollmentModel->update($enrollment_id, [
            'status' => 'rejected',
            'rejection_reason' => $rejection_reason
        ]);

        if ($updated) {
            // Create notification for student with rejection reason
            try {
                $studentMessage = "Your enrollment request for '{$course['title']}' has been rejected. Reason: {$rejection_reason}";
                $this->notificationModel->createNotification($enrollment['user_id'], $studentMessage);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create rejection notification: ' . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment rejected successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to reject enrollment. Please try again.'
            ])->setStatusCode(500);
        }
    }
}
