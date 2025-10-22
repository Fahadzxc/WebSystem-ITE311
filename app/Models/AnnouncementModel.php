<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'title',
        'content',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // NO VALIDATION - Just save everything
    protected $skipValidation = true;
    protected $cleanValidationRules = false;

    // NO CALLBACKS - Let controller handle everything
    protected $beforeInsert = [];
    protected $beforeUpdate = [];

    /**
     * Get all announcements ordered by created_at descending (newest first)
     */
    public function getAllAnnouncements()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Find announcement by ID
     */
    public function findById(int $id)
    {
        return $this->find($id);
    }

    /**
     * Get recent announcements (last N days)
     */
    public function getRecentAnnouncements(int $days = 7)
    {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at >=', $date)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
}
