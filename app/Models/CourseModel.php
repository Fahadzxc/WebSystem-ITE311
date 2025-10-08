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

    protected $allowedFields = [
        'title',
        'description',
        'instructor_id',
        'category',
        'level',
        'duration',
        'price',
        'thumbnail',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'instructor_id' => 'required|integer',
        'start_date' => 'required|valid_date',
        'end_date' => 'required|valid_date',
        'status' => 'in_list[active,inactive,completed]',
        'max_students' => 'integer|greater_than[0]'
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
            'integer' => 'Instructor ID must be an integer'
        ],
        'start_date' => [
            'required' => 'Start date is required',
            'valid_date' => 'Start date must be a valid date'
        ],
        'end_date' => [
            'required' => 'End date is required',
            'valid_date' => 'End date must be a valid date'
        ],
        'status' => [
            'in_list' => 'Status must be one of: active, inactive, completed'
        ],
        'max_students' => [
            'integer' => 'Max students must be an integer',
            'greater_than' => 'Max students must be greater than 0'
        ]
    ];

    /**
     * Get courses with instructor information
     */
    public function getCoursesWithInstructor()
    {
        return $this->select('courses.*, users.name as instructor_name')
                    ->join('users', 'users.id = courses.instructor_id')
                    ->where('courses.status', 'active')
                    ->findAll();
    }

    /**
     * Get course by ID with instructor information
     */
    public function getCourseWithInstructor($id)
    {
        return $this->select('courses.*, users.name as instructor_name')
                    ->join('users', 'users.id = courses.instructor_id')
                    ->where('courses.id', $id)
                    ->first();
    }

    /**
     * Get courses by instructor
     */
    public function getCoursesByInstructor($instructor_id)
    {
        return $this->where('instructor_id', $instructor_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
