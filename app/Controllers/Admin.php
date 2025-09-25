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
     * Admin Dashboard - Redirect to unified dashboard
     */
    public function dashboard()
    {
        return redirect()->to('/dashboard');
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
