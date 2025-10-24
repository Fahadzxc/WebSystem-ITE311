<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'message',
        'is_read',
        'created_at'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;

    // NO VALIDATION - Just save everything
    protected $skipValidation = true;
    protected $cleanValidationRules = false;

    // NO CALLBACKS - Let controller handle everything
    protected $beforeInsert = [];
    protected $beforeUpdate = [];

    /**
     * Fetches the count of unread notifications for a user
     */
    public function getUnreadCount($userId)
    {
        return $this->where('user_id', $userId)
                   ->where('is_read', 0)
                   ->countAllResults();
    }

    /**
     * Fetches the latest notifications (limit 5) for a user
     */
    public function getNotificationsForUser($userId)
    {
        return $this->where('user_id', $userId)
                   ->orderBy('created_at', 'DESC')
                   ->limit(5)
                   ->findAll();
    }

    /**
     * Updates a specific notification's is_read field to 1
     */
    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, ['is_read' => 1]);
    }

    /**
     * Create new notification (bonus method)
     */
    public function createNotification($userId, $message)
    {
        return $this->insert([
            'user_id' => $userId,
            'message' => $message,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
