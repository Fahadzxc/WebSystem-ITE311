<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Student extends Controller
{
    public function dashboard()
    {
        $data = [
            'title' => 'Student Dashboard',
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('student/dashboard', $data);
    }
} 