<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            // Store the intended URL for redirect after login
            session()->set('intended_url', current_url());
            
            // Redirect to login page
            return redirect()->to('/auth');
        }
        
        // Check role-based access if specified
        if (!empty($arguments)) {
            $userRole = session()->get('role');
            if (!in_array($userRole, $arguments)) {
                // User doesn't have required role
                session()->setFlashdata('error', 'Access denied. You do not have permission to access this page.');
                return redirect()->to('/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after the request
    }
} 