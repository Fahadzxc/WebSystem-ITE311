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
        'status',
        'day_of_week',
        'start_time',
        'end_time',
        'semester',
        'academic_year'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'instructor_id' => 'permit_empty|integer',
        'status' => 'in_list[draft,published,archived]',
        'level' => 'in_list[beginner,intermediate,advanced]'
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
        // Get course first
        $course = $this->find($id);
        if (!$course) {
            return null;
        }
        
        // Get teacher name from users table where role = 'teacher'
        $userModel = new \App\Models\UserModel();
        
        // First, try to get teacher from instructor_id if it points to a teacher
        if (!empty($course['instructor_id'])) {
            $instructor = $userModel->where('id', $course['instructor_id'])
                                  ->where('role', 'teacher')
                                  ->first();
            
            if ($instructor && !empty($instructor['name'])) {
                $course['instructor_name'] = $instructor['name'];
                return $course;
            }
        }
        
        // If instructor_id doesn't point to a teacher, get the first available teacher
        $teacher = $userModel->where('role', 'teacher')->first();
        if ($teacher && !empty($teacher['name'])) {
            $course['instructor_name'] = $teacher['name'];
        } else {
            $course['instructor_name'] = null;
        }
        
        return $course;
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

    /**
     * Check if course title already exists (case-insensitive)
     * @param string $title Course title to check
     * @param int|null $excludeId Course ID to exclude from check (for updates)
     * @return bool True if title exists, false otherwise
     */
    public function isTitleExists($title, $excludeId = null)
    {
        $titleLower = strtolower(trim($title));
        
        // Get all courses and check in PHP for case-insensitive comparison
        $courses = $this->findAll();
        
        foreach ($courses as $course) {
            // Skip if this is the course we're updating
            if ($excludeId !== null && $course['id'] == $excludeId) {
                continue;
            }
            
            // Case-insensitive comparison
            if (strtolower(trim($course['title'])) === $titleLower) {
                return true;
            }
        }
        
        return false;
    }
}
