<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class Course extends Controller
{
    protected $courseModel;
    protected $enrollmentModel;
    protected $userModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
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
     * Search courses by name or description
     */
    public function search()
    {
        // Accept search term from GET or POST
        $searchTerm = $this->request->getGet('search_term') ?? $this->request->getPost('search_term');
        
        // Build query with search conditions
        if (!empty($searchTerm)) {
            $this->courseModel->groupStart();
            $this->courseModel->like('title', $searchTerm);
            $this->courseModel->orLike('description', $searchTerm);
            $this->courseModel->groupEnd();
        }
        
        $courses = $this->courseModel->findAll();
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON($courses);
        }
        
        return view('courses/search_results', ['courses' => $courses, 'searchTerm' => $searchTerm]);
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
