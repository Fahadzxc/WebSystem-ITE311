<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class Teacher extends BaseController
{
    protected $userModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $teacherId;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        
        // Get teacher ID from session
        $session = session();
        $this->teacherId = $session->get('user_id');
    }
    
    /**
     * Teacher Dashboard
     * Performs authorization check and prepares teacher-specific data
     */
    public function dashboard()
    {
        $session = session();
        
        // Authorization check - ensure user is logged in and has instructor role
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'instructor') {
            $session->setFlashdata('error', 'Access denied. Teacher privileges required.');
            return redirect()->to('/login');
        }
        
        // Prepare data needed for teacher dashboard
        $data = [
            'title' => 'Teacher Dashboard - LMS System',
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => $session->get('role'),
            'page' => 'teacher_dashboard',
            
            // Teacher-specific data
            'my_courses' => $this->getMyCourses(),
            'total_students' => $this->getTotalStudents(),
            'pending_assignments' => $this->getPendingAssignments(),
            'average_rating' => $this->getAverageRating(),
            
            // Recent activities
            'recent_enrollments' => $this->getRecentEnrollments(),
            'recent_submissions' => $this->getRecentSubmissions(),
            
            // Course statistics
            'course_stats' => $this->getCourseStatistics(),
        ];
        
        return view('teacher/dashboard', $data);
    }
    
    /**
     * Get courses taught by this teacher
     */
    private function getMyCourses()
    {
        return $this->courseModel->where('instructor_id', $this->teacherId)->findAll();
    }
    
    /**
     * Get total students across all teacher's courses
     */
    private function getTotalStudents()
    {
        $courses = $this->getMyCourses();
        $totalStudents = 0;
        
        foreach ($courses as $course) {
            $totalStudents += $this->enrollmentModel->where('course_id', $course['id'])->countAllResults();
        }
        
        return $totalStudents;
    }
    
    /**
     * Get pending assignments (placeholder)
     */
    private function getPendingAssignments()
    {
        // This is a placeholder - implement based on your assignment model
        return 15;
    }
    
    /**
     * Get average rating (placeholder)
     */
    private function getAverageRating()
    {
        // This is a placeholder - implement based on your rating system
        return 4.8;
    }
    
    /**
     * Get recent enrollments in teacher's courses
     */
    private function getRecentEnrollments()
    {
        $courses = $this->getMyCourses();
        $courseIds = array_column($courses, 'id');
        
        if (empty($courseIds)) {
            return [];
        }
        
        return $this->enrollmentModel
            ->whereIn('course_id', $courseIds)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();
    }
    
    /**
     * Get recent submissions (placeholder)
     */
    private function getRecentSubmissions()
    {
        // This is a placeholder - implement based on your submission model
        return [];
    }
    
    /**
     * Get course statistics
     */
    private function getCourseStatistics()
    {
        $courses = $this->getMyCourses();
        $stats = [];
        
        foreach ($courses as $course) {
            $enrollmentCount = $this->enrollmentModel->where('course_id', $course['id'])->countAllResults();
            $stats[] = [
                'course_name' => $course['title'],
                'enrollment_count' => $enrollmentCount,
                'course_id' => $course['id']
            ];
        }
        
        return $stats;
    }
    
    /**
     * My Courses Management
     */
    public function myCourses()
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'instructor') {
            $session->setFlashdata('error', 'Access denied. Teacher privileges required.');
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'My Courses - Teacher',
            'courses' => $this->getMyCourses(),
            'page' => 'teacher_courses'
        ];
        
        return view('teacher/courses', $data);
    }
    
    /**
     * Students in my courses
     */
    public function myStudents()
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'instructor') {
            $session->setFlashdata('error', 'Access denied. Teacher privileges required.');
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'My Students - Teacher',
            'students' => $this->getMyStudents(),
            'page' => 'teacher_students'
        ];
        
        return view('teacher/students', $data);
    }
    
    /**
     * Get students enrolled in teacher's courses
     */
    private function getMyStudents()
    {
        $courses = $this->getMyCourses();
        $courseIds = array_column($courses, 'id');
        
        if (empty($courseIds)) {
            return [];
        }
        
        return $this->enrollmentModel
            ->select('enrollments.*, users.first_name, users.last_name, users.email, courses.title as course_title')
            ->join('users', 'users.id = enrollments.user_id')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->whereIn('enrollments.course_id', $courseIds)
            ->findAll();
    }
}
