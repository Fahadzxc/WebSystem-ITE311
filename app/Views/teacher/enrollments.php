<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-user-plus text-primary-custom me-2"></i>Manage Student Enrollments
            </h1>
            <p class="text-muted mb-0">Enroll students in your courses</p>
        </div>
        <a href="<?= base_url('teacher/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
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
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Courses Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-book text-primary-custom me-2"></i>Select Course
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($courses)): ?>
                        <div class="list-group" id="courseList">
                            <a href="#" 
                               class="list-group-item list-group-item-action course-item active" 
                               data-course-id="all"
                               data-course-title="All Courses">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-semibold">All Students</h6>
                                </div>
                                <p class="mb-1 text-muted small">View all students and their enrollments</p>
                            </a>
                            <?php foreach ($courses as $course): ?>
                                <a href="#" 
                                   class="list-group-item list-group-item-action course-item" 
                                   data-course-id="<?= $course['id'] ?>"
                                   data-course-title="<?= esc($course['title']) ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-semibold"><?= esc($course['title']) ?></h6>
                                    </div>
                                    <p class="mb-1 text-muted small"><?= esc($course['description']) ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-book-open text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="text-muted mb-0">No courses available yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Students Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                            <i class="fas fa-users text-primary-custom me-2"></i>Students
                            <span id="selectedCourseTitle" class="text-muted small ms-2">(All Students)</span>
                        </h5>
                        <?php if (!empty($students)): ?>
                            <span class="badge bg-primary-custom"><?= count($students) ?> student<?= count($students) > 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($students)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>No students found!</strong> There are no students in the system. Please create student accounts first.
                        </div>
                    <?php else: ?>
                        <div id="enrollmentArea">
                            <?php foreach ($studentsWithEnrollments as $studentData): ?>
                                <div class="card border mb-3 student-card" data-student-id="<?= $studentData['id'] ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1 fw-bold" style="color: var(--bs-text-dark);">
                                                    <i class="fas fa-user text-primary-custom me-2"></i><?= esc($studentData['name']) ?>
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    <i class="fas fa-envelope me-1"></i><?= esc($studentData['email']) ?>
                                                </p>
                                            </div>
                                            <span class="badge bg-info">
                                                <?= count($studentData['enrollments']) ?> enrolled
                                            </span>
                                        </div>
                                        
                                        <?php if (!empty($studentData['enrollments'])): ?>
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-book me-1"></i><strong>Enrolled Courses:</strong>
                                                </small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php foreach ($studentData['enrollments'] as $enrollment): ?>
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i><?= esc($enrollment['course_title']) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Not enrolled in any course yet.
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex gap-2 flex-wrap">
                                            <?php foreach ($courses as $course): ?>
                                                <?php 
                                                $isEnrolled = false;
                                                foreach ($studentData['enrollments'] as $enrollment) {
                                                    if ($enrollment['course_id'] == $course['id']) {
                                                        $isEnrolled = true;
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <?php if ($isEnrolled): ?>
                                                    <button class="btn btn-sm btn-success" disabled>
                                                        <i class="fas fa-check me-1"></i><?= esc($course['title']) ?>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-primary enroll-btn" 
                                                            data-student-id="<?= $studentData['id'] ?>" 
                                                            data-student-name="<?= esc($studentData['name']) ?>"
                                                            data-course-id="<?= $course['id'] ?>"
                                                            data-course-title="<?= esc($course['title']) ?>">
                                                        <i class="fas fa-user-plus me-1"></i>Enroll in <?= esc($course['title']) ?>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Wait for jQuery to be loaded
(function() {
    function initEnrollments() {
        if (typeof jQuery === 'undefined') {
            setTimeout(initEnrollments, 100);
            return;
        }
        
        jQuery(document).ready(function($) {
            // Handle course selection for filtering
            $('.course-item').on('click', function(e) {
                e.preventDefault();
                $('.course-item').removeClass('active');
                $(this).addClass('active');
                
                const courseId = $(this).data('course-id');
                const courseTitle = $(this).data('course-title');
                $('#selectedCourseTitle').text('(' + courseTitle + ')');
                
                if (courseId === 'all') {
                    // Show all students
                    $('.student-card').show();
                } else {
                    // Filter students by course enrollment
                    $('.student-card').each(function() {
                        const $card = $(this);
                        const hasEnrollment = $card.find('.enroll-btn[data-course-id="' + courseId + '"]').length === 0;
                        if (hasEnrollment) {
                            $card.show();
                        } else {
                            $card.hide();
                        }
                    });
                }
            });

            // Enroll student - use event delegation
            $(document).on('click', '.enroll-btn', function() {
                const studentId = $(this).data('student-id');
                const studentName = $(this).data('student-name');
                const courseId = $(this).data('course-id');
                const courseTitle = $(this).data('course-title');

                if (!confirm('Enroll ' + studentName + ' in "' + courseTitle + '"?')) {
                    return;
                }

                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Enrolling...');

                $.ajax({
                    url: '<?= base_url('teacher/enrollStudent') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                        student_id: studentId,
                        course_id: courseId
                    },
                    success: function(response) {
                        if (response && response.success) {
                            // Reload page to refresh enrollments
                            location.reload();
                        } else {
                            alert('Error: ' + (response && response.message ? response.message : 'Unknown error'));
                            $btn.prop('disabled', false).html(originalHtml);
                        }
                    },
                    error: function(xhr) {
                        console.error('Enrollment error:', xhr);
                        const response = xhr.responseJSON || {};
                        alert('Error: ' + (response.message || 'Failed to enroll student. Please try again.'));
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    }
    initEnrollments();
})();
</script>
<?= $this->endSection() ?>
