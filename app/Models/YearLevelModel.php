<?php

namespace App\Models;

use CodeIgniter\Model;

class YearLevelModel extends Model
{
    protected $table = 'year_levels';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'level_name',
        'level_number',
        'description'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all year levels ordered by level_number
     */
    public function getAllYearLevels()
    {
        return $this->orderBy('level_number', 'ASC')->findAll();
    }
}

