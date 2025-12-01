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
        'academic_year_id',
        'semester_id',
        'term_id'
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

        // Auto-assign academic year, semester, and term if not provided
        if (empty($data['academic_year_id'])) {
            $academicYearModel = new \App\Models\AcademicYearModel();
            $activeYear = $academicYearModel->getActiveAcademicYear();
            if ($activeYear) {
                $data['academic_year_id'] = $activeYear['id'];
            }
        }

        // Auto-assign semester if not provided (default to First Semester)
        if (empty($data['semester_id'])) {
            $semesterModel = new \App\Models\SemesterModel();
            $firstSemester = $semesterModel->where('semester_number', 1)->first();
            if ($firstSemester) {
                $data['semester_id'] = $firstSemester['id'];
            }
        }

        // Auto-assign term if not provided (default to 1st Term)
        if (empty($data['term_id'])) {
            $termModel = new \App\Models\TermModel();
            $firstTerm = $termModel->where('term_number', 1)->where('is_summer', 0)->first();
            if ($firstTerm) {
                $data['term_id'] = $firstTerm['id'];
            }
        }

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
        return $this->select('enrollments.*, courses.title as course_title, courses.description as course_description, courses.instructor_id, 
                             academic_years.description as academic_year, semesters.semester_name, terms.term_name')
                    ->join('courses', 'courses.id = enrollments.course_id', 'left')
                    ->join('academic_years', 'academic_years.id = enrollments.academic_year_id', 'left')
                    ->join('semesters', 'semesters.id = enrollments.semester_id', 'left')
                    ->join('terms', 'terms.id = enrollments.term_id', 'left')
                    ->where('enrollments.user_id', $user_id)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get enrollments by academic period
     */
    public function getEnrollmentsByAcademicPeriod($academic_year_id = null, $semester_id = null, $term_id = null)
    {
        $builder = $this->select('enrollments.*, courses.title as course_title, users.name as student_name, users.email as student_email,
                                 academic_years.description as academic_year, semesters.semester_name, terms.term_name')
                       ->join('courses', 'courses.id = enrollments.course_id', 'left')
                       ->join('users', 'users.id = enrollments.user_id', 'left')
                       ->join('academic_years', 'academic_years.id = enrollments.academic_year_id', 'left')
                       ->join('semesters', 'semesters.id = enrollments.semester_id', 'left')
                       ->join('terms', 'terms.id = enrollments.term_id', 'left');

        if ($academic_year_id) {
            $builder->where('enrollments.academic_year_id', $academic_year_id);
        }
        if ($semester_id) {
            $builder->where('enrollments.semester_id', $semester_id);
        }
        if ($term_id) {
            $builder->where('enrollments.term_id', $term_id);
        }

        return $builder->orderBy('enrollments.enrollment_date', 'DESC')->findAll();
    }

    /**
     * Get student enrollments count by semester
     */
    public function getStudentEnrollmentsBySemester($user_id, $semester_id = null)
    {
        $builder = $this->where('enrollments.user_id', $user_id)
                       ->where('enrollments.status', 'active');

        if ($semester_id) {
            $builder->where('enrollments.semester_id', $semester_id);
        }

        return $builder->countAllResults();
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
