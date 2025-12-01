<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="mb-1 fw-bold" style="color: var(--bs-text-dark); font-size: 2rem;">
                <i class="fas fa-tasks text-primary-custom me-2"></i>Assignments
            </h1>
            <p class="text-muted mb-0">Manage and create assignments for your courses</p>
        </div>
        <a href="<?= base_url('assignment/create') ?>" class="btn btn-primary-custom btn-lg">
            <i class="fas fa-plus me-2"></i>Create Assignment
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

    <?php if (!empty($assignments)): ?>
        <div class="row g-4">
            <?php foreach ($assignments as $assignment): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 h-100">
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
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Due: <?= date('M d, Y H:i', strtotime($assignment['due_date'])) ?>
                                    </small>
                                <?php endif; ?>
                                <small class="text-muted d-block">
                                    <i class="fas fa-star me-1"></i>
                                    Max Score: <?= number_format($assignment['max_score'], 2) ?>
                                </small>
                            </div>
                            <div class="d-flex gap-2 mt-auto">
                                <a href="<?= base_url('assignment/view/' . $assignment['id']) ?>" class="btn btn-sm btn-primary-custom flex-fill">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <button class="btn btn-sm btn-outline-danger delete-assignment" data-id="<?= $assignment['id'] ?>" data-title="<?= esc($assignment['title']) ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-tasks text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
            <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">No Assignments Yet</h3>
            <p class="text-muted mb-4">Create your first assignment to get started.</p>
            <a href="<?= base_url('assignment/create') ?>" class="btn btn-primary-custom btn-lg">
                <i class="fas fa-plus me-2"></i>Create Assignment
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
jQuery(document).ready(function($) {
    $('.delete-assignment').on('click', function() {
        const assignmentId = $(this).data('id');
        const assignmentTitle = $(this).data('title');
        
        if (!confirm('Are you sure you want to delete "' + assignmentTitle + '"?\n\nNote: Assignments with submissions cannot be deleted.')) {
            return;
        }

        $.ajax({
            url: '<?= base_url('assignment/delete') ?>/' + assignmentId,
            type: 'POST',
            dataType: 'json',
            data: {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON || {};
                alert('Error: ' + (response.message || 'Failed to delete assignment.'));
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

