<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="flex-shrink-0 me-3">
            <i class="fas fa-download text-primary-custom" style="font-size: 2rem;"></i>
        </div>
        <div class="flex-grow-1">
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">Course Materials</h1>
            <p class="text-muted mb-0">Download materials from your enrolled courses.</p>
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

    <!-- Materials by Course -->
    <?php if (!empty($materials_by_course)): ?>
        <?php foreach ($materials_by_course as $course_data): ?>
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-book text-primary-custom me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);"><?= esc($course_data['course_title']) ?></h5>
                            <p class="text-muted mb-0 small"><?= esc($course_data['course_description']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($course_data['materials'] as $material): ?>
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
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">No Materials Available</h3>
                <p class="text-muted mb-4">No course materials are available yet. Check back later or contact your instructor.</p>
                <a href="<?= base_url('student/dashboard') ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
