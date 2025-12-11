<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CourseModel;
use App\Models\CourseScheduleModel;

class Admin extends BaseController
{ 
    protected $userModel;
    protected $courseModel;
    protected $scheduleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->scheduleModel = new CourseScheduleModel();
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
        return $this->response->setJSON(['schedules' => $schedules]);
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

                // Assign teacher to course
                $this->courseModel->update($course_id, ['instructor_id' => $teacher_id]);

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
                // Remove teacher assignment (set to 0 for unassigned)
                $this->courseModel->update($course_id, ['instructor_id' => 0]);
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
}

