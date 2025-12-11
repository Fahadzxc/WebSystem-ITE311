<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-file-alt text-primary-custom me-2"></i>Submission Details
            </h1>
            <p class="text-muted mb-0">
                Assignment: <?= esc($assignment['title']) ?> | 
                Student: <?= esc($submission['student_name']) ?>
            </p>
        </div>
        <a href="<?= base_url('assignment/view/' . $assignment['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Assignment
        </a>
    </div>

    <div class="row g-4">
        <!-- Student Information -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
                    <h5 class="mb-0 fw-bold">Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Name</small>
                        <strong><?= esc($submission['student_name']) ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Email</small>
                        <strong><?= esc($submission['student_email']) ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Submitted At</small>
                        <strong><?= date('M d, Y H:i', strtotime($submission['submitted_at'])) ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge bg-<?= $submission['status'] === 'graded' ? 'success' : 'info' ?> px-3 py-2">
                            <?= ucfirst($submission['status']) ?>
                        </span>
                    </div>
                    <?php if ($submission['score'] !== null): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Score</small>
                            <strong class="fs-4 text-primary">
                                <?= number_format($submission['score'], 2) ?> / <?= number_format($assignment['max_score'], 2) ?>
                            </strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Assignment Details -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">Assignment Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Course</small>
                        <strong><?= esc($course['title']) ?></strong>
                    </div>
                    <?php if ($assignment['due_date']): ?>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Due Date</small>
                            <strong><?= date('M d, Y H:i', strtotime($assignment['due_date'])) ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Max Score</small>
                        <strong><?= number_format($assignment['max_score'], 2) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Content -->
        <div class="col-lg-8">
            <!-- Submission Text/Comments -->
            <?php if (!empty($submission['submission_text'])): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-comment me-2"></i>Student Comments
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?= nl2br(esc($submission['submission_text'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Submitted File -->
            <?php if (!empty($submission['file_path'])): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-file me-2"></i>Submitted File
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <strong><?= esc($submission['file_name']) ?></strong>
                            </div>
                            <a href="<?= base_url('assignment/download/' . $submission['id']) ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Questions and Answers -->
            <?php if (!empty($questions)): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary-custom text-white rounded-top-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-question-circle me-2"></i>Questions & Answers (<?= count($questions) ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('assignment/grade/' . $submission['id']) ?>" method="POST" id="gradeForm">
                            <?= csrf_field() ?>
                            
                            <?php foreach ($questions as $index => $question): ?>
                                <?php 
                                $answer = $answers[$question['id']] ?? null;
                                $questionType = $question['question_type'] ?? 'multiple_choice';
                                ?>
                                <div class="mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-0" style="color: var(--bs-text-dark);">
                                                Question <?= $index + 1 ?> (<?= number_format($question['points'], 2) ?> points)
                                            </h6>
                                            <?php if ($questionType === 'essay'): ?>
                                                <span class="badge bg-success">Essay / Long Answer</span>
                                            <?php elseif ($questionType === 'file_upload'): ?>
                                                <span class="badge bg-info">File Upload</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Multiple Choice</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($answer): ?>
                                            <?php if ($questionType === 'multiple_choice'): ?>
                                                <?php 
                                                $isCorrect = strtolower($answer['selected_answer'] ?? '') === strtolower($question['correct_answer'] ?? '');
                                                ?>
                                                <span class="badge bg-<?= $isCorrect ? 'success' : 'danger' ?>">
                                                    <?= $isCorrect ? 'Correct (+' . number_format($answer['points_earned'], 2) . ')' : 'Incorrect (0)' ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-<?= $answer['points_earned'] > 0 ? 'success' : 'warning text-dark' ?>">
                                                    <?= $answer['points_earned'] > 0 ? 'Graded: ' . number_format($answer['points_earned'], 2) : 'Pending Grading' ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-3"><?= esc($question['question_text']) ?></p>
                                    
                                    <?php if ($questionType === 'multiple_choice'): ?>
                                        <div class="ms-3">
                                            <?php 
                                            $options = [
                                                'a' => $question['option_a'],
                                                'b' => $question['option_b'],
                                                'c' => $question['option_c'],
                                                'd' => $question['option_d']
                                            ];
                                            $correctAnswer = strtolower($question['correct_answer'] ?? '');
                                            $selectedAnswer = $answer ? strtolower($answer['selected_answer'] ?? '') : null;
                                            ?>
                                            <?php foreach ($options as $key => $option): ?>
                                                <div class="form-check mb-2 p-2 rounded <?= $key === $correctAnswer ? 'bg-success bg-opacity-10 border border-success' : ($key === $selectedAnswer && $key !== $correctAnswer ? 'bg-danger bg-opacity-10 border border-danger' : '') ?>">
                                                    <input class="form-check-input" type="radio" disabled <?= $key === $selectedAnswer ? 'checked' : '' ?>>
                                                    <label class="form-check-label">
                                                        <strong><?= strtoupper($key) ?>.</strong> <?= esc($option) ?>
                                                        <?php if ($key === $correctAnswer): ?>
                                                            <span class="badge bg-success ms-2">Correct Answer</span>
                                                        <?php endif; ?>
                                                        <?php if ($key === $selectedAnswer && $key !== $correctAnswer): ?>
                                                            <span class="badge bg-danger ms-2">Student's Answer</span>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php elseif ($questionType === 'essay' && $answer): ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Student's Answer:</label>
                                            <div class="border rounded p-3 bg-light" style="min-height: 100px;">
                                                <?= nl2br(esc($answer['text_answer'] ?? 'No answer provided')) ?>
                                            </div>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Points Earned (out of <?= number_format($question['points'], 2) ?>)</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           name="question_scores[<?= $question['id'] ?>]" 
                                                           value="<?= $answer['points_earned'] ?? 0 ?>" 
                                                           min="0" 
                                                           max="<?= $question['points'] ?>" 
                                                           step="0.01">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <label class="form-label">Feedback (Optional)</label>
                                                <textarea class="form-control" 
                                                          name="question_feedback[<?= $question['id'] ?>]" 
                                                          rows="3" 
                                                          placeholder="Enter feedback for this answer..."><?= esc($answer['teacher_feedback'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    <?php elseif ($questionType === 'file_upload' && $answer): ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Student's Uploaded File:</label>
                                            <?php if (!empty($answer['file_path'])): ?>
                                                <div class="border rounded p-3 bg-light mb-3">
                                                    <i class="fas fa-file me-2"></i>
                                                    <a href="<?= base_url('assignment/downloadAnswer/' . $answer['id']) ?>" target="_blank" class="text-decoration-none">
                                                        <?= esc($answer['file_name'] ?? 'Download File') ?>
                                                    </a>
                                                    <a href="<?= base_url('assignment/downloadAnswer/' . $answer['id']) ?>" class="btn btn-sm btn-primary ms-2" target="_blank">
                                                        <i class="fas fa-download me-1"></i>Download
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="border rounded p-3 bg-light text-muted mb-3">No file uploaded</div>
                                            <?php endif; ?>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Points Earned (out of <?= number_format($question['points'], 2) ?>)</label>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           name="question_scores[<?= $question['id'] ?>]" 
                                                           value="<?= $answer['points_earned'] ?? 0 ?>" 
                                                           min="0" 
                                                           max="<?= $question['points'] ?>" 
                                                           step="0.01">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <label class="form-label">Feedback (Optional)</label>
                                                <textarea class="form-control" 
                                                          name="question_feedback[<?= $question['id'] ?>]" 
                                                          rows="3" 
                                                          placeholder="Enter feedback for this answer..."><?= esc($answer['teacher_feedback'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="mb-3">
                                <label for="overall_feedback" class="form-label">Overall Feedback (Optional)</label>
                                <textarea class="form-control" 
                                          id="overall_feedback" 
                                          name="feedback" 
                                          rows="4" 
                                          placeholder="Enter overall feedback for the student..."><?= esc($submission['feedback'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Grades
                                </button>
                                <a href="<?= base_url('assignment/view/' . $assignment['id']) ?>" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
