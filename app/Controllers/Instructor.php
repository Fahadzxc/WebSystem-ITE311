<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Instructor extends Controller
{
    public function dashboard()
    {
        $data = [
            'title' => 'Instructor Dashboard',
            'user' => [
                'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
                'role' => ucfirst(session()->get('role')),
                'email' => session()->get('email')
            ]
        ];
        return view('instructor/dashboard', $data);
    }
} 