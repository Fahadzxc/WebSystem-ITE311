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
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
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

    <!-- Pending Enrollment Requests -->
    <div class="card shadow-sm border-0 mb-4 <?= empty($pendingEnrollments) ? 'border-warning' : '' ?>">
        <div class="card-header <?= !empty($pendingEnrollments) ? 'bg-warning bg-opacity-10' : 'bg-white' ?> border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                <i class="fas fa-clock text-warning me-2"></i>Pending Enrollment Requests
                <?php if (!empty($pendingEnrollments)): ?>
                    <span class="badge bg-warning text-dark ms-2"><?= count($pendingEnrollments) ?></span>
                <?php else: ?>
                    <span class="badge bg-secondary ms-2">0</span>
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($pendingEnrollments)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Request Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingEnrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($enrollment['student_name']) ?></strong><br>
                                        <small class="text-muted"><?= esc($enrollment['student_email']) ?></small>
                                    </td>
                                    <td><?= esc($enrollment['course_title']) ?></td>
                                    <td><?php 
                                        helper('date');
                                        // Date is saved in server timezone (Asia/Manila), display as-is
                                        // Create DateTime with app timezone to ensure correct display
                                        $date = new \DateTime($enrollment['enrollment_date'], new \DateTimeZone('Asia/Manila'));
                                        echo $date->format('M d, Y g:i A');
                                    ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success me-2" onclick="approveEnrollment(<?= $enrollment['id'] ?>)">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectEnrollment(<?= $enrollment['id'] ?>, '<?= esc($enrollment['course_title']) ?>')">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="text-muted mb-0">No pending enrollment requests.</p>
                    <p class="text-muted small">All enrollment requests have been processed.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

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
                                <?php 
                                $is2ndSemester = !empty($course['semester']) && $course['semester'] === '2nd Semester';
                                $courseClass = $is2ndSemester ? 'list-group-item course-item disabled opacity-50' : 'list-group-item list-group-item-action course-item';
                                ?>
                                <a href="#" 
                                   class="<?= $courseClass ?>" 
                                   data-course-id="<?= $course['id'] ?>" 
                                   data-course-title="<?= esc($course['title']) ?>"
                                   <?= $is2ndSemester ? 'onclick="return false;" style="cursor: not-allowed;"' : '' ?>>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 fw-semibold">
                                            <?= esc($course['title']) ?>
                                            <?php if ($is2ndSemester): ?>
                                                <span class="badge bg-warning text-dark ms-2">
                                                    <i class="fas fa-lock me-1"></i>Unavailable
                                                </span>
                                            <?php endif; ?>
                                        </h6>
                                    </div>
                                    <p class="mb-1 text-muted small"><?= esc($course['description']) ?></p>
                                    <?php if (!empty($course['semester']) || !empty($course['academic_year'])): ?>
                                        <div class="mt-2">
                                            <?php if (!empty($course['semester'])): ?>
                                                <?php if ($is2ndSemester): ?>
                                                    <span class="badge bg-warning text-dark me-1">
                                                        <i class="fas fa-calendar-alt me-1"></i><?= esc($course['semester']) ?>
                                                        <i class="fas fa-lock ms-1"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-info me-1">
                                                        <i class="fas fa-calendar-alt me-1"></i><?= esc($course['semester']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (!empty($course['academic_year'])): ?>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-graduation-cap me-1"></i>AY <?= esc($course['academic_year']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
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
                        <span id="studentsBadge" class="badge bg-primary-custom">
                            <?php if (!empty($students)): ?>
                                <?= count($students) ?> student<?= count($students) > 1 ? 's' : '' ?>
                            <?php else: ?>
                                0 students
                            <?php endif; ?>
                        </span>
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
                                                <?php 
                                                // Count only active enrollments
                                                $activeCount = count(array_filter($studentData['enrollments'], function($e) {
                                                    return $e['status'] === 'active';
                                                }));
                                                echo $activeCount;
                                                ?> enrolled
                                            </span>
                                        </div>
                                        
                                        <?php 
                                        // Filter to show only active enrollments
                                        $activeEnrollments = array_filter($studentData['enrollments'], function($e) {
                                            return $e['status'] === 'active';
                                        });
                                        ?>
                                        <?php if (!empty($activeEnrollments)): ?>
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-book me-1"></i><strong>Enrolled Courses:</strong>
                                                </small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php foreach ($activeEnrollments as $enrollment): ?>
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
                                                // Check if course is 2nd semester
                                                $is2ndSemester = !empty($course['semester']) && $course['semester'] === '2nd Semester';
                                                
                                                // Check if student is actively enrolled (only active status, not pending or rejected)
                                                $isEnrolled = false;
                                                $enrollmentId = null;
                                                foreach ($studentData['enrollments'] as $enrollment) {
                                                    if ($enrollment['course_id'] == $course['id'] && $enrollment['status'] === 'active') {
                                                        $isEnrolled = true;
                                                        $enrollmentId = $enrollment['id'];
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <?php if ($isEnrolled): ?>
                                                    <button class="btn btn-sm btn-danger unenroll-btn" 
                                                            data-student-id="<?= $studentData['id'] ?>" 
                                                            data-student-name="<?= esc($studentData['name']) ?>"
                                                            data-course-id="<?= $course['id'] ?>"
                                                            data-course-title="<?= esc($course['title']) ?>"
                                                            data-enrollment-id="<?= $enrollmentId ?>">
                                                        <i class="fas fa-user-minus me-1"></i>Unenroll from <?= esc($course['title']) ?>
                                                    </button>
                                                <?php elseif ($is2ndSemester): ?>
                                                    <button class="btn btn-sm btn-secondary" disabled title="Course is unavailable for 2nd Semester">
                                                        <i class="fas fa-lock me-1"></i>Unavailable (2nd Semester)
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
            // Function to filter students by course
            function filterStudentsByCourse(courseId, courseTitle) {
                $('#selectedCourseTitle').text('(' + courseTitle + ')');
                
                if (courseId === 'all') {
                    // Show all students
                    $('.student-card').show();
                    // Update badge count to show all students
                    const totalStudents = $('.student-card').length;
                    $('#studentsBadge').text(totalStudents + ' student' + (totalStudents !== 1 ? 's' : ''));
                } else {
                    // Show ALL students (so teacher can enroll them)
                    // Count how many are enrolled in this course
                    let enrolledCount = 0;
                    $('.student-card').each(function() {
                        const $card = $(this);
                        // Check if student has unenroll button for this course (meaning they are enrolled)
                        const isEnrolled = $card.find('.unenroll-btn[data-course-id="' + courseId + '"]').length > 0;
                        if (isEnrolled) {
                            enrolledCount++;
                        }
                        // Always show the student card so teacher can enroll them
                        $card.show();
                    });
                    
                    const totalStudents = $('.student-card').length;
                    // Update badge count to show enrolled vs total
                    if (enrolledCount > 0) {
                        $('#studentsBadge').text(enrolledCount + ' enrolled / ' + totalStudents + ' total');
                    } else {
                        $('#studentsBadge').text(totalStudents + ' student' + (totalStudents !== 1 ? 's' : '') + ' (0 enrolled)');
                    }
                }
            }
            
            // Handle course selection for filtering
            $('.course-item').on('click', function(e) {
                e.preventDefault();
                
                // Don't allow clicking on disabled 2nd semester courses
                if ($(this).hasClass('disabled')) {
                    return false;
                }
                
                $('.course-item').removeClass('active');
                $(this).addClass('active');
                
                const courseId = $(this).data('course-id');
                const courseTitle = $(this).data('course-title');
                filterStudentsByCourse(courseId, courseTitle);
            });
            
            // Handle URL parameter for course_id
            const urlParams = new URLSearchParams(window.location.search);
            const courseIdParam = urlParams.get('course_id');
            if (courseIdParam) {
                // Find and click the course item
                const $courseItem = $('.course-item[data-course-id="' + courseIdParam + '"]');
                if ($courseItem.length > 0 && !$courseItem.hasClass('disabled')) {
                    $courseItem.click();
                }
            }

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

            // Unenroll student - use event delegation
            $(document).on('click', '.unenroll-btn', function() {
                const studentId = $(this).data('student-id');
                const studentName = $(this).data('student-name');
                const courseId = $(this).data('course-id');
                const courseTitle = $(this).data('course-title');
                const enrollmentId = $(this).data('enrollment-id');

                if (!confirm('Unenroll ' + studentName + ' from "' + courseTitle + '"? This action cannot be undone.')) {
                    return;
                }

                const $btn = $(this);
                const originalHtml = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Unenrolling...');

                $.ajax({
                    url: '<?= base_url('teacher/unenrollStudent') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                        enrollment_id: enrollmentId,
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
                        console.error('Unenrollment error:', xhr);
                        const response = xhr.responseJSON || {};
                        alert('Error: ' + (response.message || 'Failed to unenroll student. Please try again.'));
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
    }
    initEnrollments();

    // Approve enrollment
    window.approveEnrollment = function(enrollmentId) {
        if (!confirm('Are you sure you want to approve this enrollment request?')) {
            return;
        }

        $.ajax({
            url: '<?= base_url('teacher/enrollments/approve') ?>',
            method: 'POST',
            data: {
                enrollment_id: enrollmentId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('Enrollment approved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Failed to approve enrollment.'));
                }
            },
            error: function() {
                alert('Error: Failed to approve enrollment. Please try again.');
            }
        });
    };

    // Reject enrollment
    window.rejectEnrollment = function(enrollmentId, courseTitle) {
        const reason = prompt('Please provide a reason for rejecting this enrollment request:\n\nCourse: ' + courseTitle);
        
        if (reason === null) {
            return; // User cancelled
        }
        
        if (reason.trim() === '') {
            alert('Rejection reason is required.');
            return;
        }

        $.ajax({
            url: '<?= base_url('teacher/enrollments/reject') ?>',
            method: 'POST',
            data: {
                enrollment_id: enrollmentId,
                rejection_reason: reason.trim(),
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('Enrollment rejected successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Failed to reject enrollment.'));
                }
            },
            error: function() {
                alert('Error: Failed to reject enrollment. Please try again.');
            }
        });
    };
})();
</script>
<?= $this->endSection() ?>
