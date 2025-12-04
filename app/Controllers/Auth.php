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
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
            // Redirect to role-specific dashboard
            $role = session()->get('role');
            switch ($role) {
                case 'admin':
                    return redirect()->to('admin/dashboard');
                case 'teacher':
                    return redirect()->to('teacher/dashboard');
                case 'student':
                    return redirect()->to('student/dashboard');
                default:
                    return redirect()->to('/dashboard');
            }
        }
        
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
                
                // Check if user is deleted
                if ($user && isset($user['is_deleted']) && $user['is_deleted'] == 1) {
                    $session->setFlashdata('error', 'This account has been deleted. Please contact the administrator.');
                    return redirect()->to('/login');
                }
                
                if ($user && password_verify($password, $user['password'])) {
                    $session->set([
                        'user_id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true
                    ]);
                    $session->setFlashdata('success', 'Welcome, ' . $user['name'] . '!');
                    
                    // Debug: Log the role for debugging
                    log_message('debug', 'Auth Login - User Role: ' . $user['role'] . ', Redirecting...');
                    
                    // Redirect based on role
                    switch ($user['role']) {
                        case 'admin':
                            return redirect()->to('/admin/dashboard');
                        case 'teacher':
                            return redirect()->to('/teacher/dashboard');
                        case 'student':
                            return redirect()->to('/student/dashboard');
                        default:
                            // Unknown role: clear session and go back to login
                            session()->destroy();
                            session()->setFlashdata('error', 'Your account role is not recognized.');
                            return redirect()->to(base_url('login'));
                    }
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
        // Destroy the current session
        session()->destroy();
        
        // Set logout message and redirect
        session()->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to(base_url('login'));
    }

    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('login');
        }

        // Redirect to role-specific dashboard
        $role = session()->get('role');
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'teacher':
                return redirect()->to('/teacher/dashboard');
            case 'student':
                return redirect()->to('/student/dashboard');
            default:
                session()->setFlashdata('error', 'Your account role is not recognized.');
                return redirect()->to('login');
        }
    }
}
