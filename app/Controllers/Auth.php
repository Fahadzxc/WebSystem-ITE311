<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function register()
    {
        helper(['form']);
        $session = session();
        $model = new UserModel();
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'matches[password]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role' => 'student'
                ];
                
                // Save user to database
                if ($model->insert($data)) {
                    $session->setFlashdata('success', 'Registration successful. Please login.');
                    return redirect()->to('/login');
                } else {
                    // Get the last error for debugging
                    $errors = $model->errors();
                    $errorMessage = 'Registration failed. ';
                    if (!empty($errors)) {
                        $errorMessage .= implode(', ', $errors);
                    } else {
                        $errorMessage .= 'Please try again.';
                    }
                    $session->setFlashdata('error', $errorMessage);
                }
            }
        }
        
        echo view('auth/register', [
            'validation' => $this->validator
        ]);
    }

    public function login()
    {
        // Redirect if already logged in
        if (session()->get('isLoggedIn')) {
                    return redirect()->to('/dashboard');
        }
        
        helper(['form']);
        $session = session();
        $model = new UserModel();
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];
            if ($this->validate($rules)) {
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                $user = $model->where('email', $email)->first();
                
                // Check if user is deleted
                if ($user && isset($user['is_deleted']) && $user['is_deleted'] == 1) {
                    $session->setFlashdata('error', 'This account has been deleted. Please contact the administrator.');
                    return redirect()->to('/login');
                }
                
                if ($user && password_verify($password, $user['password'])) {
                    $session->set([
                        'user_id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true
                    ]);
                    $session->setFlashdata('success', 'Welcome, ' . $user['name'] . '!');
                    
                    // Debug: Log the role for debugging
                    log_message('debug', 'Auth Login - User Role: ' . $user['role'] . ', Redirecting...');
                    
                    // Redirect to unified dashboard
                    return redirect()->to('/dashboard');
                } else {
                    $session->setFlashdata('error', 'Invalid login credentials.');
                }
            }
        }
        echo view('auth/login', [
            'validation' => $this->validator
        ]);
    }

    public function logout()
    {
        // Destroy the current session
        session()->destroy();
        
        // Set logout message and redirect
        session()->setFlashdata('success', 'You have been logged out successfully.');
        return redirect()->to(base_url('login'));
    }

    public function dashboard()
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please login to access the dashboard.');
            return redirect()->to('login');
        }

        $role = session()->get('role');
        
        // Load data based on role and render unified dashboard
        switch ($role) {
            case 'admin':
                // Admin dashboard - load users, courses, and teachers data
                $userModel = new \App\Models\UserModel();
                $courseModel = new \App\Models\CourseModel();
                
                $users = $userModel->getAllUsers();
                $courses = $courseModel->orderBy('title', 'ASC')->findAll();
                $teachers = $userModel->where('role', 'teacher')->where('is_deleted', 0)->orderBy('name', 'ASC')->findAll();
                
                // Get instructor names for each course
                foreach ($courses as &$course) {
                    // Check if instructor_id exists and is not 0 (0 means unassigned)
                    if (!empty($course['instructor_id']) && $course['instructor_id'] != 0) {
                        $instructor = $userModel->find($course['instructor_id']);
                        // Verify the instructor exists and is actually a teacher
                        if ($instructor && strtolower($instructor['role']) === 'teacher') {
                            $course['instructor_name'] = $instructor['name'];
                        } else {
                            $course['instructor_name'] = 'Unassigned';
                        }
                    } else {
                        $course['instructor_name'] = 'Unassigned';
                    }
                }
                
                return view('auth/dashboard', [
                    'user' => [
                        'name'  => session('name'),
                        'email' => session('email'),
                        'role'  => session('role'),
                    ],
                    'users' => $users,
                    'courses' => $courses,
                    'teachers' => $teachers
                ]);
                
            case 'teacher':
                // Teacher dashboard - load only courses assigned to this teacher
                $courseModel = new \App\Models\CourseModel();
                $scheduleModel = new \App\Models\CourseScheduleModel();
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $teacher_id = session()->get('user_id');
                
                // Only get courses where instructor_id matches the logged-in teacher
                $courses = $courseModel->where('instructor_id', $teacher_id)
                                      ->where('status', 'published')
                                      ->orderBy('title', 'ASC')
                                      ->findAll();
                
                // Load schedules for each course
                foreach ($courses as &$course) {
                    $course['schedules'] = $scheduleModel->getSchedulesByCourse($course['id']);
                }
                
                // Get pending enrollments for all teacher's courses
                $pendingEnrollments = [];
                
                if (!empty($courses)) {
                    $course_ids = array_column($courses, 'id');
                    
                    // Get all pending enrollments for courses assigned to this teacher
                    $pendingEnrollments = $enrollmentModel
                        ->select('enrollments.*, users.name as student_name, users.email as student_email, courses.title as course_title')
                        ->join('users', 'users.id = enrollments.user_id')
                        ->join('courses', 'courses.id = enrollments.course_id')
                        ->whereIn('enrollments.course_id', $course_ids)
                        ->where('enrollments.status', 'pending')
                        ->orderBy('enrollments.enrollment_date', 'DESC')
                        ->findAll();
                }
                
                return view('auth/dashboard', [
                    'user' => [
                        'name' => session('name'),
                        'email' => session('email'),
                        'role' => session('role'),
                    ],
                    'courses' => $courses,
                    'pendingEnrollments' => $pendingEnrollments
                ]);
                
            case 'student':
                // Student dashboard - load enrollments and courses
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $courseModel = new \App\Models\CourseModel();
                $user_id = session()->get('user_id');
                
                // Get all enrollments (active, pending, rejected)
                $allEnrollments = $enrollmentModel->getUserEnrollments($user_id);
                
                // Debug: Log all enrollments
                log_message('debug', 'Student ' . $user_id . ' - All enrollments: ' . json_encode($allEnrollments));
                
                // Separate active enrollments from pending/rejected
                $activeEnrollments = array_filter($allEnrollments, function($e) { return $e['status'] === 'active'; });
                $pendingEnrollments = array_filter($allEnrollments, function($e) { return $e['status'] === 'pending'; });
                $rejectedEnrollments = array_filter($allEnrollments, function($e) { return $e['status'] === 'rejected'; });
                
                // Debug: Log counts
                log_message('debug', 'Student ' . $user_id . ' - Active: ' . count($activeEnrollments) . ', Pending: ' . count($pendingEnrollments) . ', Rejected: ' . count($rejectedEnrollments));
                
                // Get enrolled course IDs (active only for available courses filter)
                $enrolled_course_ids = array_column($activeEnrollments, 'course_id');
                $pending_course_ids = array_column($pendingEnrollments, 'course_id');
                $rejected_course_ids = array_column($rejectedEnrollments, 'course_id');
                
                // Get available courses (exclude active, pending, and rejected)
                $excluded_course_ids = array_merge($enrolled_course_ids, $pending_course_ids, $rejected_course_ids);
                $available_courses = [];
                
                if (!empty($excluded_course_ids)) {
                    $available_courses = $courseModel->whereNotIn('id', $excluded_course_ids)->findAll();
                } else {
                    $available_courses = $courseModel->findAll();
                }
                
                // Calculate progress only from active enrollments
                $overall_progress = 0;
                if (!empty($activeEnrollments)) {
                    $total_progress = array_sum(array_column($activeEnrollments, 'progress'));
                    $overall_progress = $total_progress / count($activeEnrollments);
                }
                
                return view('auth/dashboard', [
                    'user' => [
                        'name' => session('name'),
                        'email' => session('email'),
                        'role' => session('role'),
                    ],
                    'enrollments' => array_values($activeEnrollments), // Only active enrollments
                    'pendingEnrollments' => array_values($pendingEnrollments), // Pending enrollments
                    'rejectedEnrollments' => array_values($rejectedEnrollments), // Rejected enrollments
                    'available_courses' => $available_courses,
                    'overall_progress' => $overall_progress,
                    'unread_count' => 0
                ]);
                
            default:
                session()->setFlashdata('error', 'Your account role is not recognized.');
                return redirect()->to('login');
        }
    }
}
