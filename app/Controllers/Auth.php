<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function index()
    {
        // If user is already logged in, redirect to dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login');
    }

    public function login()
    {
        $userModel = new UserModel();
        
        // Get form data
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');
        
        // Validation
        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Please enter both username and password');
            return redirect()->back()->withInput();
        }
        
        // Check if user exists
        $user = $userModel->where('username', $username)
                         ->orWhere('email', $username)
                         ->first();
        
        if (!$user) {
            session()->setFlashdata('error', 'Invalid username or email');
            return redirect()->back()->withInput();
        }
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Invalid password');
            return redirect()->back()->withInput();
        }
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            session()->setFlashdata('error', 'Your account is not active. Please contact administrator.');
            return redirect()->back()->withInput();
        }
        
        // Set session data
        $sessionData = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'role' => $user['role'],
            'isLoggedIn' => true
        ];
        
        session()->set($sessionData);
        
        // Set remember me cookie if checked
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $userModel->update($user['id'], ['remember_token' => $token]);
            
            // Use Response service to set cookie
            $response = service('response');
            $response->setCookie('remember_token', $token, [
                'expires' => time() + (60 * 60 * 24 * 30), // 30 days
                'secure' => false,
                'httponly' => true
            ]);
        }
        
        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'instructor':
                return redirect()->to('/instructor/dashboard');
            case 'student':
            default:
                return redirect()->to('/student/dashboard');
        }
    }

    public function logout()
    {
        // Clear session
        session()->destroy();
        
        // Clear remember me cookie using Response service
        $response = service('response');
        $response->deleteCookie('remember_token');
        
        session()->setFlashdata('success', 'You have been successfully logged out');
        return redirect()->to('/auth');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function resetPassword()
    {
        $email = $this->request->getPost('email');
        
        if (empty($email)) {
            session()->setFlashdata('error', 'Please enter your email address');
            return redirect()->back();
        }
        
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        
        if (!$user) {
            session()->setFlashdata('error', 'Email address not found');
            return redirect()->back();
        }
        
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $userModel->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        ]);
        
        // TODO: Send email with reset link
        // For now, just show success message
        session()->setFlashdata('success', 'Password reset instructions have been sent to your email');
        return redirect()->to('/login');
    }
} 