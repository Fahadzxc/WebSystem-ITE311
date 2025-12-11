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
                $all_courses = [];
                
                if (!empty($excluded_course_ids)) {
                    $all_courses = $courseModel->whereNotIn('id', $excluded_course_ids)->findAll();
                } else {
                    $all_courses = $courseModel->findAll();
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
                
                // Add instructor names to available courses
                $userModel = new \App\Models\UserModel();
                foreach ($available_courses as &$course) {
                    if (!empty($course['instructor_id']) && $course['instructor_id'] != 0) {
                        $instructor = $userModel->find($course['instructor_id']);
                        if ($instructor && strtolower($instructor['role']) === 'teacher') {
                            $course['instructor_name'] = $instructor['name'];
                        } else {
                            $course['instructor_name'] = 'Unassigned';
                        }
                    } else {
                        $course['instructor_name'] = 'TBA';
                    }
                }
                
                // Add instructor names to unavailable courses
                foreach ($unavailable_courses as &$course) {
                    if (!empty($course['instructor_id']) && $course['instructor_id'] != 0) {
                        $instructor = $userModel->find($course['instructor_id']);
                        if ($instructor && strtolower($instructor['role']) === 'teacher') {
                            $course['instructor_name'] = $instructor['name'];
                        } else {
                            $course['instructor_name'] = 'Unassigned';
                        }
                    } else {
                        $course['instructor_name'] = 'TBA';
                    }
                }
                
                // Calculate assignment completion percentage
                $assignmentModel = new \App\Models\AssignmentModel();
                $submissionModel = new \App\Models\AssignmentSubmissionModel();
                $assignment_completion = 0;
                if (!empty($enrolled_course_ids)) {
                    // Get all assignments for enrolled courses
                    $all_assignments = $assignmentModel
                        ->whereIn('course_id', $enrolled_course_ids)
                        ->findAll();
                    
                    $total_assignments = count($all_assignments);
                    
                    // Count how many have been submitted
                    $submitted_assignments = 0;
                    if ($total_assignments > 0) {
                        foreach ($all_assignments as $assignment) {
                            $hasSubmission = $submissionModel
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
                if (!empty($activeEnrollments)) {
                    $total_progress = array_sum(array_column($activeEnrollments, 'progress'));
                    $enrollment_progress = $total_progress / count($activeEnrollments);
                }

                // Get upcoming deadlines (assignments with due dates in the future that haven't been submitted)
                $upcoming_deadlines = [];
                if (!empty($enrolled_course_ids)) {
                    $assignmentModel = new \App\Models\AssignmentModel();
                    $submissionModel = new \App\Models\AssignmentSubmissionModel();
                    $now = date('Y-m-d H:i:s');
                    $assignments = $assignmentModel
                        ->select('assignments.*, courses.title as course_title')
                        ->join('courses', 'courses.id = assignments.course_id')
                        ->whereIn('assignments.course_id', $enrolled_course_ids)
                        ->where('assignments.due_date >', $now)
                        ->orderBy('assignments.due_date', 'ASC')
                        ->limit(10)
                        ->findAll();
                    
                    // Filter out assignments that have already been submitted
                    foreach ($assignments as $assignment) {
                        $hasSubmission = $submissionModel
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
                $submissionModel = new \App\Models\AssignmentSubmissionModel();
                $submissions = $submissionModel
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
                    'enrollments' => array_values($activeEnrollments), // Only active enrollments
                    'pendingEnrollments' => array_values($pendingEnrollments), // Pending enrollments
                    'rejectedEnrollments' => array_values($rejectedEnrollments), // Rejected enrollments
                    'available_courses' => $available_courses,
                    'unavailable_courses' => $unavailable_courses,
                    'overall_progress' => $assignment_completion, // Use assignment completion instead
                    'enrollment_progress' => $enrollment_progress, // Keep enrollment progress for reference
                    'upcoming_deadlines' => $upcoming_deadlines,
                    'recent_grades' => $recent_grades,
                    'unread_count' => 0
                ]);
                
            default:
                session()->setFlashdata('error', 'Your account role is not recognized.');
                return redirect()->to('login');
        }
    }
}
