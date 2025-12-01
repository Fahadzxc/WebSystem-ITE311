<?php

namespace App\Models;

use CodeIgniter\Model;

class AcademicYearModel extends Model
{
    protected $table = 'academic_years';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'year_start',
        'year_end',
        'description',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get active academic year
     */
    public function getActiveAcademicYear()
    {
        return $this->where('is_active', 1)->first();
    }

    /**
     * Get all academic years ordered by year_start DESC
     */
    public function getAllAcademicYears()
    {
        return $this->orderBy('year_start', 'DESC')->findAll();
    }
}

