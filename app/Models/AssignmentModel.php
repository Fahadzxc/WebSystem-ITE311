<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentModel extends Model
{
    protected $table = 'assignments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'course_id',
        'teacher_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'course_id' => 'required|integer',
        'teacher_id' => 'required|integer',
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty',
        'due_date' => 'permit_empty',
        'max_score' => 'permit_empty|decimal',
        'status' => 'in_list[draft,published,closed]'
    ];
    
    protected $skipValidation = false;

    /**
     * Get assignments with course details
     */
    public function getAssignmentsWithCourse()
    {
        return $this->select('assignments.*, courses.title as course_title, courses.description as course_description')
                    ->join('courses', 'courses.id = assignments.course_id')
                    ->orderBy('assignments.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get assignments for a specific course
     */
    public function getAssignmentsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get assignments created by a teacher
     */
    public function getAssignmentsByTeacher($teacher_id)
    {
        return $this->select('assignments.*, courses.title as course_title')
                    ->join('courses', 'courses.id = assignments.course_id')
                    ->where('assignments.teacher_id', $teacher_id)
                    ->orderBy('assignments.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get assignments for enrolled students
     */
    public function getAssignmentsForStudent($student_id)
    {
        return $this->select('assignments.*, courses.title as course_title, courses.description as course_description')
                    ->join('courses', 'courses.id = assignments.course_id')
                    ->join('enrollments', 'enrollments.course_id = courses.id AND enrollments.user_id = ' . (int)$student_id)
                    ->where('assignments.status', 'published')
                    ->groupBy('assignments.id')
                    ->orderBy('assignments.due_date', 'ASC')
                    ->orderBy('assignments.created_at', 'DESC')
                    ->findAll();
    }
}

