<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }

        $userRole = session()->get('role');
        $currentPath = $request->getUri()->getPath();

        // Debug: Log the current path and role
        log_message('debug', 'RoleAuth Filter - User Role: ' . $userRole . ', Current Path: ' . $currentPath);

        // Admin can access any route starting with /admin
        if ($userRole === 'admin') {
            if (strpos($currentPath, '/admin') === 0) {
                return; // Allow access
            }
        }

        // Teacher can only access routes starting with /teacher
        if ($userRole === 'teacher') {
            if (strpos($currentPath, '/teacher') === 0) {
                return; // Allow access
            }
        }

        // Student can access routes starting with /student
        if ($userRole === 'student') {
            if (strpos($currentPath, '/student') === 0) {
                return; // Allow access
            }
        }

        // If user tries to access unauthorized route, redirect with error
        log_message('debug', 'RoleAuth Filter - Access denied for role: ' . $userRole . ' to path: ' . $currentPath);
        session()->setFlashdata('error', 'Access Denied: Insufficient Permissions');
        return redirect()->to(base_url('announcements'));
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
