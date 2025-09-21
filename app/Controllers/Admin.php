<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CourseModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $courseModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
    }
    
    /**
     * Admin Dashboard
     * Performs authorization check and prepares admin-specific data
     */
    public function dashboard()
    {
        $session = session();
        
        // Authorization check - ensure user is logged in and has admin role
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin privileges required.');
            return redirect()->to('/login');
        }
        
        // Prepare data needed for admin dashboard
        $data = [
            'title' => 'Admin Dashboard - LMS System',
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => $session->get('role'),
            'page' => 'admin_dashboard',
            
            // Admin-specific data
            'total_students' => $this->userModel->where('role', 'student')->countAllResults(),
            'total_teachers' => $this->userModel->where('role', 'instructor')->countAllResults(),
            'total_courses' => $this->courseModel->countAllResults(),
            'total_enrollments' => $this->getTotalEnrollments(),
            
            // Recent activities
            'recent_users' => $this->userModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            'recent_courses' => $this->courseModel->orderBy('created_at', 'DESC')->limit(5)->find(),
            
            // System statistics
            'active_users' => $this->userModel->where('status', 'active')->countAllResults(),
            'inactive_users' => $this->userModel->where('status', 'inactive')->countAllResults(),
        ];
        
        return view('admin/dashboard', $data);
    }
    
    /**
     * Get total enrollments (placeholder - implement based on your enrollment model)
     */
    private function getTotalEnrollments()
    {
        // This is a placeholder - implement based on your enrollment model
        // For now, return a sample number
        return 320;
    }
    
    /**
     * User Management
     */
    public function users()
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin privileges required.');
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'User Management - Admin',
            'users' => $this->userModel->findAll(),
            'page' => 'admin_users'
        ];
        
        return view('admin/users', $data);
    }
    
    /**
     * Course Management
     */
    public function courses()
    {
        $session = session();
        
        // Authorization check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Access denied. Admin privileges required.');
            return redirect()->to('/login');
        }
        
        $data = [
            'title' => 'Course Management - Admin',
            'courses' => $this->courseModel->findAll(),
            'page' => 'admin_courses'
        ];
        
        return view('admin/courses', $data);
    }
}
