<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Admin extends BaseController
{ 
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Check if user is logged in and is admin
     */
    private function checkAdminAuth()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please Login first.');
            return redirect()->to(base_url('login'));
        }

        if (strtolower(session('role')) !== 'admin') {
            session()->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to(base_url('dashboard'));
        }

        return true;
    }

    public function dashboard()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        // Render unified wrapper with user context
        return view('auth/dashboard', [
            'user' => [
                'name'  => session('name'),
                'email' => session('email'),
                'role'  => session('role'),
            ]
        ]);
    }

    /**
     * User Management - List all users
     */
    public function users()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        $users = $this->userModel->getAllUsers();

        return view('admin/users', [
            'users' => $users,
            'user' => [
                'name'  => session('name'),
                'email' => session('email'),
                'role'  => session('role'),
            ]
        ]);
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($this->request->getMethod() === 'POST') {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $role = $this->request->getPost('role');

            // Validate name - only letters and spaces allowed
            if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
                session()->setFlashdata('error', 'Name contains invalid characters.');
                return redirect()->to(base_url('admin/users'));
            }

            // Validate email - must be valid email format and @gmail.com
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('admin/users'));
            }

            // Check if email contains invalid special characters
            if (preg_match('/[\/\'"\\\;\<\>]/', $email)) {
                session()->setFlashdata('error', 'Invalid email format.');
                return redirect()->to(base_url('admin/users'));
            }

            // Validate email must be @gmail.com
            if (strpos($email, '@gmail.com') === false) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('admin/users'));
            }

            // Check if email already exists
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                session()->setFlashdata('error', 'Email already exists.');
                return redirect()->to(base_url('admin/users'));
            }

            // Create user
            $data = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ];

            if ($this->userModel->insert($data)) {
                session()->setFlashdata('success', 'User created successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to create user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Update user
     */
    public function updateUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($this->request->getMethod() === 'POST' && $id) {
            // Don't allow editing yourself
            if ($id == session('user_id')) {
                session()->setFlashdata('error', 'You cannot edit your own account.');
                return redirect()->to(base_url('admin/users'));
            }

            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $role = $this->request->getPost('role');
            $password = $this->request->getPost('password');

            // Validate name - only letters and spaces allowed
            if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
                session()->setFlashdata('error', 'Name contains invalid characters.');
                return redirect()->to(base_url('admin/users'));
            }

            // Validate email - must be valid email format and @gmail.com
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('admin/users'));
            }

            // Check if email contains invalid special characters
            if (preg_match('/[\/\'"\\\;\<\>]/', $email)) {
                session()->setFlashdata('error', 'Invalid email format.');
                return redirect()->to(base_url('admin/users'));
            }

            // Validate email must be @gmail.com
            if (strpos($email, '@gmail.com') === false) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('admin/users'));
            }

            // Check if email already exists for other users
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                session()->setFlashdata('error', 'Email already exists for another user.');
                return redirect()->to(base_url('admin/users'));
            }

            // Prepare update data
            $data = [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];

            // Only update password if provided
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User updated successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to update user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Delete user (soft delete - mark as deleted)
     */
    public function deleteUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($id) {
            // Don't allow deleting yourself
            if ($id == session('user_id')) {
                session()->setFlashdata('error', 'You cannot delete your own account.');
                return redirect()->to(base_url('admin/users'));
            }

            // Soft delete - mark as deleted instead of actually deleting
            $data = [
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User marked as deleted successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to delete user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Restore deleted user
     */
    public function restoreUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($id) {
            $data = [
                'is_deleted' => 0,
                'deleted_at' => null
            ];

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User restored successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to restore user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Get user by ID (AJAX)
     */
    public function getUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if ($id) {
            $user = $this->userModel->find($id);
            if ($user) {
                // Don't send password
                unset($user['password']);
                return $this->response->setJSON(['success' => true, 'user' => $user]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
    }

    /**
     * Cleanup expired enrollments (4 months old)
     * Can be called manually or via cron job
     */
    public function cleanupExpiredEnrollments()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $enrollmentModel = new \App\Models\EnrollmentModel();
        $removedCount = $enrollmentModel->removeExpiredEnrollments();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Removed {$removedCount} expired enrollment(s).",
                'count' => $removedCount
            ]);
        }

        session()->setFlashdata('success', "Removed {$removedCount} expired enrollment(s).");
        return redirect()->to(base_url('admin/users'));
    }
}

