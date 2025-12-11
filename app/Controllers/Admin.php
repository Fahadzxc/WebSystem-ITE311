<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\CourseScheduleModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;

class Admin extends BaseController
{ 
    protected $userModel;
    protected $courseModel;
    protected $scheduleModel;
    protected $enrollmentModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->scheduleModel = new CourseScheduleModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Check if user is logged in and is admin
     */
    private function checkAdminAuth()
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Please Login first.');
            return redirect()->to(base_url('login'));
        }

        if (strtolower(session('role')) !== 'admin') {
            session()->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to(base_url('dashboard'));
        }

        return true;
    }

    public function dashboard()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        // Render unified wrapper with user context
        return view('auth/dashboard', [
            'user' => [
                'name'  => session('name'),
                'email' => session('email'),
                'role'  => session('role'),
            ]
        ]);
    }

    /**
     * User Management - List all users
     * Redirects to dashboard where User Management is now integrated
     */
    public function users()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        // Redirect to dashboard where User Management is now integrated
        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Create new user
     */
    public function createUser()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($this->request->getMethod() === 'POST') {
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $role = $this->request->getPost('role');

            // Validate role is provided and is valid
            if (empty($role)) {
                session()->setFlashdata('error', 'Please select a role.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate role is one of the allowed values
            $allowedRoles = ['student', 'teacher', 'admin'];
            if (!in_array(strtolower($role), $allowedRoles)) {
                session()->setFlashdata('error', 'Invalid role selected.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate name - only letters and spaces allowed
            if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
                session()->setFlashdata('error', 'Name contains invalid characters.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate email - must be valid email format and @gmail.com
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('dashboard'));
            }

            // Check if email contains invalid special characters
            if (preg_match('/[\/\'"\\\;\<\>]/', $email)) {
                session()->setFlashdata('error', 'Invalid email format.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate email must be @gmail.com
            if (strpos($email, '@gmail.com') === false) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('dashboard'));
            }

            // Check if email already exists
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                session()->setFlashdata('error', 'Email already exists.');
                return redirect()->to(base_url('dashboard'));
            }

            // Create user - ensure role is lowercase and valid
            $data = [
                'name' => trim($name),
                'email' => trim($email),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => strtolower(trim($role)) // Ensure role is lowercase
            ];

            // Debug: Log the data being inserted
            log_message('debug', 'Creating user with role: ' . $data['role']);

            $insertedId = $this->userModel->insert($data);
            if ($insertedId) {
                // Verify the role was saved correctly
                $createdUser = $this->userModel->find($insertedId);
                if ($createdUser && $createdUser['role'] !== $data['role']) {
                    log_message('error', 'Role mismatch! Requested: ' . $data['role'] . ', Saved: ' . ($createdUser['role'] ?? 'null'));
                    session()->setFlashdata('error', 'User created but role may not have been saved correctly.');
                } else {
                    session()->setFlashdata('success', 'User created successfully with role: ' . ucfirst($data['role']) . '.');
                }
            } else {
                $errors = $this->userModel->errors();
                $errorMsg = 'Failed to create user.';
                if (!empty($errors)) {
                    $errorMsg .= ' ' . implode(', ', $errors);
                }
                session()->setFlashdata('error', $errorMsg);
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Update user
     */
    public function updateUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($this->request->getMethod() === 'POST' && $id) {
            // Don't allow editing yourself
            if ($id == session('user_id')) {
                session()->setFlashdata('error', 'You cannot edit your own account.');
                return redirect()->to(base_url('dashboard'));
            }

            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $role = $this->request->getPost('role');
            $password = $this->request->getPost('password');

            // Validate name - only letters and spaces allowed
            if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
                session()->setFlashdata('error', 'Name contains invalid characters.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate email - must be valid email format and @gmail.com
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('dashboard'));
            }

            // Check if email contains invalid special characters
            if (preg_match('/[\/\'"\\\;\<\>]/', $email)) {
                session()->setFlashdata('error', 'Invalid email format.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate email must be @gmail.com
            if (strpos($email, '@gmail.com') === false) {
                session()->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->to(base_url('dashboard'));
            }

            // Check if email already exists for other users
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                session()->setFlashdata('error', 'Email already exists for another user.');
                return redirect()->to(base_url('dashboard'));
            }

            // Prepare update data
            $data = [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];

            // Only update password if provided
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User updated successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to update user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Delete user (soft delete - mark as deleted)
     */
    public function deleteUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($id) {
            // Don't allow deleting yourself
            if ($id == session('user_id')) {
                session()->setFlashdata('error', 'You cannot delete your own account.');
                return redirect()->to(base_url('dashboard'));
            }

            // Soft delete - mark as deleted instead of actually deleting
            $data = [
                'is_deleted' => 1,
                'deleted_at' => date('Y-m-d H:i:s')
            ];

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User marked as deleted successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to delete user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Restore deleted user
     */
    public function restoreUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($id) {
            $data = [
                'is_deleted' => 0,
                'deleted_at' => null
            ];

            if ($this->userModel->update($id, $data)) {
                session()->setFlashdata('success', 'User restored successfully.');
            } else {
                session()->setFlashdata('error', 'Failed to restore user.');
            }
        }

        return redirect()->to(base_url('admin/users'));
    }

    /**
     * Get user by ID (AJAX)
     */
    public function getUser($id = null)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        if ($id) {
            $user = $this->userModel->find($id);
            if ($user) {
                // Don't send password
                unset($user['password']);
                return $this->response->setJSON(['success' => true, 'user' => $user]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
    }

    /**
     * Cleanup expired enrollments (4 months old)
     * Can be called manually or via cron job
     */
    public function cleanupExpiredEnrollments()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $enrollmentModel = new \App\Models\EnrollmentModel();
        $removedCount = $enrollmentModel->removeExpiredEnrollments();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => "Removed {$removedCount} expired enrollment(s).",
                'count' => $removedCount
            ]);
        }

        session()->setFlashdata('success', "Removed {$removedCount} expired enrollment(s).");
        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Get schedules for a course
     */
    public function getSchedules()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON(['schedules' => []]);
        }

        $course_id = $this->request->getGet('course_id');
        if (!$course_id) {
            return $this->response->setJSON(['schedules' => []]);
        }

        $schedules = $this->scheduleModel->getSchedulesByCourse($course_id);
        $course = $this->courseModel->find($course_id);
        
        return $this->response->setJSON([
            'schedules' => $schedules,
            'course' => [
                'semester' => $course['semester'] ?? null,
                'academic_year' => $course['academic_year'] ?? null,
                'max_students' => $course['max_students'] ?? null
            ]
        ]);
    }

    /**
     * Check for schedule conflicts
     */
    public function checkConflict()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON(['has_conflict' => false, 'message' => 'Unauthorized']);
        }

        if ($this->request->getMethod() === 'POST') {
            $json = $this->request->getJSON(true);
            $teacher_id = $json['teacher_id'] ?? null;
            $schedules = $json['schedules'] ?? [];
            $course_id = $json['course_id'] ?? null;

            if (!$teacher_id || empty($schedules)) {
                return $this->response->setJSON(['has_conflict' => false]);
            }

            // Check each schedule for conflicts
            foreach ($schedules as $schedule) {
                $day_of_week = $schedule['day_of_week'] ?? null;
                $start_time = $schedule['start_time'] ?? null;
                $end_time = $schedule['end_time'] ?? null;

                if (!$day_of_week || !$start_time || !$end_time) {
                    continue;
                }

                // Check if end time is after start time
                if ($end_time <= $start_time) {
                    return $this->response->setJSON([
                        'has_conflict' => true,
                        'message' => 'End time must be after start time for all schedules.'
                    ]);
                }

                // Get existing schedules for this teacher on this day
                $existingSchedules = $this->scheduleModel->getSchedulesByTeacherAndDay($teacher_id, $day_of_week, $course_id);

                // Check for time overlaps
                foreach ($existingSchedules as $existingSchedule) {
                    // Conflict if: new_start < existing_end AND new_end > existing_start
                    if ($start_time < $existingSchedule['end_time'] && $end_time > $existingSchedule['start_time']) {
                        $conflictCourse = $this->courseModel->find($existingSchedule['course_id']);
                        return $this->response->setJSON([
                            'has_conflict' => true,
                            'message' => "Schedule conflict! This teacher already has '{$conflictCourse['title']}' scheduled at " . 
                                        date('g:i A', strtotime($existingSchedule['start_time'])) . " - " . 
                                        date('g:i A', strtotime($existingSchedule['end_time'])) . " on {$day_of_week}."
                        ]);
                    }
                }
            }

            return $this->response->setJSON(['has_conflict' => false]);
        }

        return $this->response->setJSON(['has_conflict' => false]);
    }

    /**
     * Assign teacher to course with multiple schedules
     */
    public function assignTeacher()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($this->request->getMethod() === 'POST') {
            $course_id = $this->request->getPost('course_id');
            $teacher_id = $this->request->getPost('teacher_id');
            $schedulesJson = $this->request->getPost('schedules');

            if (!$course_id) {
                session()->setFlashdata('error', 'Course ID is required.');
                return redirect()->to(base_url('dashboard'));
            }

            // Validate course exists
            $course = $this->courseModel->find($course_id);
            if (!$course) {
                session()->setFlashdata('error', 'Course not found.');
                return redirect()->to(base_url('dashboard'));
            }

            // If teacher_id is provided, validate teacher exists and is a teacher
            if ($teacher_id) {
                $teacher = $this->userModel->find($teacher_id);
                if (!$teacher) {
                    session()->setFlashdata('error', 'Teacher not found.');
                    return redirect()->to(base_url('dashboard'));
                }

                if (strtolower($teacher['role']) !== 'teacher') {
                    session()->setFlashdata('error', 'Selected user is not a teacher.');
                    return redirect()->to(base_url('dashboard'));
                }

                // Check if teacher is deleted
                if (isset($teacher['is_deleted']) && $teacher['is_deleted'] == 1) {
                    session()->setFlashdata('error', 'Cannot assign a deleted teacher.');
                    return redirect()->to(base_url('dashboard'));
                }

                // Parse schedules
                $schedules = json_decode($schedulesJson, true);
                if (empty($schedules) || !is_array($schedules)) {
                    session()->setFlashdata('error', 'Please provide at least one schedule.');
                    return redirect()->to(base_url('dashboard'));
                }

                // Validate all schedules
                foreach ($schedules as $schedule) {
                    $day_of_week = $schedule['day_of_week'] ?? null;
                    $start_time = $schedule['start_time'] ?? null;
                    $end_time = $schedule['end_time'] ?? null;

                    if (empty($day_of_week) || empty($start_time) || empty($end_time)) {
                        session()->setFlashdata('error', 'All schedules must have day, start time, and end time.');
                        return redirect()->to(base_url('dashboard'));
                    }

                    // Validate end time is after start time
                    if ($end_time <= $start_time) {
                        session()->setFlashdata('error', 'End time must be after start time for all schedules.');
                        return redirect()->to(base_url('dashboard'));
                    }
                }

                // Check for schedule conflicts
                foreach ($schedules as $schedule) {
                    $existingSchedules = $this->scheduleModel->getSchedulesByTeacherAndDay($teacher_id, $schedule['day_of_week'], $course_id);

                    foreach ($existingSchedules as $existingSchedule) {
                        if ($schedule['start_time'] < $existingSchedule['end_time'] && $schedule['end_time'] > $existingSchedule['start_time']) {
                            $conflictCourse = $this->courseModel->find($existingSchedule['course_id']);
                            session()->setFlashdata('error', "Schedule conflict! This teacher already has '{$conflictCourse['title']}' scheduled at " . 
                                        date('g:i A', strtotime($existingSchedule['start_time'])) . " - " . 
                                        date('g:i A', strtotime($existingSchedule['end_time'])) . " on {$schedule['day_of_week']}.");
                            return redirect()->to(base_url('dashboard'));
                        }
                    }
                }

                // Get semester and academic year from POST
                $semester = $this->request->getPost('semester');
                $academic_year = $this->request->getPost('academic_year');

                // Validate semester and academic year
                if (empty($semester)) {
                    session()->setFlashdata('error', 'Please select a semester/term.');
                    return redirect()->to(base_url('dashboard'));
                }

                if (empty($academic_year)) {
                    session()->setFlashdata('error', 'Please enter an academic year.');
                    return redirect()->to(base_url('dashboard'));
                }

                // Validate academic year format (YYYY-YYYY) - still validate even if dropdown
                if (!preg_match('/^\d{4}-\d{4}$/', $academic_year)) {
                    session()->setFlashdata('error', 'Please select a valid academic year.');
                    return redirect()->to(base_url('dashboard'));
                }

                // Get max_students from POST
                $max_students = $this->request->getPost('max_students');
                $max_students = !empty($max_students) && $max_students > 0 ? (int)$max_students : null;

                // Assign teacher to course with semester, academic year, and max_students
                $updateData = [
                    'instructor_id' => $teacher_id,
                    'semester' => $semester,
                    'academic_year' => $academic_year
                ];
                
                if ($max_students !== null) {
                    $updateData['max_students'] = $max_students;
                } else {
                    $updateData['max_students'] = null;
                }
                
                $this->courseModel->update($course_id, $updateData);

                // Delete existing schedules for this course
                $this->scheduleModel->deleteByCourse($course_id);

                // Insert new schedules
                foreach ($schedules as $schedule) {
                    $this->scheduleModel->insert([
                        'course_id' => $course_id,
                        'day_of_week' => $schedule['day_of_week'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time']
                    ]);
                }

                session()->setFlashdata('success', 'Teacher assigned to course with schedule(s) successfully.');
            } else {
                // Remove teacher assignment (set to 0 for unassigned, clear semester/term)
                $this->courseModel->update($course_id, [
                    'instructor_id' => 0,
                    'semester' => null,
                    'academic_year' => null
                ]);
                $this->scheduleModel->deleteByCourse($course_id);
                session()->setFlashdata('success', 'Teacher assignment removed successfully.');
            }
        }

        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Create new course
     */
    public function createCourse()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) return $auth;

        if ($this->request->getMethod() === 'POST') {
            $title = $this->request->getPost('title');
            $description = $this->request->getPost('description');
            $category = $this->request->getPost('category');
            $level = $this->request->getPost('level');
            $status = $this->request->getPost('status') ?? 'draft';

            // Validate required fields
            if (empty($title) || empty($description)) {
                session()->setFlashdata('error', 'Course title and description are required.');
                return redirect()->to(base_url('dashboard'));
            }

            // Check for duplicate course title (case-insensitive)
            if ($this->courseModel->isTitleExists($title)) {
                session()->setFlashdata('error', 'A course with this title already exists. Please use a different title.');
                return redirect()->to(base_url('dashboard'));
            }

            // Prepare course data
            // Note: instructor_id is required by database, so we'll use 0 to represent "unassigned"
            // This can be updated later when a teacher is assigned
            $courseData = [
                'title' => $title,
                'description' => $description,
                'category' => $category ?? null,
                'level' => $level ?? 'beginner',
                'status' => $status,
                'instructor_id' => 0 // 0 represents unassigned (will be updated when teacher is assigned)
            ];

            // Insert course (skip validation for fields we don't need)
            try {
                $courseId = $this->courseModel->insert($courseData);
                if ($courseId) {
                    session()->setFlashdata('success', 'Course created successfully! You can now assign a teacher to this course.');
                } else {
                    $errors = $this->courseModel->errors();
                    $errorMsg = 'Failed to create course.';
                    if (!empty($errors)) {
                        $errorMsg .= ' ' . implode(', ', $errors);
                    }
                    session()->setFlashdata('error', $errorMsg);
                }
            } catch (\Exception $e) {
                log_message('error', 'Course creation failed: ' . $e->getMessage());
                session()->setFlashdata('error', 'Failed to create course: ' . $e->getMessage());
            }
        }

        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Get students enrolled in courses taught by a teacher
     */
    public function getTeacherStudents($teacher_id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(403);
        }

        // Get all courses taught by this teacher
        $courses = $this->courseModel->where('instructor_id', $teacher_id)->findAll();
        $courseIds = array_column($courses, 'id');

        if (empty($courseIds)) {
            return $this->response->setJSON([
                'success' => true,
                'students' => []
            ]);
        }

        // Get all enrollments for these courses
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $enrollments = $enrollmentModel->select('enrollments.*, users.name as student_name, users.email as student_email, courses.title as course_title')
                                      ->join('users', 'users.id = enrollments.user_id')
                                      ->join('courses', 'courses.id = enrollments.course_id')
                                      ->whereIn('enrollments.course_id', $courseIds)
                                      ->where('enrollments.status', 'active')
                                      ->orderBy('users.name', 'ASC')
                                      ->findAll();

        // Group students by student (remove duplicates)
        $studentsMap = [];
        foreach ($enrollments as $enrollment) {
            $studentId = $enrollment['user_id'];
            if (!isset($studentsMap[$studentId])) {
                $studentsMap[$studentId] = [
                    'id' => $studentId,
                    'name' => $enrollment['student_name'],
                    'email' => $enrollment['student_email'],
                    'courses' => []
                ];
            }
            $studentsMap[$studentId]['courses'][] = $enrollment['course_title'];
        }

        return $this->response->setJSON([
            'success' => true,
            'students' => array_values($studentsMap)
        ]);
    }

    /**
     * Enroll a student in a course (Admin)
     */
    public function enrollStudent()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(401);
        }

        $student_id = $this->request->getPost('student_id');
        $course_id = $this->request->getPost('course_id');

        if (!$student_id || !$course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student ID and Course ID are required.'
            ])->setStatusCode(400);
        }

        // Verify course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Verify student exists and is a student
        $student = $this->userModel->find($student_id);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid student.'
            ])->setStatusCode(400);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($student_id, $course_id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student is already enrolled in this course.'
            ])->setStatusCode(400);
        }

        // Enroll student
        $enrollmentData = [
            'user_id' => $student_id,
            'course_id' => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'active',
            'progress' => 0.00
        ];

        $enrollmentId = $this->enrollmentModel->enrollUser($enrollmentData);

        if ($enrollmentId) {
            // Get student name for notifications
            $studentName = $student['name'];

            // Create notification for student
            try {
                $message = "You have been enrolled in '{$course['title']}' by the administrator.";
                $this->notificationModel->createNotification($student_id, $message);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create enrollment notification: ' . $e->getMessage());
            }

            // Notify teacher if assigned
            if (!empty($course['instructor_id'])) {
                try {
                    $teacherMessage = "New enrollment: {$studentName} has been enrolled in '{$course['title']}' by the administrator.";
                    $this->notificationModel->createNotification($course['instructor_id'], $teacherMessage);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to create teacher notification: ' . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student enrolled successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to enroll student. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Unenroll a student from a course (Admin)
     */
    public function unenrollStudent()
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(401);
        }

        $enrollment_id = $this->request->getPost('enrollment_id');
        $student_id = $this->request->getPost('student_id');
        $course_id = $this->request->getPost('course_id');

        if (!$enrollment_id || !$student_id || !$course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment ID, Student ID, and Course ID are required.'
            ])->setStatusCode(400);
        }

        // Get enrollment
        $enrollment = $this->enrollmentModel->find($enrollment_id);
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment not found.'
            ])->setStatusCode(404);
        }

        // Verify enrollment belongs to the specified student and course
        if ($enrollment['user_id'] != $student_id || $enrollment['course_id'] != $course_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment mismatch.'
            ])->setStatusCode(400);
        }

        // Get course
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Update enrollment status to 'dropped'
        $updated = $this->enrollmentModel->update($enrollment_id, [
            'status' => 'dropped'
        ]);

        if ($updated) {
            // Get student name for notification
            $student = $this->userModel->find($student_id);
            $studentName = $student ? $student['name'] : 'Student';

            // Create notification for student
            try {
                $message = "You have been unenrolled from '{$course['title']}' by the administrator.";
                $this->notificationModel->createNotification($student_id, $message);
            } catch (\Exception $e) {
                log_message('error', 'Failed to create unenrollment notification: ' . $e->getMessage());
            }

            // Notify teacher if assigned
            if (!empty($course['instructor_id'])) {
                try {
                    $teacherMessage = "Unenrollment: {$studentName} has been unenrolled from '{$course['title']}' by the administrator.";
                    $this->notificationModel->createNotification($course['instructor_id'], $teacherMessage);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to create teacher notification: ' . $e->getMessage());
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student unenrolled successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to unenroll student. Please try again.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Get students for a course (Admin)
     */
    public function getCourseStudents($course_id)
    {
        $auth = $this->checkAdminAuth();
        if ($auth !== true) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(401);
        }

        // Verify course exists
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        // Get all students
        $allStudents = $this->userModel->where('role', 'student')
                                       ->where('is_deleted', 0)
                                       ->orderBy('name', 'ASC')
                                       ->findAll();

        // Get enrolled students for this course
        $enrollments = $this->enrollmentModel->getCourseEnrollments($course_id);
        $enrolledStudentIds = array_column($enrollments, 'user_id');

        // Prepare student list with enrollment status
        $students = [];
        foreach ($allStudents as $student) {
            $isEnrolled = in_array($student['id'], $enrolledStudentIds);
            $enrollment = null;
            if ($isEnrolled) {
                foreach ($enrollments as $enr) {
                    if ($enr['user_id'] == $student['id']) {
                        $enrollment = $enr;
                        break;
                    }
                }
            }

            $students[] = [
                'id' => $student['id'],
                'name' => $student['name'],
                'email' => $student['email'],
                'is_enrolled' => $isEnrolled,
                'enrollment' => $enrollment
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'students' => $students,
            'course' => $course
        ]);
    }
}

