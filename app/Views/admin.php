<div class="container py-4">
  <h3 class="mb-3 text-primary">Welcome, Admin!</h3>

  <div class="row g-3">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-1">Total Users</h6>
          <div class="fs-4 fw-semibold">—</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="text-muted mb-1">Total Courses</h6>
          <div class="fs-4 fw-semibold">—</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Course Management Section -->
  <div class="card mt-4 shadow-sm">
    <div class="card-header fw-semibold">Course Management</div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <h6 class="text-muted mb-2">Upload Materials</h6>
          <div class="d-grid gap-2">
            <a href="<?= base_url('admin/course/1/upload') ?>" class="btn btn-primary">
              <i class="fas fa-upload me-2"></i> Upload to Course 1
            </a>
            <a href="<?= base_url('admin/course/2/upload') ?>" class="btn btn-primary">
              <i class="fas fa-upload me-2"></i> Upload to Course 2
            </a>
            <a href="<?= base_url('admin/course/3/upload') ?>" class="btn btn-primary">
              <i class="fas fa-upload me-2"></i> Upload to Course 3
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-4 shadow-sm">
    <div class="card-header fw-semibold">Recent Activity</div>
    <div class="card-body">
      <p class="text-muted mb-0">No recent activity to show.</p>
    </div>
  </div>
</div>