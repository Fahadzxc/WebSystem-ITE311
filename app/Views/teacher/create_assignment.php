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

                        <!-- Multiple Choice Questions Section -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label small text-uppercase fw-semibold mb-0">Multiple Choice Questions</label>
                                <button type="button" class="btn btn-sm btn-primary" id="addQuestion">
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

    $('#addQuestion').on('click', function() {
        questionCount++;
        const questionHtml = `
            <div class="card border mb-3 question-item" data-question-index="${questionCount}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>Question ${questionCount}</strong>
                    <button type="button" class="btn btn-sm btn-danger remove-question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="card-body">
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
        $('#questionsContainer').append(questionHtml);
    });

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

