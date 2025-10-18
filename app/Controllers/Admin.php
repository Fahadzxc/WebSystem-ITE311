<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Admin extends BaseController
{ 
    public function dashboard()
    {
        // Must be logged in
        if  (!session () ->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please Login first.');
            return redirect()->to(base_url('login'));
        }

        // Must be admin
        if (session('role') !== 'admin') {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('login'));
        }

        // Render admin dashboard with user context
        return view('admin', [
            'user' => [
                'name'  => session('name'),
                'email' => session('email'),
                'role'  => session('role'),
            ]
        ]);
        }
}

