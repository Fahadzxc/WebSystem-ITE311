<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="course-header">
        <h1><i class="fas fa-book"></i> <?= esc($course['title']) ?></h1>
        <p class="text-muted"><?= esc($course['description']) ?></p>
        <div class="course-meta">
            <span class="badge">
                <i class="fas fa-user"></i> Instructor: <?= esc($course['instructor_name'] ?? 'TBA') ?>
            </span>
        </div>
    </div>
    
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" style="background-color: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; border: 1px solid #A7F3D0;">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" style="background-color: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; border: 1px solid #FCA5A5;">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Course Materials -->
    <?php if (!empty($materials)): ?>
        <div class="materials-section">
            <h2><i class="fas fa-download"></i> Course Materials (<?= count($materials) ?>)</h2>
            <p class="text-muted">Download course materials and resources.</p>
            
            <div class="materials-grid">
                <?php foreach ($materials as $material): ?>
                    <div class="material-card">
                        <div class="material-icon">
                            <?php
                            $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                            $iconClass = 'fas fa-file';
                            
                            switch ($extension) {
                                case 'pdf':
                                    $iconClass = 'fas fa-file-pdf';
                                    break;
                                case 'doc':
                                case 'docx':
                                    $iconClass = 'fas fa-file-word';
                                    break;
                                case 'xls':
                                case 'xlsx':
                                    $iconClass = 'fas fa-file-excel';
                                    break;
                                case 'ppt':
                                case 'pptx':
                                    $iconClass = 'fas fa-file-powerpoint';
                                    break;
                                case 'jpg':
                                case 'jpeg':
                                case 'png':
                                case 'gif':
                                    $iconClass = 'fas fa-file-image';
                                    break;
                                case 'zip':
                                case 'rar':
                                    $iconClass = 'fas fa-file-archive';
                                    break;
                                case 'txt':
                                    $iconClass = 'fas fa-file-alt';
                                    break;
                            }
                            ?>
                            <i class="<?= $iconClass ?>"></i>
                        </div>
                        
                        <div class="material-info">
                            <h4 class="material-name"><?= esc($material['file_name']) ?></h4>
                            <p class="material-date">
                                <i class="fas fa-calendar"></i> 
                                <?= date('M d, Y H:i', strtotime($material['created_at'])) ?>
                            </p>
                        </div>
                        
                        <div class="material-actions">
                            <a href="<?= base_url('materials/download/' . $material['id']) ?>" 
                               class="btn btn-success" 
                               title="Download">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3>No Materials Available</h3>
            <p class="text-muted">No materials have been uploaded for this course yet.</p>
            <a href="<?= base_url('student/materials') ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to All Materials
            </a>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <div class="navigation-actions">
        <a href="<?= base_url('student/materials') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> All Materials
        </a>
        <a href="<?= base_url('student/dashboard') ?>" class="btn btn-primary">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>
</div>


<?= $this->endSection() ?>
