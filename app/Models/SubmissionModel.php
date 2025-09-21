<?php

namespace App\Models;

use CodeIgniter\Model;

class SubmissionModel extends Model
{
    protected $table = 'submissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'assignment_id',
        'course_id',
        'submission_text',
        'submission_file',
        'submitted_at',
        'grade',
        'feedback',
        'status',
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
        'assignment_id' => 'required|integer',
        'course_id' => 'required|integer',
        'submission_text' => 'permit_empty|max_length[5000]',
        'submission_file' => 'permit_empty|max_length[255]',
        'submitted_at' => 'required|valid_date',
        'grade' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
        'feedback' => 'permit_empty|max_length[2000]',
        'status' => 'required|in_list[submitted,graded,returned,late]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'Invalid user ID'
        ],
        'assignment_id' => [
            'required' => 'Assignment ID is required',
            'integer' => 'Invalid assignment ID'
        ],
        'course_id' => [
            'required' => 'Course ID is required',
            'integer' => 'Invalid course ID'
        ],
        'submission_text' => [
            'max_length' => 'Submission text cannot exceed 5000 characters'
        ],
        'submission_file' => [
            'max_length' => 'File path cannot exceed 255 characters'
        ],
        'submitted_at' => [
            'required' => 'Submission date is required',
            'valid_date' => 'Invalid submission date'
        ],
        'grade' => [
            'decimal' => 'Grade must be a valid decimal number',
            'greater_than_equal_to' => 'Grade cannot be negative',
            'less_than_equal_to' => 'Grade cannot exceed 100'
        ],
        'feedback' => [
            'max_length' => 'Feedback cannot exceed 2000 characters'
        ],
        'status' => [
            'required' => 'Submission status is required',
            'in_list' => 'Invalid submission status'
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
