<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentQuestionModel extends Model
{
    protected $table = 'assignment_questions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'assignment_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'points',
        'order'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get questions for an assignment
     */
    public function getQuestionsByAssignment($assignment_id)
    {
        return $this->where('assignment_id', $assignment_id)
                    ->orderBy('order', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Get total points for an assignment
     */
    public function getTotalPoints($assignment_id)
    {
        $questions = $this->where('assignment_id', $assignment_id)->findAll();
        return array_sum(array_column($questions, 'points'));
    }
}

