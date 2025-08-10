<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends Controller
{
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'currentPage' => 'dashboard',
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/dashboard', $data);
    }
    
    public function users()
    {
        $data = [
            'title' => 'User Management',
            'currentPage' => 'users',
            'breadcrumb' => [
                ['text' => 'User Management', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/users/index', $data);
    }
    
    public function courses()
    {
        $data = [
            'title' => 'Course Management',
            'currentPage' => 'courses',
            'breadcrumb' => [
                ['text' => 'Course Management', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/courses/index', $data);
    }
    
    public function instructors()
    {
        $data = [
            'title' => 'Instructor Management',
            'currentPage' => 'instructors',
            'breadcrumb' => [
                ['text' => 'Instructor Management', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/instructors/index', $data);
    }
    
    public function students()
    {
        $data = [
            'title' => 'Student Management',
            'currentPage' => 'students',
            'breadcrumb' => [
                ['text' => 'Student Management', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/students/index', $data);
    }
    
    public function enrollments()
    {
        $data = [
            'title' => 'Enrollment Management',
            'currentPage' => 'enrollments',
            'breadcrumb' => [
                ['text' => 'Enrollment Management', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/enrollments/index', $data);
    }
    
    public function reports()
    {
        $data = [
            'title' => 'Reports & Analytics',
            'currentPage' => 'reports',
            'breadcrumb' => [
                ['text' => 'Reports & Analytics', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/reports/index', $data);
    }
    
    public function settings()
    {
        $data = [
            'title' => 'System Settings',
            'currentPage' => 'settings',
            'breadcrumb' => [
                ['text' => 'System Settings', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/settings/index', $data);
    }
    
    public function profile()
    {
        $data = [
            'title' => 'My Profile',
            'currentPage' => 'profile',
            'breadcrumb' => [
                ['text' => 'My Profile', 'url' => '#', 'active' => true]
            ],
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('admin/profile/index', $data);
    }
} 