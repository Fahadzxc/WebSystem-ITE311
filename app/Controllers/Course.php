<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    /**
     * Display all available courses
     */
    public function index()
    {
        $data = [
            'title' => 'Available Courses',
            'courses' => $this->courseModel->findAll()
        ];

        return view('courses/index', $data);
    }

    /**
     * Display a specific course
     */
    public function view($id)
    {
        $course = $this->courseModel->find($id);
        
        if (!$course) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Course not found');
        }

        $data = [
            'title' => $course['title'],
            'course' => $course
        ];

        return view('courses/view', $data);
    }

    /**
     * Handle course enrollment via AJAX
     */
    public function enroll()
    {
        // Debug log
        log_message('debug', 'Enrollment request received');
        
        // Check if user is logged in (try session first, then POST data)
        $user_id = session()->get('user_id');
        if (!$user_id) {
            $user_id = $this->request->getPost('user_id');
        }
        
        if (!$user_id || !session()->get('isLoggedIn')) {
            log_message('debug', 'User not logged in');
            session()->setFlashdata('error', 'You must be logged in to enroll in courses.');
            return redirect()->to('login');
        }

        // Check if request is POST
        if ($this->request->getMethod() !== 'post') {
            session()->setFlashdata('error', 'Invalid request method.');
            return redirect()->to('dashboard');
        }

        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        if (!$course_id) {
            session()->setFlashdata('error', 'Course ID is required.');
            return redirect()->to('dashboard');
        }

        // Validate course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            session()->setFlashdata('error', 'Course not found.');
            return redirect()->to('dashboard');
        }

        // User ID already obtained above

        // Check if user is already enrolled (active or pending)
        if ($this->enrollmentModel->isAlreadyEnrolled($user_id, $course_id)) {
            session()->setFlashdata('error', 'You already have a pending or active enrollment in this course.');
            return redirect()->to('dashboard');
        }

        // Check if course has an instructor assigned
        if (empty($course['instructor_id'])) {
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
            // Create notification for student (pending status)
            try {
                $notificationModel = new \App\Models\NotificationModel();
                $pendingMessage = "Your enrollment request for '{$course['title']}' is pending approval. You will be notified once the instructor reviews your request.";
                $notificationModel->createNotification($user_id, $pendingMessage);
                
                // Create notification for teacher
                $teacherMessage = "New enrollment request: " . session()->get('name') . " wants to enroll in '{$course['title']}'.";
                $notificationModel->createNotification($course['instructor_id'], $teacherMessage);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create enrollment notification: ' . $e->getMessage());
            }
            
            session()->setFlashdata('success', 'Enrollment request submitted for ' . $course['title'] . '! Waiting for instructor approval.');
            return redirect()->to('dashboard');
        } else {
            session()->setFlashdata('error', 'Failed to submit enrollment request. Please try again.');
            return redirect()->to('dashboard');
        }
    }

    /**
     * Handle course unenrollment via AJAX
     */
    public function unenroll()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to unenroll from courses.'
            ]);
        }

        // Check if request is POST
        if ($this->request->getMethod() !== 'post') {
            session()->setFlashdata('error', 'Invalid request method.');
            return redirect()->to('dashboard');
        }

        // Get course_id from POST request
        $course_id = $this->request->getPost('course_id');
        
        if (!$course_id) {
            session()->setFlashdata('error', 'Course ID is required.');
            return redirect()->to('dashboard');
        }

        // Get user ID from session
        $user_id = session()->get('user_id');

        // Check if user is enrolled
        $enrollment = $this->enrollmentModel->getEnrollment($user_id, $course_id);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not enrolled in this course.'
            ]);
        }

        // Update enrollment status to dropped
        $result = $this->enrollmentModel->updateStatus($user_id, $course_id, 'dropped');

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully unenrolled from course.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll from course. Please try again.'
            ]);
        }
    }

    /**
     * Get user's enrolled courses
     */
    public function myEnrollments()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        $user_id = session()->get('user_id');
        $enrollments = $this->enrollmentModel->getUserEnrollments($user_id);

        $data = [
            'title' => 'My Enrollments',
            'enrollments' => $enrollments
        ];

        return view('courses/my_enrollments', $data);
    }
}
