<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\MaterialModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Models\AssignmentModel;
use App\Models\AssignmentSubmissionModel;

class Student extends BaseController
{
    protected $enrollmentModel;
    protected $courseModel;
    protected $materialModel;
    protected $notificationModel;
    protected $userModel;
    protected $assignmentModel;
    protected $submissionModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->materialModel = new MaterialModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        $this->assignmentModel = new AssignmentModel();
        $this->submissionModel = new AssignmentSubmissionModel();
    }

    public function dashboard()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        $user_id = session()->get('user_id');

        // Get user's enrollments
        $enrollments = $this->enrollmentModel->getUserEnrollments($user_id);

        // Get available courses (courses not enrolled in)
        $enrolled_course_ids = array_column($enrollments, 'course_id');
        $all_courses = [];
        
        if (!empty($enrolled_course_ids)) {
            $all_courses = $this->courseModel->whereNotIn('id', $enrolled_course_ids)->findAll();
        } else {
            $all_courses = $this->courseModel->findAll();
        }
        
        // Separate available and unavailable courses based on semester
        $available_courses = [];
        $unavailable_courses = [];
        
        foreach ($all_courses as $course) {
            // If course is 2nd Semester, put it in unavailable
            if (!empty($course['semester']) && $course['semester'] === '2nd Semester') {
                $unavailable_courses[] = $course;
            } else {
                $available_courses[] = $course;
            }
        }

        // Calculate assignment completion percentage
        $assignment_completion = 0;
        $user_id = session()->get('user_id');
        if (!empty($enrolled_course_ids)) {
            // Get all assignments for enrolled courses
            $all_assignments = $this->assignmentModel
                ->whereIn('course_id', $enrolled_course_ids)
                ->findAll();
            
            $total_assignments = count($all_assignments);
            
            // Count how many have been submitted
            $submitted_assignments = 0;
            if ($total_assignments > 0) {
                foreach ($all_assignments as $assignment) {
                    $hasSubmission = $this->submissionModel
                        ->where('assignment_id', $assignment['id'])
                        ->where('student_id', $user_id)
                        ->first();
                    
                    if ($hasSubmission) {
                        $submitted_assignments++;
                    }
                }
                
                $assignment_completion = ($submitted_assignments / $total_assignments) * 100;
            }
        }
        
        // Also keep enrollment progress for reference
        $enrollment_progress = 0;
        if (!empty($enrollments)) {
            $total_progress = array_sum(array_column($enrollments, 'progress'));
            $enrollment_progress = $total_progress / count($enrollments);
        }

        // Get upcoming deadlines (assignments with due dates in the future that haven't been submitted)
        $upcoming_deadlines = [];
        if (!empty($enrolled_course_ids)) {
            $user_id = session()->get('user_id');
            $now = date('Y-m-d H:i:s');
            $assignments = $this->assignmentModel
                ->select('assignments.*, courses.title as course_title')
                ->join('courses', 'courses.id = assignments.course_id')
                ->whereIn('assignments.course_id', $enrolled_course_ids)
                ->where('assignments.due_date >', $now)
                ->orderBy('assignments.due_date', 'ASC')
                ->limit(10)
                ->findAll();
            
            // Filter out assignments that have already been submitted
            foreach ($assignments as $assignment) {
                $hasSubmission = $this->submissionModel
                    ->where('assignment_id', $assignment['id'])
                    ->where('student_id', $user_id)
                    ->first();
                
                // Only add if not submitted yet
                if (!$hasSubmission) {
                    $upcoming_deadlines[] = [
                        'title' => $assignment['title'],
                        'course_title' => $assignment['course_title'],
                        'due_date' => $assignment['due_date'],
                        'assignment_id' => $assignment['id']
                    ];
                    
                    // Limit to 5 unsubmitted assignments
                    if (count($upcoming_deadlines) >= 5) {
                        break;
                    }
                }
            }
        }

        // Get recent grades (graded assignment submissions)
        $recent_grades = [];
        $user_id = session()->get('user_id');
        $submissions = $this->submissionModel
            ->select('assignment_submissions.*, assignments.title as assignment_title, assignments.max_score, courses.title as course_title')
            ->join('assignments', 'assignments.id = assignment_submissions.assignment_id')
            ->join('courses', 'courses.id = assignments.course_id')
            ->where('assignment_submissions.student_id', $user_id)
            ->where('assignment_submissions.status', 'graded')
            ->where('assignment_submissions.score IS NOT NULL')
            ->orderBy('assignment_submissions.updated_at', 'DESC')
            ->limit(5)
            ->findAll();
        
        foreach ($submissions as $submission) {
            $percentage = $submission['max_score'] > 0 
                ? ($submission['score'] / $submission['max_score']) * 100 
                : 0;
            
            $recent_grades[] = [
                'assignment_title' => $submission['assignment_title'],
                'course_title' => $submission['course_title'],
                'score' => $submission['score'],
                'max_score' => $submission['max_score'],
                'percentage' => $percentage,
                'graded_at' => $submission['updated_at'],
                'assignment_id' => $submission['assignment_id']
            ];
        }

        return view('auth/dashboard', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'enrollments' => $enrollments,
            'available_courses' => $available_courses,
            'unavailable_courses' => $unavailable_courses,
            'overall_progress' => $assignment_completion, // Use assignment completion instead
            'enrollment_progress' => $enrollment_progress, // Keep enrollment progress for reference
            'upcoming_deadlines' => $upcoming_deadlines,
            'recent_grades' => $recent_grades,
            'unread_count' => $this->data['unread_count'] ?? 0
        ]);
    }

    public function enroll()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to enroll in courses.');
            return redirect()->to('login');
        }

        // Check if user is a student
        if (session()->get('role') !== 'student') {
            session()->setFlashdata('error', 'Access denied. Student role required.');
            return redirect()->to('login');
        }

        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        if (!$course_id) {
            session()->setFlashdata('error', 'Course ID is required.');
            return redirect()->to('dashboard');
        }

        // Get user ID from session only (never trust client data)
        $user_id = session()->get('user_id');
        
        if (!$user_id) {
            session()->setFlashdata('error', 'User session not found.');
            return redirect()->to('login');
        }

        // Validate course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->to('dashboard');
        }

        // Check if user is already enrolled (active or pending)
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            // Get the enrollment to check status
            $existingEnrollment = $this->enrollmentModel->getEnrollment($user_id, $course_id);
            if ($existingEnrollment) {
                if ($existingEnrollment['status'] === 'pending') {
                    session()->setFlashdata('error', 'You already have a pending enrollment request for this course. Please wait for instructor approval.');
                } else {
                    session()->setFlashdata('error', 'You are already enrolled in this course.');
                }
            }
            return redirect()->to('dashboard');
        }

        // Check if course has an instructor assigned
        if (empty($course['instructor_id']) || $course['instructor_id'] == 0 || $course['instructor_id'] == null) {
            session()->setFlashdata('error', 'This course does not have an instructor assigned yet. Please contact the administrator.');
            return redirect()->to('dashboard');
        }

        // Prepare enrollment data with pending status
        $enrollmentData = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'progress' => 0.00
        ];

        // Insert new enrollment record
        $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);

        if ($enrollmentId) {
            // Verify the enrollment was created with pending status
            $createdEnrollment = $this->enrollmentModel->find($enrollmentId);
            if ($createdEnrollment && $createdEnrollment['status'] !== 'pending') {
                log_message('error', 'Enrollment created with wrong status. ID: ' . $enrollmentId . ', Status: ' . ($createdEnrollment['status'] ?? 'null'));
                // Try to update it to pending
                $this->enrollmentModel->update($enrollmentId, ['status' => 'pending']);
            }
            
            // Get student name for notifications
            $studentName = session()->get('name');
            
            // Create notification for student (pending status)
            try {
                $pendingMessage = "Your enrollment request for '{$course['title']}' is pending approval. You will be notified once the instructor reviews your request.";
                $this->notificationModel->createNotification($user_id, $pendingMessage);
                
                // Create notification for teacher
                $teacherMessage = "New enrollment request: {$studentName} wants to enroll in '{$course['title']}'.";
                $this->notificationModel->createNotification($course['instructor_id'], $teacherMessage);
                
                // Notify all admins about the enrollment request
                $admins = $this->userModel->where('role', 'admin')
                                         ->where('is_deleted', 0)
                                         ->findAll();

                foreach ($admins as $admin) {
                    $adminMessage = "New enrollment request: {$studentName} wants to enroll in '{$course['title']}'. Waiting for instructor approval.";
                    $this->notificationModel->createNotification($admin['id'], $adminMessage);
                }
                
                // Debug log
                log_message('debug', 'Enrollment created - ID: ' . $enrollmentId . ', User: ' . $user_id . ', Course: ' . $course_id . ', Status: ' . ($createdEnrollment['status'] ?? 'unknown'));
            } catch (\Exception $e) {
                // Log error but don't fail the enrollment
                log_message('error', 'Failed to create enrollment notification: ' . $e->getMessage());
            }
            
            session()->setFlashdata('success', 'Enrollment request submitted for ' . $course['title'] . '! Waiting for instructor approval.');
            return redirect()->to('dashboard');
        } else {
            // Get more specific error information
            $errors = $this->enrollmentModel->errors();
            $errorMessage = 'Failed to submit enrollment request.';
            
            if (!empty($errors)) {
                $errorMessage .= ' ' . implode(' ', $errors);
            } else {
                // Check if user is already enrolled with a different status
                $existingEnrollment = $this->enrollmentModel->getEnrollment($user_id, $course_id);
                if ($existingEnrollment) {
                    if ($existingEnrollment['status'] === 'completed') {
                        $errorMessage = 'You have already completed this course.';
                    } else {
                        $errorMessage = 'Enrollment request could not be processed. Please contact support.';
                    }
                } else {
                    $errorMessage = 'Failed to submit enrollment request. The course may not be available for enrollment.';
                }
            }
            
            log_message('error', 'Enrollment failed for user ' . $user_id . ' in course ' . $course_id . '. Errors: ' . json_encode($errors));
            session()->setFlashdata('error', $errorMessage);
            return redirect()->to('dashboard');
        }
    }

    /**
     * Display materials for enrolled courses
     */
    public function materials()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        // Check if user is a student
        if (session('role') !== 'student') {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('login'));
        }

        $user_id = session()->get('user_id');

        // Get user's enrollments
        $enrollments = $this->enrollmentModel->getUserEnrollments($user_id);

        if (empty($enrollments)) {
            return view('student/materials', [
                'user' => [
                    'name' => session('name'),
                    'email' => session('email'),
                    'role' => session('role'),
                ],
                'enrollments' => [],
                'materials_by_course' => []
            ]);
        }

        // Get course IDs from enrollments
        $course_ids = array_column($enrollments, 'course_id');

        // Get all materials for enrolled courses
        $materials_by_course = [];
        foreach ($enrollments as $enrollment) {
            $course_id = $enrollment['course_id'];
            $materials = $this->materialModel->getMaterialsByCourse($course_id);
            
            if (!empty($materials)) {
                $materials_by_course[] = [
                    'course_id' => $course_id,
                    'course_title' => $enrollment['course_title'],
                    'course_description' => $enrollment['course_description'],
                    'materials' => $materials
                ];
            }
        }

        return view('student/materials', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'enrollments' => $enrollments,
            'materials_by_course' => $materials_by_course
        ]);
    }

    /**
     * Display materials for a specific course
     */
    public function courseMaterials($course_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to(base_url('login'));
        }

        // Check if user is a student
        if (session('role') !== 'student') {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('login'));
        }

        $user_id = session()->get('user_id');

        // Check if user is enrolled in this course
        $enrollment = $this->enrollmentModel->getEnrollment($user_id, $course_id);
        if (!$enrollment) {
            session()->setFlashdata('error', 'You are not enrolled in this course.');
            return redirect()->to(base_url('student/materials'));
        }

        // Check if enrollment is expired (4 months)
        if ($this->enrollmentModel->isEnrollmentExpired($enrollment['enrollment_date'])) {
            // Remove expired enrollment
            $this->enrollmentModel->delete($enrollment['id']);
            session()->setFlashdata('error', 'Your enrollment in this course has expired (4 months duration).');
            return redirect()->to(base_url('student/materials'));
        }

        // Get course information with instructor name
        $course = $this->courseModel->getCourseWithInstructor($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->to(base_url('student/materials'));
        }

        // Get materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        return view('student/course_materials', [
            'user' => [
                'name' => session('name'),
                'email' => session('email'),
                'role' => session('role'),
            ],
            'course' => $course,
            'materials' => $materials
        ]);
    }

    /**
     * Create additional test notifications for demonstration
     * Access via: /student/create-test-notifications
     */
    public function createTestNotifications()
    {
        // Must be logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login first.');
            return redirect()->to('login');
        }
        
        $user_id = session()->get('user_id');
        
        try {
            // Create various test notifications
            $testNotifications = [
                " New assignment has been posted in your enrolled course. Check it out!",
                " Your quiz submission has been graded. View your results now.",
                " Course materials have been updated. New resources are available.",
                " Reminder: Assignment deadline is approaching in 2 days.",
                " Welcome to the Learning Management System! Explore your dashboard.",
                " You have a new message from your instructor.",
                " Congratulations! You've completed 50% of the course."
            ];
            
            foreach ($testNotifications as $message) {
                $this->notificationModel->createNotification($user_id, $message);
            }
            
            session()->setFlashdata('success', 'Test notifications created successfully! Check your notification dropdown.');
            
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Failed to create test notifications: ' . $e->getMessage());
        }
        
        return redirect()->to('dashboard');
    }
}