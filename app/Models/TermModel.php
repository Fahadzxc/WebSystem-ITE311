<?php

namespace App\Models;

use CodeIgniter\Model;

class TermModel extends Model
{
    protected $table = 'terms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'term_name',
        'term_number',
        'is_summer',
        'description'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all terms ordered by term_number
     */
    public function getAllTerms()
    {
        return $this->orderBy('term_number', 'ASC')->findAll();
    }

    /**
     * Get regular terms only (excluding summer)
     */
    public function getRegularTerms()
    {
        return $this->where('is_summer', 0)->orderBy('term_number', 'ASC')->findAll();
    }

    /**
     * Get summer term
     */
    public function getSummerTerm()
    {
        return $this->where('is_summer', 1)->first();
    }
}

