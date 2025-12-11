<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-file-alt text-primary-custom me-2"></i><?= esc($assignment['title']) ?>
            </h1>
            <p class="text-muted mb-0">Course: <?= esc($course['title']) ?></p>
        </div>
        <a href="<?= base_url('assignment') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
        </a>
    </div>

    <div class="row g-4">
        <!-- Assignment Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
                    <h5 class="mb-0 fw-bold">Assignment Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2" style="color: var(--bs-text-dark);">Description</h6>
                        <p class="mb-0"><?= esc($assignment['description'] ?: 'No description provided.') ?></p>
                    </div>
                    <div class="row">
                        <?php if ($assignment['due_date']): ?>
                            <div class="col-md-6 mb-3">
                                <small class="text-muted d-block mb-1">Due Date</small>
                                <strong><?= date('M d, Y H:i', strtotime($assignment['due_date'])) ?></strong>
                                <?php if (strtotime($assignment['due_date']) < time()): ?>
                                    <span class="badge bg-danger ms-2">Overdue</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block mb-1">Max Score</small>
                            <strong><?= number_format($assignment['max_score'], 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <?php if (!empty($questions)): ?>
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-primary-custom text-white rounded-top-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-question-circle me-2"></i>Questions (<?= count($questions) ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!$submission): ?>
                            <form action="<?= base_url('assignment/submit/' . $assignment['id']) ?>" method="post" enctype="multipart/form-data" id="assignmentForm">
                                <?= csrf_field() ?>
                                
                                <?php foreach ($questions as $index => $question): ?>
                                    <?php 
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
                                        </div>
                                        <p class="mb-3"><?= esc($question['question_text']) ?></p>
                                        
                                        <?php if ($questionType === 'multiple_choice'): ?>
                                            <div class="ms-3">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" id="q<?= $question['id'] ?>_a" value="a" required>
                                                    <label class="form-check-label" for="q<?= $question['id'] ?>_a">
                                                        <strong>A.</strong> <?= esc($question['option_a']) ?>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" id="q<?= $question['id'] ?>_b" value="b" required>
                                                    <label class="form-check-label" for="q<?= $question['id'] ?>_b">
                                                        <strong>B.</strong> <?= esc($question['option_b']) ?>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" id="q<?= $question['id'] ?>_c" value="c" required>
                                                    <label class="form-check-label" for="q<?= $question['id'] ?>_c">
                                                        <strong>C.</strong> <?= esc($question['option_c']) ?>
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="answers[<?= $question['id'] ?>]" id="q<?= $question['id'] ?>_d" value="d" required>
                                                    <label class="form-check-label" for="q<?= $question['id'] ?>_d">
                                                        <strong>D.</strong> <?= esc($question['option_d']) ?>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php elseif ($questionType === 'essay'): ?>
                                            <div class="mb-3">
                                                <label for="essay_answer_<?= $question['id'] ?>" class="form-label">Your Answer <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="essay_answers[<?= $question['id'] ?>]" id="essay_answer_<?= $question['id'] ?>" rows="6" required placeholder="Type your answer here..."></textarea>
                                                <small class="text-muted">This question will be manually graded by your instructor.</small>
                                            </div>
                                        <?php elseif ($questionType === 'file_upload'): ?>
                                            <div class="mb-3">
                                                <label for="file_answer_<?= $question['id'] ?>" class="form-label">Upload File <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="file_answers[<?= $question['id'] ?>]" id="file_answer_<?= $question['id'] ?>" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.zip,.rar,.ppt,.pptx,.c,.java,.php" required>
                                                <small class="text-muted">Allowed: Word (.docx), PDF (.pdf), Image (.png/.jpg), Code files (.c, .java, .php), PowerPoint (.pptx) (Max: 10MB). This question will be manually graded by your instructor.</small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <div class="mb-4">
                                    <label for="submission_text" class="form-label small text-uppercase fw-semibold">Additional Comments (Optional)</label>
                                    <textarea class="form-control form-control-lg" id="submission_text" name="submission_text" rows="4" placeholder="Enter any additional comments..."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="submission_file" class="form-label small text-uppercase fw-semibold">Upload File (Optional)</label>
                                    <input type="file" class="form-control form-control-lg" id="submission_file" name="submission_file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.zip,.rar">
                                    <small class="text-muted">Allowed: PDF, DOC, DOCX, TXT, JPG, PNG, ZIP, RAR (Max: 10MB)</small>
                                </div>

                                <?php 
                                $hasManualGrading = false;
                                foreach ($questions as $q) {
                                    $qType = $q['question_type'] ?? 'multiple_choice';
                                    if ($qType === 'essay' || $qType === 'file_upload') {
                                        $hasManualGrading = true;
                                        break;
                                    }
                                }
                                ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> 
                                    <?php if ($hasManualGrading): ?>
                                        Some questions will be manually graded by your instructor. 
                                    <?php else: ?>
                                        This assignment will be auto-graded. 
                                    <?php endif; ?>
                                    Once submitted, you cannot modify your answers. Please review carefully before submitting.
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary-custom btn-lg fw-bold">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Assignment
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Show answers and results -->
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
                                                <?php if ($answer['is_correct']): ?>
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Correct (+<?= number_format($answer['points_earned'], 2) ?>)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Incorrect (0 points)</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if ($answer['points_earned'] > 0): ?>
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Graded: <?= number_format($answer['points_earned'], 2) ?>/<?= number_format($question['points'], 2) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending Grading</span>
                                                <?php endif; ?>
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
                                            foreach ($options as $key => $option): 
                                                $isSelected = $answer && $answer['selected_answer'] === $key;
                                                $isCorrect = $question['correct_answer'] === $key;
                                                $class = '';
                                                if ($isCorrect) $class = 'bg-success bg-opacity-10 border-success';
                                                if ($isSelected && !$isCorrect) $class = 'bg-danger bg-opacity-10 border-danger';
                                            ?>
                                                <div class="form-check mb-2 p-2 rounded <?= $class ?>">
                                                    <input class="form-check-input" type="radio" disabled <?= $isSelected ? 'checked' : '' ?>>
                                                    <label class="form-check-label">
                                                        <strong><?= strtoupper($key) ?>.</strong> <?= esc($option) ?>
                                                        <?php if ($isCorrect): ?>
                                                            <i class="fas fa-check-circle text-success ms-2"></i>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php if ($answer): ?>
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        Your answer: <strong><?= strtoupper($answer['selected_answer']) ?></strong> | 
                                                        Correct answer: <strong class="text-success"><?= strtoupper($question['correct_answer']) ?></strong>
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($questionType === 'essay' && $answer): ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Your Answer:</label>
                                            <div class="border rounded p-3 bg-light">
                                                <?= nl2br(esc($answer['text_answer'] ?? 'No answer provided')) ?>
                                            </div>
                                            <?php if (!empty($answer['teacher_feedback'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label fw-semibold text-success">Teacher Feedback:</label>
                                                    <div class="border border-success rounded p-3 bg-success bg-opacity-10">
                                                        <?= nl2br(esc($answer['teacher_feedback'])) ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($questionType === 'file_upload' && $answer): ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Your Uploaded File:</label>
                                            <?php if (!empty($answer['file_path'])): ?>
                                                <div class="border rounded p-3 bg-light">
                                                    <i class="fas fa-file me-2"></i>
                                                    <a href="<?= base_url('assignment/download/' . $answer['id']) ?>" target="_blank">
                                                        <?= esc($answer['file_name'] ?? 'Download File') ?>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="border rounded p-3 bg-light text-muted">No file uploaded</div>
                                            <?php endif; ?>
                                            <?php if (!empty($answer['teacher_feedback'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label fw-semibold text-success">Teacher Feedback:</label>
                                                    <div class="border border-success rounded p-3 bg-success bg-opacity-10">
                                                        <?= nl2br(esc($answer['teacher_feedback'])) ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Submission Form (for assignments without questions) -->
            <?php if (empty($questions) && !$submission): ?>
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                            <i class="fas fa-upload me-2"></i>Submit Assignment
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('assignment/submit/' . $assignment['id']) ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <label for="submission_text" class="form-label small text-uppercase fw-semibold">Your Answer / Submission Text</label>
                                <textarea class="form-control form-control-lg" id="submission_text" name="submission_text" rows="8" placeholder="Enter your submission here..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="submission_file" class="form-label small text-uppercase fw-semibold">Upload File (Optional)</label>
                                <input type="file" class="form-control form-control-lg" id="submission_file" name="submission_file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.zip,.rar">
                                <small class="text-muted">Allowed: PDF, DOC, DOCX, TXT, JPG, PNG, ZIP, RAR (Max: 10MB)</small>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> Once submitted, you cannot modify your submission. Please review carefully before submitting.
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-custom btn-lg fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Assignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif (!empty($submission)): ?>
                <!-- Submitted Assignment -->
                <div class="card shadow-sm border-0 mt-4 border-success">
                    <div class="card-header bg-success text-white rounded-top-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-check-circle me-2"></i>Assignment Submitted
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($submission['submitted_at'])): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Submitted On</small>
                                <strong><?= date('M d, Y H:i', strtotime($submission['submitted_at'])) ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($submission['submission_text'])): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Your Submission</small>
                                <p class="mb-0"><?= esc($submission['submission_text']) ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($submission['file_name'])): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Uploaded File</small>
                                <a href="<?= base_url('assignment/download/' . $submission['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i><?= esc($submission['file_name']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($submission['score']) && $submission['score'] !== null): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Score</small>
                                <strong class="text-success"><?= number_format($submission['score'], 2) ?> / <?= number_format($assignment['max_score'], 2) ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($submission['feedback'])): ?>
                            <div class="mb-0">
                                <small class="text-muted d-block mb-1">Teacher Feedback</small>
                                <p class="mb-0"><?= esc($submission['feedback']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">Information</h5>
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
                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge bg-<?= $assignment['status'] === 'published' ? 'success' : ($assignment['status'] === 'closed' ? 'danger' : 'warning') ?>">
                            <?= ucfirst($assignment['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

