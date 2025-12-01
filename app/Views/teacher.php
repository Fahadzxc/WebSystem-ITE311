<div class="container py-4">
    <h3 class="mb-4 fw-bold" style="color: var(--bs-text-dark);">
        <i class="fas fa-chalkboard-teacher text-primary-custom me-2"></i>Welcome, Teacher!
    </h3>

    <!-- My Courses Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                    <i class="fas fa-book text-primary-custom me-2"></i>My Courses
                    <?php if (!empty($courses)): ?>
                        <span class="badge bg-primary-custom text-white ms-2"><?= count($courses) ?></span>
                    <?php endif; ?>
                </h5>
                <a href="<?= base_url('teacher/enrollments') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus me-2"></i>Manage Enrollments
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($courses)): ?>
                <div class="row g-3">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title text-primary-custom fw-bold mb-2">
                                        <?= esc($course['title']) ?>
                                    </h6>
                                    <p class="card-text text-muted small mb-3 flex-grow-1">
                                        <?= esc($course['description']) ?>
                                    </p>
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-info">
                                                <i class="fas fa-tag me-1"></i><?= esc($course['category'] ?? 'General') ?>
                                            </span>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-signal me-1"></i><?= ucfirst($course['level'] ?? 'beginner') ?>
                                            </span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('materials/upload/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                                <i class="fas fa-upload me-1"></i>Upload Materials
                                            </a>
                                            <a href="<?= base_url('teacher/enrollments?course_id=' . $course['id']) ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-users me-1"></i>Students
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-book-open text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                    <p class="text-muted mb-0">No courses available yet.</p>
                    <p class="text-muted small">Courses will appear here once they are created.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- New Submissions Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                <i class="fas fa-file-alt text-primary-custom me-2"></i>New Submissions
            </h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-0">No new submissions.</p>
        </div>
    </div>
</div>
