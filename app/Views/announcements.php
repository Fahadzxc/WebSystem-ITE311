<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container py-4" style="max-height: calc(100vh - 200px); overflow-y: auto;">
  <h3 class="mb-3 text-primary">Announcements</h3>
  
  <!-- Flash Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Announcements List -->
  <div class="card shadow-sm">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
      <span>All Announcements</span>
      <span class="badge bg-primary"><?= count($announcements ?? []) ?></span>
    </div>
    <div class="card-body">
      <?php if (!empty($announcements)): ?>
        <div class="row g-3">
          <?php foreach ($announcements as $announcement): ?>
            <div class="col-12">
              <div class="card border-0 bg-light mb-3">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title text-primary mb-0"><?= esc($announcement['title']) ?></h5>
                    <small class="text-muted">
                      <i class="fas fa-calendar-alt me-1"></i>
                      <?= date('M d, Y', strtotime($announcement['created_at'])) ?>
                    </small>
                  </div>
                  <p class="card-text text-muted"><?= esc($announcement['content']) ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                      <i class="fas fa-clock me-1"></i>
                      Posted: <?= date('M d, Y g:i A', strtotime($announcement['created_at'])) ?>
                    </small>
                    <?php if (isset($announcement['updated_at']) && $announcement['updated_at'] !== $announcement['created_at']): ?>
                      <small class="text-muted">
                        <i class="fas fa-edit me-1"></i>
                        Updated: <?= date('M d, Y g:i A', strtotime($announcement['updated_at'])) ?>
                      </small>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-center py-5">
          <i class="fas fa-bullhorn text-muted" style="font-size: 3rem;"></i>
          <h5 class="text-muted mt-3">No Announcements Yet</h5>
          <p class="text-muted">There are no announcements to display at this time.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
