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
        'progress',
        'rejection_reason'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'course_id' => 'required|integer',
        'enrollment_date' => 'required|valid_date',
        'status' => 'in_list[pending,active,completed,dropped,suspended,rejected]',
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
            'in_list' => 'Status must be one of: pending, active, completed, dropped, suspended, rejected'
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
        // Set default values (but don't override if explicitly set)
        if (!isset($data['enrollment_date'])) {
            $data['enrollment_date'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }
        if (!isset($data['progress'])) {
            $data['progress'] = 0.00;
        }
        
        // Debug: Log the data being inserted
        log_message('debug', 'EnrollmentModel::enrollUser() - Data: ' . json_encode($data));

        // Check if user is already enrolled (active or pending)
        if ($this->isAlreadyEnrolled($data['user_id'], $data['course_id'])) {
            return false; // User already enrolled
        }

        // Check if there's ANY existing enrollment (regardless of status) to avoid duplicate entry error
        $existingEnrollment = $this->where('user_id', $data['user_id'])
                                  ->where('course_id', $data['course_id'])
                                  ->first();

        if ($existingEnrollment) {
            // If status is rejected, dropped, or suspended, update it to pending
            if (in_array($existingEnrollment['status'], ['rejected', 'dropped', 'suspended'])) {
                $updateData = [
                    'status' => $data['status'],
                    'enrollment_date' => $data['enrollment_date'],
                    'rejection_reason' => null, // Clear rejection reason
                    'progress' => $data['progress']
                ];
                
                if ($this->update($existingEnrollment['id'], $updateData)) {
                    return $existingEnrollment['id']; // Return existing ID
                }
                return false;
            }
            
            // If status is something else (like 'completed'), return false
            return false;
        }

        // No existing enrollment, insert new one
        try {
            $result = $this->insert($data);
            if ($result === false) {
                $errors = $this->errors();
                log_message('error', 'EnrollmentModel::enrollUser() - Insert failed. Data: ' . json_encode($data) . ' Errors: ' . json_encode($errors));
                
                // Check if the error is related to status ENUM
                if (!empty($errors)) {
                    foreach ($errors as $field => $error) {
                        if (strpos(strtolower($error), 'status') !== false || strpos(strtolower($error), 'enum') !== false) {
                            log_message('error', 'ENUM ERROR: The status column may not include "pending". Please run the migration or update the database manually.');
                        }
                    }
                }
            } else {
                // Verify the insert was successful by checking the actual saved data
                $saved = $this->find($result);
                if ($saved && $saved['status'] !== $data['status']) {
                    log_message('error', 'EnrollmentModel::enrollUser() - Status mismatch! Requested: ' . $data['status'] . ', Saved: ' . ($saved['status'] ?? 'null'));
                }
            }
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'EnrollmentModel::enrollUser() - Exception: ' . $e->getMessage());
            log_message('error', 'EnrollmentModel::enrollUser() - Exception trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Get all courses a user is enrolled in (excluding expired enrollments)
     * 
     * @param int $user_id User ID
     * @return array Array of enrollment records with course details
     */
    public function getUserEnrollments($user_id)
    {
        // First, remove expired enrollments for this user
        $this->removeExpiredEnrollments($user_id);
        
        return $this->select('enrollments.*, courses.title as course_title, courses.description as course_description, courses.instructor_id')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->where('enrollments.user_id', $user_id)
                    ->whereIn('enrollments.status', ['active', 'pending', 'rejected'])
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get pending enrollments for a course
     * 
     * @param int $course_id Course ID
     * @return array Array of pending enrollment records
     */
    public function getPendingEnrollments($course_id)
    {
        return $this->select('enrollments.*, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $course_id)
                    ->where('enrollments.status', 'pending')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Check if enrollment is expired (4 months after enrollment date)
     * 
     * @param string $enrollment_date Enrollment date
     * @return bool True if expired, false otherwise
     */
    public function isEnrollmentExpired($enrollment_date)
    {
        $enrollmentDate = new \DateTime($enrollment_date);
        $expiryDate = clone $enrollmentDate;
        $expiryDate->modify('+4 months');
        $now = new \DateTime();
        
        return $now > $expiryDate;
    }

    /**
     * Remove expired enrollments for a specific user
     * 
     * @param int $user_id User ID (optional, if null removes for all users)
     * @return int Number of enrollments removed
     */
    public function removeExpiredEnrollments($user_id = null)
    {
        $builder = $this->where('status', 'active');
        
        if ($user_id) {
            $builder->where('user_id', $user_id);
        }
        
        $enrollments = $builder->findAll();
        $removedCount = 0;
        
        foreach ($enrollments as $enrollment) {
            if ($this->isEnrollmentExpired($enrollment['enrollment_date'])) {
                // Delete the enrollment
                $this->delete($enrollment['id']);
                $removedCount++;
            }
        }
        
        return $removedCount;
    }

    /**
     * Get enrollment expiry date
     * 
     * @param string $enrollment_date Enrollment date
     * @return string Expiry date in Y-m-d H:i:s format
     */
    public function getExpiryDate($enrollment_date)
    {
        $enrollmentDate = new \DateTime($enrollment_date);
        $expiryDate = clone $enrollmentDate;
        $expiryDate->modify('+4 months');
        
        return $expiryDate->format('Y-m-d H:i:s');
    }

    /**
     * Get enrollments by academic period (deprecated - academic settings removed)
     */
    public function getEnrollmentsByAcademicPeriod($academic_year_id = null, $semester_id = null, $term_id = null)
    {
        $builder = $this->select('enrollments.*, courses.title as course_title, users.name as student_name, users.email as student_email')
                       ->join('courses', 'courses.id = enrollments.course_id', 'left')
                       ->join('users', 'users.id = enrollments.user_id', 'left');

        return $builder->orderBy('enrollments.enrollment_date', 'DESC')->findAll();
    }

    /**
     * Get student enrollments count by semester (deprecated - academic settings removed)
     */
    public function getStudentEnrollmentsBySemester($user_id, $semester_id = null)
    {
        $builder = $this->where('user_id', $user_id)
                       ->where('status', 'active');

        return $builder->countAllResults();
    }

    /**
     * Check if a user is already enrolled in a specific course (active or pending)
     * 
     * @param int $user_id User ID
     * @param int $course_id Course ID
     * @return bool True if already enrolled (active or pending), false otherwise
     */
    public function isAlreadyEnrolled($user_id, $course_id)
    {
        $enrollment = $this->where('user_id', $user_id)
                          ->where('course_id', $course_id)
                          ->whereIn('status', ['active', 'pending'])
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
