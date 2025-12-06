<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-tasks text-primary-custom me-2"></i>Assignments
            </h1>
            <p class="text-muted mb-0">View and submit your assignments</p>
        </div>
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

    <?php if (!empty($assignments)): ?>
        <div class="row g-4">
            <?php foreach ($assignments as $assignment): ?>
                <?php
                $isOverdue = $assignment['due_date'] && strtotime($assignment['due_date']) < time();
                $dueSoon = $assignment['due_date'] && strtotime($assignment['due_date']) < strtotime('+3 days') && strtotime($assignment['due_date']) > time();
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 h-100 <?= $isOverdue ? 'border-danger' : ($dueSoon ? 'border-warning' : '') ?>">
                        <div class="card-header bg-primary-custom text-white rounded-top-3">
                            <h6 class="mb-0 fw-bold">
                                <i class="fas fa-book me-2"></i><?= esc($assignment['course_title']) ?>
                            </h6>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-2" style="color: var(--bs-text-dark);">
                                <?= esc($assignment['title']) ?>
                            </h5>
                            <p class="card-text text-muted small mb-3 flex-grow-1">
                                <?= esc($assignment['description'] ?: 'No description') ?>
                            </p>
                            <div class="mb-3">
                                <?php if ($assignment['due_date']): ?>
                                    <small class="d-block mb-1 <?= $isOverdue ? 'text-danger' : ($dueSoon ? 'text-warning' : 'text-muted') ?>">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Due: <?= date('M d, Y H:i', strtotime($assignment['due_date'])) ?>
                                        <?php if ($isOverdue): ?>
                                            <span class="badge bg-danger ms-2">Overdue</span>
                                        <?php elseif ($dueSoon): ?>
                                            <span class="badge bg-warning ms-2">Due Soon</span>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                                <small class="text-muted d-block">
                                    <i class="fas fa-star me-1"></i>
                                    Max Score: <?= number_format($assignment['max_score'], 2) ?>
                                </small>
                            </div>
                            <div class="d-grid mt-auto">
                                <a href="<?= base_url('assignment/view/' . $assignment['id']) ?>" class="btn btn-sm btn-primary-custom">
                                    <i class="fas fa-eye me-1"></i>View Assignment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-tasks text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
            <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">No Assignments</h3>
            <p class="text-muted mb-3">You don't have any assignments yet.</p>
            <div class="alert alert-info mx-auto" style="max-width: 600px;">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Note:</strong> Assignments will only appear for courses you are enrolled in. 
                Make sure you are enrolled in the course where the assignment was created.
            </div>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary-custom mt-3">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

