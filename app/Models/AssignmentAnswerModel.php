<?php

namespace App\Models;

use CodeIgniter\Model;

class AssignmentAnswerModel extends Model
{
    protected $table = 'assignment_answers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'submission_id',
        'question_id',
        'selected_answer',
        'text_answer',
        'file_path',
        'file_name',
        'is_correct',
        'points_earned',
        'teacher_feedback'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get answers for a submission
     */
    public function getAnswersBySubmission($submission_id)
    {
        return $this->select('assignment_answers.*, assignment_questions.question_text, assignment_questions.question_type, assignment_questions.correct_answer, assignment_questions.points')
                    ->join('assignment_questions', 'assignment_questions.id = assignment_answers.question_id')
                    ->where('assignment_answers.submission_id', $submission_id)
                    ->orderBy('assignment_questions.order', 'ASC')
                    ->findAll();
    }

    /**
     * Save answers and calculate score
     */
    public function saveAnswers($submission_id, $answers, $questions)
    {
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $totalPoints += $question['points'];
            $selectedAnswer = $answers[$question['id']] ?? null;
            $isCorrect = ($selectedAnswer === $question['correct_answer']);
            $pointsEarned = $isCorrect ? $question['points'] : 0;
            $earnedPoints += $pointsEarned;

            $this->insert([
                'submission_id' => $submission_id,
                'question_id' => $question['id'],
                'selected_answer' => $selectedAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned
            ]);
        }

        return [
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'percentage' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0
        ];
    }
}

