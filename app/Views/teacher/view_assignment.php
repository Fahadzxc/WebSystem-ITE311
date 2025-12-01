<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-eye text-primary-custom me-2"></i><?= esc($assignment['title']) ?>
            </h1>
            <p class="text-muted mb-0">Course: <?= esc($course['title']) ?></p>
        </div>
        <a href="<?= base_url('assignment') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
        </a>
    </div>

    <div class="row g-4">
        <!-- Assignment Details -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-custom text-white rounded-top-3">
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
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge bg-<?= $assignment['status'] === 'published' ? 'success' : ($assignment['status'] === 'closed' ? 'danger' : 'warning') ?>">
                            <?= ucfirst($assignment['status']) ?>
                        </span>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Submissions</small>
                        <strong><?= count($submissions) ?> student<?= count($submissions) !== 1 ? 's' : '' ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-file-upload me-2"></i>Student Submissions (<?= count($submissions) ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($submissions)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($submissions as $submission): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($submission['student_name']) ?></strong><br>
                                                <small class="text-muted"><?= esc($submission['student_email']) ?></small>
                                            </td>
                                            <td>
                                                <small><?= date('M d, Y H:i', strtotime($submission['submitted_at'])) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $submission['status'] === 'graded' ? 'success' : 'info' ?>">
                                                    <?= ucfirst($submission['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($submission['score'] !== null): ?>
                                                    <strong><?= number_format($submission['score'], 2) ?></strong> / <?= number_format($assignment['max_score'], 2) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not graded</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('assignment/submission/' . $submission['id']) ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="text-muted mb-0">No submissions yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Assignment Description -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-info-circle me-2"></i>Description
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= esc($assignment['description'] ?: 'No description provided.') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

