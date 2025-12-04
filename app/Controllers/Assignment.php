<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AssignmentModel;
use App\Models\AssignmentSubmissionModel;
use App\Models\AssignmentQuestionModel;
use App\Models\AssignmentAnswerModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use App\Models\UserModel;

class Assignment extends BaseController
{
    protected $assignmentModel;
    protected $submissionModel;
    protected $questionModel;
    protected $answerModel;
    protected $courseModel;
    protected $enrollmentModel;
    protected $notificationModel;
    protected $userModel;

    public function __construct()
    {
        $this->assignmentModel = new AssignmentModel();
        $this->submissionModel = new AssignmentSubmissionModel();
        $this->questionModel = new AssignmentQuestionModel();
        $this->answerModel = new AssignmentAnswerModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        
        // Load form helper
        helper('form');
    }

    /**
     * Teacher: List all assignments
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $role = session()->get('role');
        $user_id = session()->get('user_id');

        if ($role === 'teacher') {
            $assignments = $this->assignmentModel->getAssignmentsByTeacher($user_id);
            return view('teacher/assignments', [
                'assignments' => $assignments
            ]);
        } elseif ($role === 'student') {
            $assignments = $this->assignmentModel->getAssignmentsForStudent($user_id);
            
            // Debug: Log if no assignments found
            if (empty($assignments)) {
                log_message('debug', 'Student ' . $user_id . ' has no assignments. Checking enrollments...');
                $enrollments = $this->enrollmentModel->where('user_id', $user_id)->findAll();
                log_message('debug', 'Student enrollments: ' . count($enrollments));
                $allAssignments = $this->assignmentModel->where('status', 'published')->findAll();
                log_message('debug', 'Total published assignments: ' . count($allAssignments));
            }
            
            return view('student/assignments', [
                'assignments' => $assignments
            ]);
        }

        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Teacher: Create new assignment
     */
    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->to(base_url('login'));
        }

        $courses = $this->courseModel->where('status', 'published')->findAll();

        // Check if POST request - SIMPLE CHECK
        $method = strtolower($this->request->getMethod());
        if ($method === 'post') {
            log_message('debug', '=== ASSIGNMENT CREATE - POST DETECTED ===');
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
            
            // Get form data
            $course_id = $this->request->getPost('course_id');
            $title = $this->request->getPost('title');
            $description = $this->request->getPost('description');
            $due_date = $this->request->getPost('due_date');
            $max_score = $this->request->getPost('max_score') ?: 100;
            $status = $this->request->getPost('status') ?: 'published';
            
            // Debug: Log received data
            log_message('debug', 'Course ID: ' . $course_id . ', Title: ' . $title);
            
            // Basic validation
            if (empty($course_id) || empty($title)) {
                $errorMsg = 'Please fill in Course and Title fields.';
                if (empty($course_id)) $errorMsg .= ' (Course is missing)';
                if (empty($title)) $errorMsg .= ' (Title is missing)';
                session()->setFlashdata('error', $errorMsg);
                return view('teacher/create_assignment', [
                    'courses' => $courses,
                    'validation' => \Config\Services::validation()
                ]);
            }
            
            // Get database connection
            $db = \Config\Database::connect();
            
            // Prepare data
            $now = date('Y-m-d H:i:s');
            $data = [
                'course_id' => (int)$course_id,
                'teacher_id' => (int)session()->get('user_id'),
                'title' => trim($title),
                'description' => !empty($description) ? trim($description) : null,
                'max_score' => (float)$max_score,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now
            ];
            
            // Handle due date
            if (!empty($due_date)) {
                $data['due_date'] = date('Y-m-d H:i:s', strtotime($due_date));
            } else {
                $data['due_date'] = null;
            }
            
            // Use Query Builder - SIMPLE DIRECT INSERT
            try {
                $builder = $db->table('assignments');
                $result = $builder->insert($data);
                
                if ($result) {
                    $assignment_id = $db->insertID();
                
                // Save questions if provided
                $questions = $this->request->getPost('questions');
                if (!empty($questions) && is_array($questions)) {
                    $qBuilder = $db->table('assignment_questions');
                    $order = 0;
                    foreach ($questions as $question) {
                        if (!empty($question['question_text']) && !empty($question['correct_answer'])) {
                            $qData = [
                                'assignment_id' => $assignment_id,
                                'question_text' => trim($question['question_text']),
                                'option_a' => trim($question['option_a'] ?? ''),
                                'option_b' => trim($question['option_b'] ?? ''),
                                'option_c' => trim($question['option_c'] ?? ''),
                                'option_d' => trim($question['option_d'] ?? ''),
                                'correct_answer' => trim($question['correct_answer']),
                                'points' => (float)($question['points'] ?? 1),
                                'order' => $order,
                                'created_at' => $now,
                                'updated_at' => $now
                            ];
                            $qBuilder->insert($qData);
                            $order++;
                        }
                    }
                }
                
                // Notify students
                $this->notifyStudentsAboutAssignment($course_id, $assignment_id);
                
                    session()->setFlashdata('success', 'Assignment created successfully! ID: ' . $assignment_id);
                    return redirect()->to(base_url('assignment'));
                } else {
                    $error = $db->error();
                    $errorMsg = 'Failed to insert assignment. ';
                    if (!empty($error['message'])) {
                        $errorMsg .= 'Error: ' . $error['message'];
                    } else {
                        $errorMsg .= 'Please check database connection and table structure.';
                    }
                    session()->setFlashdata('error', $errorMsg);
                    log_message('error', 'Assignment insert failed. Data: ' . json_encode($data) . ' Error: ' . json_encode($error));
                }
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Exception: ' . $e->getMessage());
                log_message('error', 'Assignment creation exception: ' . $e->getMessage());
            }
        }

        return view('teacher/create_assignment', [
            'courses' => $courses,
            'validation' => \Config\Services::validation()
        ]);
    }

    /**
     * Teacher: View assignment details and submissions
     */
    public function view($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to(base_url('assignment'));
        }

        $role = session()->get('role');
        $user_id = session()->get('user_id');

        if ($role === 'teacher') {
            // Check if teacher owns this assignment
            if ($assignment['teacher_id'] != $user_id) {
                session()->setFlashdata('error', 'Access denied.');
                return redirect()->to(base_url('assignment'));
            }

            $submissions = $this->submissionModel->getSubmissionsByAssignment($id);
            $course = $this->courseModel->find($assignment['course_id']);
            $questions = $this->questionModel->getQuestionsByAssignment($id);

            return view('teacher/view_assignment', [
                'assignment' => $assignment,
                'course' => $course,
                'submissions' => $submissions,
                'questions' => $questions
            ]);
        } elseif ($role === 'student') {
            // Check if student is enrolled in the course
            $isEnrolled = $this->enrollmentModel->isAlreadyEnrolled($user_id, $assignment['course_id']);
            if (!$isEnrolled) {
                session()->setFlashdata('error', 'You are not enrolled in this course.');
                return redirect()->to(base_url('assignment'));
            }

            $submission = $this->submissionModel->getSubmission($id, $user_id);
            $course = $this->courseModel->find($assignment['course_id']);
            $questions = $this->questionModel->getQuestionsByAssignment($id);
            
            // Get answers if submitted
            $answers = [];
            if ($submission) {
                $answerData = $this->answerModel->getAnswersBySubmission($submission['id']);
                foreach ($answerData as $answer) {
                    $answers[$answer['question_id']] = $answer;
                }
            }

            return view('student/view_assignment', [
                'assignment' => $assignment,
                'course' => $course,
                'submission' => $submission,
                'questions' => $questions,
                'answers' => $answers
            ]);
        }

        return redirect()->to(base_url('dashboard'));
    }

    /**
     * Student: Submit assignment
     */
    public function submit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'student') {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->to(base_url('login'));
        }

        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to(base_url('assignment'));
        }

        $student_id = session()->get('user_id');

        // Check if already submitted
        if ($this->submissionModel->hasSubmitted($id, $student_id)) {
            session()->setFlashdata('error', 'You have already submitted this assignment.');
            return redirect()->to(base_url('assignment/view/' . $id));
        }

        // Check if enrolled
        if (!$this->enrollmentModel->isAlreadyEnrolled($student_id, $assignment['course_id'])) {
            session()->setFlashdata('error', 'You are not enrolled in this course.');
            return redirect()->to(base_url('assignment'));
        }

        // Check if POST request
        $method = strtolower($this->request->getMethod());
        if ($method === 'post') {
            log_message('debug', '=== ASSIGNMENT SUBMIT - POST DETECTED ===');
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
            
            $submission_text = $this->request->getPost('submission_text');
            $file = $this->request->getFile('submission_file');
            $answers = $this->request->getPost('answers') ?? [];

            // Get database connection
            $db = \Config\Database::connect();
            $now = date('Y-m-d H:i:s');
            
            $data = [
                'assignment_id' => (int)$id,
                'student_id' => (int)$student_id,
                'submission_text' => !empty($submission_text) ? trim($submission_text) : null,
                'status' => 'submitted',
                'submitted_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ];

            // Handle file upload
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/assignments', $newName);
                $data['file_path'] = 'uploads/assignments/' . $newName;
                $data['file_name'] = $file->getName();
            }

            // Insert submission using Query Builder
            $builder = $db->table('assignment_submissions');
            if ($builder->insert($data)) {
                $submission_id = $db->insertID();
                log_message('debug', 'Submission inserted. ID: ' . $submission_id);
                
                // Auto-grade multiple choice questions
                $questions = $this->questionModel->getQuestionsByAssignment($id);
                $scoreData = null;
                if (!empty($questions) && !empty($answers)) {
                    $scoreData = $this->answerModel->saveAnswers($submission_id, $answers, $questions);
                    
                    // Update submission with score
                    $builder->where('id', $submission_id)->update([
                        'score' => $scoreData['earned_points'],
                        'status' => 'graded',
                        'updated_at' => $now
                    ]);
                }
                
                // Notify teacher
                $this->notifyTeacherAboutSubmission($assignment['teacher_id'], $id, $student_id);
                
                $successMsg = 'Assignment submitted successfully!';
                if ($scoreData) {
                    $successMsg .= ' Your score: ' . number_format($scoreData['earned_points'], 2) . ' / ' . number_format($assignment['max_score'], 2);
                }
                session()->setFlashdata('success', $successMsg);
                return redirect()->to(base_url('assignment/view/' . $id));
            } else {
                $error = $db->error();
                $errorMsg = 'Failed to submit assignment. ';
                if (!empty($error['message'])) {
                    $errorMsg .= 'Error: ' . $error['message'];
                }
                session()->setFlashdata('error', $errorMsg);
                log_message('error', 'Submission insert failed. Data: ' . json_encode($data) . ' Error: ' . json_encode($error));
            }
        } else {
            log_message('debug', 'Submit method is not POST. Method: ' . $method);
        }

        return redirect()->to(base_url('assignment/view/' . $id));
    }

    /**
     * View submission details (Teacher only)
     */
    public function viewSubmission($submission_id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $role = session()->get('role');
        $user_id = session()->get('user_id');

        // Only teachers can view submissions
        if ($role !== 'teacher') {
            session()->setFlashdata('error', 'Access denied. Teacher only.');
            return redirect()->to(base_url('assignment'));
        }

        // Get submission with student info
        $submission = $this->submissionModel->select('assignment_submissions.*, users.name as student_name, users.email as student_email')
                                           ->join('users', 'users.id = assignment_submissions.student_id')
                                           ->where('assignment_submissions.id', $submission_id)
                                           ->first();

        if (!$submission) {
            session()->setFlashdata('error', 'Submission not found.');
            return redirect()->to(base_url('assignment'));
        }

        // Get assignment details
        $assignment = $this->assignmentModel->find($submission['assignment_id']);
        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to(base_url('assignment'));
        }

        // Check if teacher owns this assignment
        if ($assignment['teacher_id'] != $user_id) {
            session()->setFlashdata('error', 'Access denied. You do not own this assignment.');
            return redirect()->to(base_url('assignment'));
        }

        // Get course info
        $course = $this->courseModel->find($assignment['course_id']);

        // Get questions and answers
        $questions = $this->questionModel->getQuestionsByAssignment($assignment['id']);
        $answers = [];
        if ($submission) {
            $answerData = $this->answerModel->getAnswersBySubmission($submission['id']);
            foreach ($answerData as $answer) {
                $answers[$answer['question_id']] = $answer;
            }
        }

        return view('teacher/view_submission', [
            'assignment' => $assignment,
            'submission' => $submission,
            'course' => $course,
            'questions' => $questions,
            'answers' => $answers
        ]);
    }

    /**
     * Download submission file
     */
    public function download($submission_id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $submission = $this->submissionModel->find($submission_id);
        if (!$submission || !$submission['file_path']) {
            session()->setFlashdata('error', 'File not found.');
            return redirect()->back();
        }

        $role = session()->get('role');
        $user_id = session()->get('user_id');

        // Check access: student can download their own, teacher can download any
        if ($role === 'student' && $submission['student_id'] != $user_id) {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->back();
        }

        $filePath = WRITEPATH . $submission['file_path'];
        if (!file_exists($filePath)) {
            session()->setFlashdata('error', 'File not found.');
            return redirect()->back();
        }

        return $this->response->download($filePath, null);
    }

    /**
     * Teacher: Grade submission
     */
    public function grade($submission_id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            session()->setFlashdata('error', 'Access denied. Teacher only.');
            return redirect()->to(base_url('assignment'));
        }

        $user_id = session()->get('user_id');

        // Get submission
        $submission = $this->submissionModel->find($submission_id);
        if (!$submission) {
            session()->setFlashdata('error', 'Submission not found.');
            return redirect()->to(base_url('assignment'));
        }

        // Get assignment
        $assignment = $this->assignmentModel->find($submission['assignment_id']);
        if (!$assignment) {
            session()->setFlashdata('error', 'Assignment not found.');
            return redirect()->to(base_url('assignment'));
        }

        // Check if teacher owns this assignment
        if ($assignment['teacher_id'] != $user_id) {
            session()->setFlashdata('error', 'Access denied. You do not own this assignment.');
            return redirect()->to(base_url('assignment'));
        }

        if ($this->request->getMethod() === 'POST') {
            $score = $this->request->getPost('score');
            $feedback = $this->request->getPost('feedback');

            // Validate score
            if ($score < 0 || $score > $assignment['max_score']) {
                session()->setFlashdata('error', 'Score must be between 0 and ' . $assignment['max_score']);
                return redirect()->to(base_url('assignment/submission/' . $submission_id));
            }

            // Update submission
            $data = [
                'score' => $score,
                'feedback' => $feedback ?: null,
                'status' => 'graded'
            ];

            if ($this->submissionModel->update($submission_id, $data)) {
                session()->setFlashdata('success', 'Submission graded successfully.');
                return redirect()->to(base_url('assignment/view/' . $assignment['id']));
            } else {
                session()->setFlashdata('error', 'Failed to grade submission.');
            }
        }

        return redirect()->to(base_url('assignment/submission/' . $submission_id));
    }

    /**
     * Teacher: Delete assignment (only if no submissions)
     */
    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(403);
        }

        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Assignment not found.'
            ])->setStatusCode(404);
        }

        // Check if teacher owns this assignment
        if ($assignment['teacher_id'] != session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied.'
            ])->setStatusCode(403);
        }

        // Check if there are submissions
        $submissions = $this->submissionModel->where('assignment_id', $id)->findAll();
        if (!empty($submissions)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete assignment with existing submissions.'
            ])->setStatusCode(400);
        }

        if ($this->assignmentModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Assignment deleted successfully.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to delete assignment.'
        ])->setStatusCode(500);
    }

    /**
     * Notify all enrolled students about new assignment
     */
    private function notifyStudentsAboutAssignment($course_id, $assignment_id)
    {
        $assignment = $this->assignmentModel->find($assignment_id);
        $enrollments = $this->enrollmentModel->where('course_id', $course_id)
                                             ->where('status', 'active')
                                             ->findAll();

        foreach ($enrollments as $enrollment) {
            $message = "New assignment: {$assignment['title']} has been posted.";
            $this->notificationModel->createNotification($enrollment['user_id'], $message);
        }
    }

    /**
     * Notify teacher about student submission
     */
    private function notifyTeacherAboutSubmission($teacher_id, $assignment_id, $student_id)
    {
        $assignment = $this->assignmentModel->find($assignment_id);
        $student = $this->userModel->find($student_id);
        
        $message = "{$student['name']} submitted assignment: {$assignment['title']}";
        $this->notificationModel->createNotification($teacher_id, $message);
    }
}

