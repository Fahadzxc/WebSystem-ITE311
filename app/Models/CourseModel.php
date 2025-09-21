<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title',
        'description',
        'instructor_id',
        'category',
        'duration',
        'price',
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
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'instructor_id' => 'required|integer',
        'category' => 'required|max_length[100]',
        'duration' => 'required|integer',
        'price' => 'required|decimal',
        'status' => 'required|in_list[active,inactive,archived]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Course title is required',
            'min_length' => 'Course title must be at least 3 characters long',
            'max_length' => 'Course title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Course description is required',
            'min_length' => 'Course description must be at least 10 characters long'
        ],
        'instructor_id' => [
            'required' => 'Instructor is required',
            'integer' => 'Invalid instructor ID'
        ],
        'category' => [
            'required' => 'Course category is required',
            'max_length' => 'Category cannot exceed 100 characters'
        ],
        'duration' => [
            'required' => 'Course duration is required',
            'integer' => 'Duration must be a number'
        ],
        'price' => [
            'required' => 'Course price is required',
            'decimal' => 'Price must be a valid decimal number'
        ],
        'status' => [
            'required' => 'Course status is required',
            'in_list' => 'Invalid course status'
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
