<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $role = session()->get('role');
        
        // Redirect to role-specific dashboard
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'instructor':
                return redirect()->to('/instructor/dashboard');
            case 'student':
            default:
                return redirect()->to('/student/dashboard');
        }
    }
} 