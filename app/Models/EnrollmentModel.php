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

    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrollment_date',
        'status',
        'progress'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'course_id' => 'required|integer',
        'enrollment_date' => 'required|valid_date',
        'status' => 'in_list[active,completed,dropped,suspended]',
        'progress' => 'decimal'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'course_id' => [
            'required' => 'Course ID is required',
            'integer' => 'Course ID must be an integer'
        ],
        'enrollment_date' => [
            'required' => 'Enrollment date is required',
            'valid_date' => 'Enrollment date must be a valid date'
        ],
        'status' => [
            'in_list' => 'Status must be one of: active, completed, dropped, suspended'
        ]
    ];

    /**
     * Enroll a user in a course
     * 
     * @param array $data Enrollment data
     * @return int|false Inserted ID or false on failure
     */
    public function enrollUser($data)
    {
        // Set default values
        $data['enrollment_date'] = $data['enrollment_date'] ?? date('Y-m-d H:i:s');
        $data['status'] = $data['status'] ?? 'active';
        $data['progress'] = $data['progress'] ?? 0.00;

        // Check if user is already enrolled
        if ($this->isAlreadyEnrolled($data['user_id'], $data['course_id'])) {
            return false; // User already enrolled
        }

        return $this->insert($data);
    }

    /**
     * Get all courses a user is enrolled in
     * 
     * @param int $user_id User ID
     * @return array Array of enrollment records with course details
     */
    public function getUserEnrollments($user_id)
    {
        return $this->select('enrollments.*, courses.title as course_title, courses.description as course_description, courses.instructor_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $user_id)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @return bool True if already enrolled, false otherwise
     */
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        $enrollment = $this->where('user_id', $user_id)
                          ->where('course_id', $course_id)
                          ->first();

        return $enrollment !== null;
    }

    /**
     * Get enrollment by user and course
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @return array|null Enrollment record or null
     */
    public function getEnrollment($user_id, $course_id)
    {
        return $this->where('user_id', $user_id)
                   ->where('course_id', $course_id)
                   ->first();
    }

    /**
     * Update enrollment progress
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @param float $progress Progress percentage
     * @return bool Success status
     */
    public function updateProgress($user_id, $course_id, $progress)
    {
        return $this->where('user_id', $user_id)
                   ->where('course_id', $course_id)
                   ->set('progress', $progress)
                   ->update();
    }

    /**
     * Update enrollment status
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateStatus($user_id, $course_id, $status)
    {
        return $this->where('user_id', $user_id)
                   ->where('course_id', $course_id)
                   ->set('status', $status)
                   ->update();
    }

    /**
     * Get all enrollments for a specific course
     * 
     * @param int $course_id Course ID
     * @return array Array of enrollment records with user details
     */
    public function getCourseEnrollments($course_id)
    {
        return $this->select('enrollments.*, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $course_id)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }
}
