<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\SubmissionModel;

class Student extends BaseController
{
    protected $userModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $submissionModel;
    protected $studentId;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->submissionModel = new SubmissionModel();
        
        // Get student ID from session
        $session = session();
        $this->studentId = $session->get('user_id');
    }
    
    /**
     * Student Dashboard
     * Performs authorization check and prepares student-specific data
     */
    public function dashboard()
    {
        $session = session();
        
        // Authorization check - ensure user is logged in and has student role
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            $session->setFlashdata('error', 'Access denied. Student privileges required.');
            return redirect()->to('/login');
        }
        
        // Prepare data needed for student dashboard
        $data = [
            'title' => 'Student Dashboard - LMS System',
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => $session->get('role'),
            'page' => 'student_dashboard',
            
            // Student-specific data
            'enrolled_courses' => $this->getEnrolledCourses(),
            'total_courses' => $this->getTotalEnrolledCourses(),
            'pending_assignments' => $this->getPendingAssignments(),
            'completed_tasks' => $this->getCompletedTasks(),
            'overall_progress' => $this->getOverallProgress(),
            
            // Recent activities
            'recent_assignments' => $this->getRecentAssignments(),
            'recent_submissions' => $this->getRecentSubmissions(),
            
            // Course progress
            'course_progress' => $this->getCourseProgress(),
        ];
        
        return view('student/dashboard', $data);
    }
    
    /**
     * Get courses enrolled by this student
     */
    private function getEnrolledCourses()
    {
        return $this->enrollmentModel
            ->select('enrollments.*, courses.title, courses.description, courses.instructor_id, users.first_name, users.last_name')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->join('users', 'users.id = courses.instructor_id')
            ->where('enrollments.user_id', $this->studentId)
            ->findAll();
    }
    
    /**
     * Get total number of enrolled courses
     */
    private function getTotalEnrolledCourses()
    {
        return $this->enrollmentModel->where('user_id', $this->studentId)->countAllResults();
    }
    
    /**
     * Get pending assignments (placeholder)
     */
    private function getPendingAssignments()
    {
        // This is a placeholder - implement based on your assignment model
        return 12;
    }
    
    /**
     * Get completed tasks (placeholder)
     */
    private function getCompletedTasks()
    {
        // This is a placeholder - implement based on your task/submission model
        return 8;
    }
    
    /**
     * Get overall progress percentage
     */
    private function getOverallProgress()
    {
        $courses = $this->getEnrolledCourses();
        if (empty($courses)) {
            return 0;
        }
        
        $totalProgress = 0;
        foreach ($courses as $course) {
            // This is a placeholder - implement based on your progress tracking
            $totalProgress += rand(30, 100); // Random progress for demo
        }
        
        return round($totalProgress / count($courses));
    }
    
    /**
     * Get recent assignments (placeholder)
     */
    private function getRecentAssignments()
    {
        // This is a placeholder - implement based on your assignment model
        return [
            [
                'title' => 'HTML/CSS Project',
                'due_date' => 'Tomorrow',
                'status' => 'Pending'
            ],
            [
                'title' => 'Database Quiz',
                'due_date' => '3 days',
                'status' => 'In Progress'
            ],
            [
                'title' => 'PHP Assignment',
                'due_date' => '1 week',
                'status' => 'Completed'
            ]
        ];
    }
    
    /**
     * Get recent submissions
     */
    private function getRecentSubmissions()
    {
        return $this->submissionModel
            ->where('user_id', $this->studentId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();
    }
    
    /**
     * Get course progress for each enrolled course
     */
    private function getCourseProgress()
    {
        $courses = $this->getEnrolledCourses();
        $progress = [];
        
        foreach ($courses as $course) {
            // This is a placeholder - implement based on your progress tracking
            $progress[] = [
                'course_id' => $course['course_id'],
                'course_title' => $course['title'],
                'progress_percentage' => rand(30, 100), // Random progress for demo
                'instructor_name' => $course['first_name'] . ' ' . $course['last_name']
            ];
        }
        
        return $progress;
    }
    
    /**
     * My Courses
     */
    public function myCourses()
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            $session->setFlashdata('error', 'Access denied. Student privileges required.');
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'My Courses - Student',
            'courses' => $this->getEnrolledCourses(),
            'page' => 'student_courses'
        ];
        
        return view('student/courses', $data);
    }
    
    /**
     * Available Courses to Enroll
     */
    public function availableCourses()
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            $session->setFlashdata('error', 'Access denied. Student privileges required.');
            return redirect()->to('/login');
        }
        
        // Get courses not yet enrolled by this student
        $enrolledCourseIds = $this->enrollmentModel
            ->where('user_id', $this->studentId)
            ->findColumn('course_id');
        
        $availableCourses = $this->courseModel
            ->select('courses.*, users.first_name, users.last_name')
            ->join('users', 'users.id = courses.instructor_id')
            ->whereNotIn('courses.id', $enrolledCourseIds ?? [])
            ->findAll();
        
        $data = [
            'title' => 'Available Courses - Student',
            'courses' => $availableCourses,
            'page' => 'student_available_courses'
        ];
        
        return view('student/available_courses', $data);
    }
    
    /**
     * Enroll in a course
     */
    public function enroll($courseId)
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            $session->setFlashdata('error', 'Access denied. Student privileges required.');
            return redirect()->to('/login');
        }
        
        // Check if already enrolled
        $existingEnrollment = $this->enrollmentModel
            ->where('user_id', $this->studentId)
            ->where('course_id', $courseId)
            ->first();
        
        if ($existingEnrollment) {
            $session->setFlashdata('error', 'You are already enrolled in this course.');
            return redirect()->to('/student/available-courses');
        }
        
        // Enroll in course
        $enrollmentData = [
            'user_id' => $this->studentId,
            'course_id' => $courseId,
            'enrolled_at' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];
        
        if ($this->enrollmentModel->insert($enrollmentData)) {
            $session->setFlashdata('success', 'Successfully enrolled in the course!');
        } else {
            $session->setFlashdata('error', 'Failed to enroll in the course. Please try again.');
        }
        
        return redirect()->to('/student/available-courses');
    }
}
