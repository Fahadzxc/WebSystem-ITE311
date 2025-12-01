<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentSubmissionModel extends Model
{
    protected $table = 'assignment_submissions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'assignment_id',
        'student_id',
        'submission_text',
        'file_path',
        'file_name',
        'score',
        'feedback',
        'status',
        'submitted_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'assignment_id' => 'required|integer',
        'student_id' => 'required|integer',
        'submission_text' => 'permit_empty',
        'file_path' => 'permit_empty',
        'file_name' => 'permit_empty',
        'score' => 'permit_empty|decimal',
        'feedback' => 'permit_empty',
        'status' => 'in_list[submitted,graded,returned]'
    ];

    /**
     * Check if student has already submitted
     */
    public function hasSubmitted($assignment_id, $student_id)
    {
        return $this->where('assignment_id', $assignment_id)
                    ->where('student_id', $student_id)
                    ->first() !== null;
    }

    /**
     * Get submission by assignment and student
     */
    public function getSubmission($assignment_id, $student_id)
    {
        $result = $this->where('assignment_id', $assignment_id)
                    ->where('student_id', $student_id)
                    ->first();
        return $result ?: null; // Return null explicitly if not found
    }

    /**
     * Get all submissions for an assignment
     */
    public function getSubmissionsByAssignment($assignment_id)
    {
        return $this->select('assignment_submissions.*, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = assignment_submissions.student_id')
                    ->where('assignment_submissions.assignment_id', $assignment_id)
                    ->orderBy('assignment_submissions.submitted_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get submissions by student
     */
    public function getSubmissionsByStudent($student_id)
    {
        return $this->select('assignment_submissions.*, assignments.title as assignment_title, assignments.course_id, courses.title as course_title')
                    ->join('assignments', 'assignments.id = assignment_submissions.assignment_id')
                    ->join('courses', 'courses.id = assignments.course_id')
                    ->where('assignment_submissions.student_id', $student_id)
                    ->orderBy('assignment_submissions.submitted_at', 'DESC')
                    ->findAll();
    }
}

