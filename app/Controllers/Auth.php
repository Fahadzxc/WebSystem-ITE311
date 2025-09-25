<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => 'student'
                ];
                
                // Save user to database
                if ($model->insert($data)) {
                    $session->setFlashdata('success', 'Registration successful. Please login.');
                    return redirect()->to('/login');
                } else {
                    // Get the last error for debugging
                    $errors = $model->errors();
                    $errorMessage = 'Registration failed. ';
                    if (!empty($errors)) {
                        $errorMessage .= implode(', ', $errors);
                    } else {
                        $errorMessage .= 'Please try again.';
                    }
                    $session->setFlashdata('error', $errorMessage);
                }
            }
        }
        
        echo view('auth/register', [
            'validation' => $this->validator
        ]);
    }

    public function login()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $model->where('email', $email)->first();
                if ($user && password_verify($password, $user['password'])) {
                    $session->set([
                        'user_id' => $user['id'],
                        'user_name' => $user['name'],
                        'user_email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true
                    ]);
                    $session->setFlashdata('success', 'Welcome, ' . $user['name'] . '!');
                    
                    // Unified dashboard redirection for all roles
                    return redirect()->to('/dashboard');
                } else {
                    $session->setFlashdata('error', 'Invalid login credentials.');
                }
            }
        }
        echo view('auth/login', [
            'validation' => $this->validator
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $session = session();
        
        // Authorization check - ensure user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Get user role and prepare role-specific data
        $role = $session->get('role');
        $userModel = new UserModel();
        
        // Prepare base data
        $data = [
            'title' => 'Dashboard - LMS System',
            'user_name' => $session->get('user_name'),
            'user_email' => $session->get('user_email'),
            'role' => $role,
            'page' => 'dashboard'
        ];
        
        // Fetch role-specific data
        switch ($role) {
            case 'admin':
                // Admin-specific data
                $data = array_merge($data, [
                    'total_students' => $userModel->where('role', 'student')->countAllResults(),
                    'total_teachers' => $userModel->where('role', 'instructor')->countAllResults(),
                    'total_users' => $userModel->countAllResults(),
                    'recent_users' => $userModel->orderBy('created_at', 'DESC')->limit(5)->find() ?? [],
                    'dashboard_type' => 'admin'
                ]);
                break;
                
            case 'instructor':
                // Teacher-specific data
                $data = array_merge($data, [
                    'my_courses' => [], // Placeholder - implement based on your course model
                    'total_students' => 0, // Placeholder - implement based on your enrollment model
                    'pending_assignments' => 0, // Placeholder
                    'dashboard_type' => 'instructor'
                ]);
                break;
                
            case 'student':
                // Student-specific data
                $data = array_merge($data, [
                    'enrolled_courses' => [], // Placeholder - implement based on your enrollment model
                    'pending_assignments' => 0, // Placeholder
                    'completed_tasks' => 0, // Placeholder
                    'overall_progress' => 0, // Placeholder
                    'dashboard_type' => 'student'
                ]);
                break;
                
            default:
                $data['dashboard_type'] = 'default';
        }
        
        return view('auth/dashboard', $data);
    }
}
