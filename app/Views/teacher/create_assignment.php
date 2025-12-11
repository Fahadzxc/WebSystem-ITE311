<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-plus-circle text-primary-custom me-2"></i>Create Assignment
            </h1>
            <p class="text-muted mb-0">Create a new assignment for your course</p>
        </div>
        <a href="<?= base_url('assignment') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error:</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($validation) && !empty($validation->getErrors())): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2"></i>Assignment Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('assignment/create') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="course_id" class="form-label small text-uppercase fw-semibold">Course <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="course_id" name="course_id" required>
                                <option value="">Select a course</option>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>"><?= esc($course['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label small text-uppercase fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" value="<?= old('title', '') ?>" placeholder="Enter assignment title" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label small text-uppercase fw-semibold">Description</label>
                            <textarea class="form-control form-control-lg" id="description" name="description" rows="5" placeholder="Enter assignment description"><?= old('description', '') ?></textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="due_date" class="form-label small text-uppercase fw-semibold">Due Date</label>
                                <input type="datetime-local" class="form-control form-control-lg" id="due_date" name="due_date" value="<?= old('due_date', '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="max_score" class="form-label small text-uppercase fw-semibold">Max Score</label>
                                <input type="number" class="form-control form-control-lg" id="max_score" name="max_score" value="<?= old('max_score', 100) ?>" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label small text-uppercase fw-semibold">Status</label>
                            <select class="form-select form-select-lg" id="status" name="status">
                                <option value="published" <?= old('status', 'published') === 'published' ? 'selected' : '' ?>>Published</option>
                                <option value="draft" <?= old('status', 'published') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>

                        <!-- Questions Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label small text-uppercase fw-semibold mb-0">Questions</label>
                                <button type="button" class="btn btn-sm btn-primary" id="addQuestion" data-bs-toggle="modal" data-bs-target="#questionTypeModal">
                                    <i class="fas fa-plus me-1"></i>Add Question
                                </button>
                            </div>
                            <div id="questionsContainer">
                                <!-- Questions will be added here dynamically -->
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary-custom btn-lg fw-bold" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Create Assignment
                            </button>
                            <a href="<?= base_url('assignment') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Type Selection Modal -->
<div class="modal fade" id="questionTypeModal" tabindex="-1" aria-labelledby="questionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary-custom text-white">
                <h5 class="modal-title" id="questionTypeModalLabel">
                    <i class="fas fa-question-circle me-2"></i>Select Question Type
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Multiple Choice -->
                    <div class="col-12">
                        <div class="card border h-100 question-type-option" data-type="multiple_choice" style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-list-ul fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">1. Multiple Choice</h6>
                                        <p class="text-muted small mb-0">Use case: quick assessment, automatic grading</p>
                                        <p class="text-muted small mb-0"><em>Example: "What is the capital of France?"</em></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-success" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Essay / Long Answer -->
                    <div class="col-12">
                        <div class="card border h-100 question-type-option" data-type="essay" style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-file-alt fa-2x text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">2. Essay / Long Answer</h6>
                                        <p class="text-muted small mb-0">Use case: reflection, explanation, analysis</p>
                                        <p class="text-muted small mb-0"><em>Example: "Explain how climate change affects agriculture."</em></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-success" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Upload -->
                    <div class="col-12">
                        <div class="card border h-100 question-type-option" data-type="file_upload" style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <i class="fas fa-upload fa-2x text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">3. File Upload</h6>
                                        <p class="text-muted small mb-0">Use case: kapag kailangan ng document, PDF, image, video, PowerPoint, code file, etc.</p>
                                        <p class="text-muted small mb-0"><em>Common student uploads: Word (.docx), PDF (.pdf), Image (.png/.jpg), Code files (.c, .java, .php), PowerPoint (.pptx)</em></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-success" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
