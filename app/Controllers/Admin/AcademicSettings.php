<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\YearLevelModel;
use App\Models\SemesterModel;
use App\Models\TermModel;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;

class AcademicSettings extends BaseController
{
    protected $academicYearModel;
    protected $yearLevelModel;
    protected $semesterModel;
    protected $termModel;
    protected $userModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->academicYearModel = new AcademicYearModel();
        $this->yearLevelModel = new YearLevelModel();
        $this->semesterModel = new SemesterModel();
        $this->termModel = new TermModel();
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Display academic settings dashboard
     */
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to(base_url('login'));
        }

        // Get filter parameters
        $academic_year_id = $this->request->getGet('academic_year_id');
        $semester_id = $this->request->getGet('semester_id');
        $term_id = $this->request->getGet('term_id');

        // Get enrollments filtered by academic period
        $enrollments = [];
        if ($academic_year_id || $semester_id || $term_id) {
            $enrollments = $this->enrollmentModel->getEnrollmentsByAcademicPeriod($academic_year_id, $semester_id, $term_id);
        }

        // Group enrollments by student
        $enrollmentsByStudent = [];
        foreach ($enrollments as $enrollment) {
            $student_id = $enrollment['user_id'];
            if (!isset($enrollmentsByStudent[$student_id])) {
                $enrollmentsByStudent[$student_id] = [
                    'student_name' => $enrollment['student_name'],
                    'student_email' => $enrollment['student_email'],
                    'enrollments' => [],
                    'count' => 0
                ];
            }
            $enrollmentsByStudent[$student_id]['enrollments'][] = $enrollment;
            $enrollmentsByStudent[$student_id]['count']++;
        }

        $data = [
            'academic_years' => $this->academicYearModel->getAllAcademicYears(),
            'year_levels' => $this->yearLevelModel->getAllYearLevels(),
            'semesters' => $this->semesterModel->getAllSemesters(),
            'terms' => $this->termModel->getAllTerms(),
            'active_academic_year' => $this->academicYearModel->getActiveAcademicYear(),
            'enrollments' => $enrollments,
            'enrollmentsByStudent' => $enrollmentsByStudent,
            'filter_academic_year_id' => $academic_year_id,
            'filter_semester_id' => $semester_id,
            'filter_term_id' => $term_id,
        ];

        return view('admin/academic_settings', $data);
    }

    /**
     * Apply academic year to existing students and courses
     */
    public function applyAcademicYear()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied'
            ])->setStatusCode(403);
        }

        $academic_year_id = $this->request->getPost('academic_year_id');
        $semester_id = $this->request->getPost('semester_id');
        $term_id = $this->request->getPost('term_id');

        if (!$academic_year_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Academic year is required'
            ]);
        }

        $db = \Config\Database::connect();

        // Update all courses to use this academic year
        if ($academic_year_id) {
            $db->table('courses')
               ->where('academic_year_id IS NULL')
               ->update(['academic_year_id' => $academic_year_id]);
        }

        if ($semester_id) {
            $db->table('courses')
               ->where('semester_id IS NULL')
               ->update(['semester_id' => $semester_id]);
        }

        if ($term_id) {
            $db->table('courses')
               ->where('term_id IS NULL')
               ->update(['term_id' => $term_id]);
        }

        // Update all enrollments to use this academic year
        if ($academic_year_id) {
            $db->table('enrollments')
               ->where('academic_year_id IS NULL')
               ->update(['academic_year_id' => $academic_year_id]);
        }

        if ($semester_id) {
            $db->table('enrollments')
               ->where('semester_id IS NULL')
               ->update(['semester_id' => $semester_id]);
        }

        if ($term_id) {
            $db->table('enrollments')
               ->where('term_id IS NULL')
               ->update(['term_id' => $term_id]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Academic year applied to all existing courses and enrollments'
        ]);
    }

    /**
     * Assign year level to students
     */
    public function assignYearLevelToStudents()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied'
            ])->setStatusCode(403);
        }

        $year_level_id = $this->request->getPost('year_level_id');
        $student_ids = $this->request->getPost('student_ids'); // Array of student IDs

        if (!$year_level_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Year level is required'
            ]);
        }

        $db = \Config\Database::connect();

        if (!empty($student_ids) && is_array($student_ids)) {
            // Update specific students
            $db->table('users')
               ->whereIn('id', $student_ids)
               ->where('role', 'student')
               ->update(['year_level_id' => $year_level_id]);
        } else {
            // Update all students without year level
            $db->table('users')
               ->where('role', 'student')
               ->where('year_level_id IS NULL')
               ->update(['year_level_id' => $year_level_id]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Year level assigned to students'
        ]);
    }
}

