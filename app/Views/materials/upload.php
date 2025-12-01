<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="flex-shrink-0 me-3">
            <i class="fas fa-upload text-primary-custom" style="font-size: 2rem;"></i>
        </div>
        <div class="flex-grow-1">
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                Upload Materials - <?= esc($course['course_name'] ?? $course['title'] ?? 'Course') ?>
            </h1>
            <p class="text-muted mb-0">Manage and upload course materials</p>
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

    <!-- Upload Form Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex align-items-center">
                <i class="fas fa-cloud-upload-alt text-primary-custom me-3" style="font-size: 1.5rem;"></i>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">Upload New Material</h5>
                    <p class="text-muted mb-0 small">Upload course materials such as documents, presentations, images, or other files.</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= base_url('materials/uploadFile') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                <input type="hidden" name="redirect_to" value="materials">
                
                <div class="mb-4">
                    <label for="material_file" class="form-label fw-semibold">
                        <i class="fas fa-file me-2"></i>Select File
                    </label>
                    <input type="file" 
                           class="form-control form-control-lg" 
                           id="material_file" 
                           name="material_file" 
                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.ppt,.pptx,.xls,.xlsx"
                           required>
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Allowed file types: PDF, DOC, DOCX, TXT, JPG, PNG, GIF, ZIP, RAR, PPT, PPTX, XLS, XLSX (Max: 10MB)
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Material
                    </button>
                    <a href="<?= base_url('admin/courses') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Courses
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Materials Card -->
    <?php if (!empty($materials)): ?>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-folder-open text-primary-custom me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                                Course Materials
                                <span class="badge bg-primary-custom text-white ms-2"><?= count($materials) ?></span>
                            </h5>
                            <p class="text-muted mb-0 small">Manage existing course materials.</p>
                        </div>
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
                                    <div class="mt-auto d-flex gap-2">
                                        <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                                           class="btn btn-success flex-fill" 
                                           title="Download">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                        <a href="<?= base_url('materials/delete/' . $material['id']) ?>" 
                                           class="btn btn-danger" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this material?')">
                                            <i class="fas fa-trash"></i>
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
                <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">No Materials Yet</h3>
                <p class="text-muted mb-0">Upload your first course material using the form above.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