jQuery(document).ready(function($) {
    // Debug: Log form submission
    $('form').on('submit', function(e) {
        console.log('Form submitting...');
        const courseId = $('#course_id').val();
        const title = $('#title').val();
        console.log('Course ID:', courseId);
        console.log('Title:', title);
        
        if (!courseId || !title) {
            alert('Please fill in Course and Title fields!');
            e.preventDefault();
            return false;
        }
    });
    
    let questionCount = 0;
    let selectedQuestionType = null;

    // Question type selection
    $('.question-type-option').on('click', function() {
        $('.question-type-option').removeClass('border-primary').addClass('border');
        $('.question-type-option .fa-check-circle').hide();
        $(this).removeClass('border').addClass('border-primary border-2');
        $(this).find('.fa-check-circle').show();
        selectedQuestionType = $(this).data('type');
    });

    // When modal is closed, add question if type was selected
    $('#questionTypeModal').on('hidden.bs.modal', function() {
        if (selectedQuestionType) {
            addQuestion(selectedQuestionType);
            selectedQuestionType = null;
            $('.question-type-option').removeClass('border-primary border-2').addClass('border');
            $('.question-type-option .fa-check-circle').hide();
        }
    });

    function addQuestion(questionType) {
        questionCount++;
        let questionHtml = '';
        
        if (questionType === 'multiple_choice') {
            questionHtml = `
                <div class="card border mb-3 question-item" data-question-index="${questionCount}" data-question-type="multiple_choice">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Question ${questionCount}</strong>
                            <span class="badge bg-primary ms-2">Multiple Choice</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="questions[${questionCount}][question_type]" value="multiple_choice">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Question Text <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="questions[${questionCount}][question_text]" rows="2" required></textarea>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small">Option A <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="questions[${questionCount}][option_a]" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Option B <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="questions[${questionCount}][option_b]" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Option C <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="questions[${questionCount}][option_c]" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Option D <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="questions[${questionCount}][option_d]" required>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">Correct Answer <span class="text-danger">*</span></label>
                                <select class="form-select" name="questions[${questionCount}][correct_answer]" required>
                                    <option value="">Select...</option>
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Points</label>
                                <input type="number" class="form-control" name="questions[${questionCount}][points]" value="1" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else if (questionType === 'essay') {
            questionHtml = `
                <div class="card border mb-3 question-item" data-question-index="${questionCount}" data-question-type="essay">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Question ${questionCount}</strong>
                            <span class="badge bg-success ms-2">Essay / Long Answer</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="questions[${questionCount}][question_type]" value="essay">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Question Text <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="questions[${questionCount}][question_text]" rows="3" required placeholder="Example: Explain how climate change affects agriculture."></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label small">Points</label>
                                <input type="number" class="form-control" name="questions[${questionCount}][points]" value="10" min="0" step="0.01">
                                <small class="text-muted">This question will be manually graded by the teacher.</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else if (questionType === 'file_upload') {
            questionHtml = `
                <div class="card border mb-3 question-item" data-question-index="${questionCount}" data-question-type="file_upload">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Question ${questionCount}</strong>
                            <span class="badge bg-info ms-2">File Upload</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="questions[${questionCount}][question_type]" value="file_upload">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Question Text / Instructions <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="questions[${questionCount}][question_text]" rows="3" required placeholder="Example: Upload a 1-page reflection paper about the topic discussed."></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label small">Points</label>
                                <input type="number" class="form-control" name="questions[${questionCount}][points]" value="10" min="0" step="0.01">
                                <small class="text-muted">This question will be manually graded by the teacher. Students can upload: Word (.docx), PDF (.pdf), Image (.png/.jpg), Code files (.c, .java, .php), PowerPoint (.pptx)</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        $('#questionsContainer').append(questionHtml);
    }

    $(document).on('click', '.remove-question', function() {
        $(this).closest('.question-item').remove();
        // Renumber questions
        $('.question-item').each(function(index) {
            $(this).find('.card-header strong').text('Question ' + (index + 1));
        });
    });
});
</script>
<?= $this->endSection() ?>

