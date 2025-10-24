<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class Notifications extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Display notifications page
     */
    public function index()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to('login');
        }

        $user_id = session()->get('user_id');
        
        try {
            // Get all notifications for the user (not just 5)
            $notifications = $this->notificationModel->where('user_id', $user_id)
                                                   ->orderBy('created_at', 'DESC')
                                                   ->findAll();
            
            return view('notifications_page', [
                'user' => [
                    'name' => session('name'),
                    'email' => session('email'),
                    'role' => session('role'),
                ],
                'notifications' => $notifications
            ]);
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error loading notifications: ' . $e->getMessage());
            return redirect()->to('dashboard');
        }
    }

    /**
     * Get notifications for current user (AJAX endpoint)
     * Returns JSON response with unread count and notifications list
     */
    public function get()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login first'
            ])->setStatusCode(401);
        }

        $user_id = session()->get('user_id');
        
        try {
            // Get unread count and notifications
            $unread_count = $this->notificationModel->getUnreadCount($user_id);
            $notifications = $this->notificationModel->getNotificationsForUser($user_id);
            
            return $this->response->setJSON([
                'success' => true,
                'unread_count' => $unread_count,
                'notifications' => $notifications
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error fetching notifications: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Mark notification as read (AJAX endpoint)
     * Accepts notification ID via POST
     */
    public function mark_as_read($id)
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login first'
            ])->setStatusCode(401);
        }

        $user_id = session()->get('user_id');
        
        try {
            // Verify notification belongs to current user
            $notification = $this->notificationModel->find($id);
            
            if (!$notification) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Notification not found'
                ])->setStatusCode(404);
            }
            
            if ($notification['user_id'] != $user_id) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ])->setStatusCode(403);
            }
            
            // Mark as read
            $result = $this->notificationModel->markAsRead($id);
            
            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notification marked as read'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update notification'
                ])->setStatusCode(500);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating notification: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
