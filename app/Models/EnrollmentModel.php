<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrolled_at',
        'status',
        'progress',
        'completed_at',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'course_id' => 'required|integer',
        'enrolled_at' => 'required|valid_date',
        'status' => 'required|in_list[active,inactive,completed,dropped]',
        'progress' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'Invalid user ID'
        ],
        'course_id' => [
            'required' => 'Course ID is required',
            'integer' => 'Invalid course ID'
        ],
        'enrolled_at' => [
            'required' => 'Enrollment date is required',
            'valid_date' => 'Invalid enrollment date'
        ],
        'status' => [
            'required' => 'Enrollment status is required',
            'in_list' => 'Invalid enrollment status'
        ],
        'progress' => [
            'integer' => 'Progress must be a number',
            'greater_than_equal_to' => 'Progress cannot be negative',
            'less_than_equal_to' => 'Progress cannot exceed 100%'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}
