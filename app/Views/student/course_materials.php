<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <!-- Course Header Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <div class="d-flex align-items-start mb-3">
                <div class="flex-shrink-0 me-3">
                    <i class="fas fa-book text-primary-custom" style="font-size: 2.5rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <h1 class="mb-2 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                        <?= esc($course['title']) ?>
                    </h1>
                    <p class="text-muted mb-2"><?= esc($course['description']) ?></p>
                    <span class="badge bg-primary-custom text-white">
                        <i class="fas fa-user me-1"></i>Instructor: <?= !empty($course['instructor_name']) ? esc($course['instructor_name']) : 'TBA' ?>
                    </span>
                </div>
            </div>
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

    <!-- Course Materials -->
    <?php if (!empty($materials)): ?>
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex align-items-center">
                    <i class="fas fa-download text-primary-custom me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                            Course Materials
                            <span class="badge bg-primary-custom text-white ms-2"><?= count($materials) ?></span>
                        </h5>
                        <p class="text-muted mb-0 small">Download course materials and resources.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($materials as $material): ?>
                        <?php
                        $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                        $iconClass = 'fas fa-file';
                        $iconColor = 'text-secondary';
                        
                        switch ($extension) {
                            case 'pdf':
                                $iconClass = 'fas fa-file-pdf';
                                $iconColor = 'text-danger';
                                break;
                            case 'doc':
                            case 'docx':
                                $iconClass = 'fas fa-file-word';
                                $iconColor = 'text-primary';
                                break;
                            case 'xls':
                            case 'xlsx':
                                $iconClass = 'fas fa-file-excel';
                                $iconColor = 'text-success';
                                break;
                            case 'ppt':
                            case 'pptx':
                                $iconClass = 'fas fa-file-powerpoint';
                                $iconColor = 'text-warning';
                                break;
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'gif':
                                $iconClass = 'fas fa-file-image';
                                $iconColor = 'text-info';
                                break;
                            case 'zip':
                            case 'rar':
                                $iconClass = 'fas fa-file-archive';
                                $iconColor = 'text-dark';
                                break;
                            case 'txt':
                                $iconClass = 'fas fa-file-alt';
                                $iconColor = 'text-muted';
                                break;
                        }
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="<?= $iconClass ?> <?= $iconColor ?>" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1 fw-semibold" style="color: var(--bs-text-dark); line-height: 1.4;">
                                                <?= esc($material['file_name']) ?>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?= date('M d, Y H:i', strtotime($material['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="mt-auto">
                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                           class="btn btn-success w-100" 
                                           title="Download">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">No Materials Available</h3>
                <p class="text-muted mb-4">No materials have been uploaded for this course yet.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Navigation Buttons -->
    <div class="d-flex gap-2 justify-content-center mt-4">
        <a href="<?= base_url('student/materials') ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to All Materials
        </a>
        <a href="<?= base_url('student/dashboard') ?>" class="btn btn-primary">
            <i class="fas fa-home me-2"></i>Dashboard
        </a>
    </div>
</div>

<?= $this->endSection() ?>
