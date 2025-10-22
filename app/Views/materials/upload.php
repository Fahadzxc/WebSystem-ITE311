<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="card">
    <h1><i class="fas fa-upload"></i> Upload Materials - <?= esc($course['course_name'] ?? 'Course') ?></h1>
    
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

    <!-- Upload Form -->
    <div class="upload-section">
        <h2><i class="fas fa-cloud-upload-alt"></i> Upload New Material</h2>
        <p class="text-muted">Upload course materials such as documents, presentations, images, or other files.</p>
        
        <form action="<?= base_url('file-upload/upload') ?>" method="post" enctype="multipart/form-data" class="upload-form">
            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
            <input type="hidden" name="redirect_to" value="materials">
            
            <div class="form-group">
                <label for="material_file" class="form-label">
                    <i class="fas fa-file"></i> Select File
                </label>
                <div class="file-input-wrapper">
                    <input type="file" 
                           class="form-control file-input" 
                           id="material_file" 
                           name="material_file" 
                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar,.ppt,.pptx,.xls,.xlsx"
                           required>
                    <div class="file-input-info">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Allowed file types: PDF, DOC, DOCX, TXT, JPG, PNG, GIF, ZIP, RAR, PPT, PPTX, XLS, XLSX (Max: 10MB)
                        </small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Material
                </button>
                <a href="<?= base_url('admin/courses') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Courses
                </a>
            </div>
        </form>
    </div>

    <!-- Existing Materials -->
    <?php if (!empty($materials)): ?>
        <div class="materials-section">
            <h2><i class="fas fa-folder-open"></i> Course Materials (<?= count($materials) ?>)</h2>
            <p class="text-muted">Manage existing course materials.</p>
            
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
                               class="btn btn-sm btn-success" 
                               title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="<?= base_url('materials/delete/' . $material['id']) ?>" 
                               class="btn btn-sm btn-danger" 
                               title="Delete"
                               onclick="return confirm('Are you sure you want to delete this material?')">
                                <i class="fas fa-trash"></i>
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
            <h3>No Materials Yet</h3>
            <p class="text-muted">Upload your first course material using the form above.</p>
        </div>
    <?php endif; ?>
</div>


<?= $this->endSection() ?>
