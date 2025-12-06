<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    
    <?php
      // Get role and name
      $role = $user['role'] ?? session('role');
      $name = $user['name'] ?? session('name');

      switch ($role) {
        case 'admin':
          // Admin Dashboard Content
    ?>
    <div class="container py-4">
      <h3 class="mb-3 text-primary">Welcome, Admin!</h3>

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

      <!-- User Management Section -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">User Management</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
              <i class="fas fa-plus me-2"></i>Add New User
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="card-header bg-white border-bottom">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="mb-0 fw-semibold">All Users</h6>
              </div>
              <div class="col-auto">
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search users..." style="width: 250px;">
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
              <thead class="bg-light">
                <tr>
                  <th class="px-4 py-3">ID</th>
                  <th class="py-3">Name</th>
                  <th class="py-3">Email</th>
                  <th class="py-3">Role</th>
                  <th class="py-3">Created At</th>
                  <th class="py-3 text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($users)): ?>
                  <tr>
                    <td colspan="6" class="text-center py-5">
                      <i class="fas fa-users text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                      <p class="text-muted mt-2">No users found.</p>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($users as $u): ?>
                    <tr>
                      <td class="px-4 py-3"><?= $u['id'] ?></td>
                      <td class="py-3">
                        <div class="d-flex align-items-center">
                          <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                            <?= strtoupper(substr($u['name'], 0, 1)) ?>
                          </div>
                          <span class="fw-medium"><?= esc($u['name']) ?></span>
                        </div>
                      </td>
                      <td class="py-3"><?= esc($u['email']) ?></td>
                      <td class="py-3">
                        <?php 
                        $role = strtolower($u['role']);
                        $badgeClass = match($role) {
                          'admin' => 'bg-danger',
                          'teacher' => 'bg-success',
                          'student' => 'bg-info',
                          default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?> px-3 py-2"><?= ucfirst($u['role']) ?></span>
                      </td>
                      <td class="py-3">
                        <small class="text-muted">
                          <?= isset($u['created_at']) ? date('M d, Y', strtotime($u['created_at'])) : 'N/A' ?>
                        </small>
                      </td>
                      <td class="py-3 text-center">
                        <?php if (isset($u['is_deleted']) && $u['is_deleted'] == 1): ?>
                          <span class="badge bg-danger me-2">Deleted</span>
                          <button class="btn btn-sm btn-outline-success" onclick="confirmRestore(<?= $u['id'] ?>, '<?= esc($u['name']) ?>')" title="Restore">
                            <i class="fas fa-undo"></i>
                          </button>
                        <?php elseif ($u['id'] != session('user_id')): ?>
                          <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(<?= $u['id'] ?>)" title="Edit">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?= $u['id'] ?>, '<?= esc($u['name']) ?>')" title="Delete">
                            <i class="fas fa-trash"></i>
                          </button>
                        <?php else: ?>
                          <button class="btn btn-sm btn-secondary me-1" disabled title="Cannot edit yourself">
                            <i class="fas fa-lock"></i>
                          </button>
                          <span class="badge bg-warning text-dark">You</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
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
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="createUserModalLabel"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="<?= base_url('admin/users/create') ?>" method="POST" id="createUserForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <div class="mb-3">
                <label for="createName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="createName" name="name" required pattern="^[a-zA-Z\s]+$">
                <div class="invalid-feedback" id="createNameError">Name contains invalid characters.</div>
              </div>
              <div class="mb-3">
                <label for="createEmail" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="createEmail" name="email" required>
                <div class="invalid-feedback" id="createEmailError">Please enter a valid email address.</div>
              </div>
              <div class="mb-3">
                <label for="createPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="createPassword" name="password" required>
              </div>
              <div class="mb-3">
                <label for="createRole" class="form-label">Role</label>
                <select class="form-select" id="createRole" name="role" required>
                  <option value="">Select Role</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create User</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="editUserModalLabel"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editUserForm" action="" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body">
              <div class="mb-3">
                <label for="editName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="editName" name="name" required pattern="^[a-zA-Z\s]+$">
                <div class="invalid-feedback" id="editNameError">Name contains invalid characters.</div>
              </div>
              <div class="mb-3">
                <label for="editEmail" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="editEmail" name="email" required>
                <div class="invalid-feedback" id="editEmailError">Please enter a valid email address.</div>
              </div>
              <div class="mb-3">
                <label for="editPassword" class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                <input type="password" class="form-control" id="editPassword" name="password">
              </div>
              <div class="mb-3">
                <label for="editRole" class="form-label">Role</label>
                <select class="form-select" id="editRole" name="role" required>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteUserModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="mb-0">Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
            <p class="text-muted small mt-2 mb-0"><i class="fas fa-info-circle me-1"></i>The user will be marked as deleted and won't be able to login. You can restore them later.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a href="#" id="deleteUserBtn" class="btn btn-danger"><i class="fas fa-trash me-2"></i>Delete User</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreUserModal" tabindex="-1" aria-labelledby="restoreUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="restoreUserModalLabel"><i class="fas fa-undo me-2"></i>Restore User</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="mb-0">Are you sure you want to restore <strong id="restoreUserName"></strong>?</p>
            <p class="text-muted small mt-2 mb-0"><i class="fas fa-info-circle me-1"></i>The user will be able to login again.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a href="#" id="restoreUserBtn" class="btn btn-success"><i class="fas fa-undo me-2"></i>Restore User</a>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
      const searchValue = this.value.toLowerCase();
      const tableRows = document.querySelectorAll('#usersTable tbody tr');
      
      tableRows.forEach(function(row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
      });
    });

    // Edit user function
    function editUser(userId) {
      const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
      
      fetch('<?= base_url('admin/users/get/') ?>' + userId)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('editName').value = data.user.name;
            document.getElementById('editEmail').value = data.user.email;
            document.getElementById('editRole').value = data.user.role;
            document.getElementById('editPassword').value = '';
            document.getElementById('editUserForm').action = '<?= base_url('admin/users/update/') ?>' + userId;
            modal.show();
          } else {
            alert('Failed to load user data');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error loading user data');
        });
    }

    // Delete confirmation function
    function confirmDelete(userId, userName) {
      document.getElementById('deleteUserName').textContent = userName;
      document.getElementById('deleteUserBtn').href = '<?= base_url('admin/users/delete/') ?>' + userId;
      const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
      modal.show();
    }

    // Restore confirmation function
    function confirmRestore(userId, userName) {
      document.getElementById('restoreUserName').textContent = userName;
      document.getElementById('restoreUserBtn').href = '<?= base_url('admin/users/restore/') ?>' + userId;
      const modal = new bootstrap.Modal(document.getElementById('restoreUserModal'));
      modal.show();
    }

    // Validation functions
    function validateName(name) {
      return /^[a-zA-Z\s]+$/.test(name);
    }

    function validateEmail(email) {
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return false;
      }
      if (/[\/\'"\\\;\<\>]/.test(email)) {
        return false;
      }
      if (email.indexOf('@gmail.com') === -1) {
        return false;
      }
      return true;
    }

    // Create User Form Validation
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
      const name = document.getElementById('createName').value.trim();
      const email = document.getElementById('createEmail').value.trim();
      const nameInput = document.getElementById('createName');
      const emailInput = document.getElementById('createEmail');
      const nameError = document.getElementById('createNameError');
      const emailError = document.getElementById('createEmailError');
      let isValid = true;

      nameInput.classList.remove('is-invalid');
      emailInput.classList.remove('is-invalid');

      if (!validateName(name)) {
        nameInput.classList.add('is-invalid');
        nameError.textContent = 'Name contains invalid characters.';
        isValid = false;
      }

      if (!validateEmail(email)) {
        emailInput.classList.add('is-invalid');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          emailError.textContent = 'Please enter a valid email address.';
        } else if (/[\/\'"\\\;\<\>]/.test(email)) {
          emailError.textContent = 'Invalid email format.';
        } else if (email.indexOf('@gmail.com') === -1) {
          emailError.textContent = 'Please enter a valid email address.';
        } else {
          emailError.textContent = 'Invalid email format.';
        }
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
        return false;
      }
    });

    // Edit User Form Validation
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
      const name = document.getElementById('editName').value.trim();
      const email = document.getElementById('editEmail').value.trim();
      const nameInput = document.getElementById('editName');
      const emailInput = document.getElementById('editEmail');
      const nameError = document.getElementById('editNameError');
      const emailError = document.getElementById('editEmailError');
      let isValid = true;

      nameInput.classList.remove('is-invalid');
      emailInput.classList.remove('is-invalid');

      if (!validateName(name)) {
        nameInput.classList.add('is-invalid');
        nameError.textContent = 'Name contains invalid characters.';
        isValid = false;
      }

      if (!validateEmail(email)) {
        emailInput.classList.add('is-invalid');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          emailError.textContent = 'Please enter a valid email address.';
        } else if (/[\/\'"\\\;\<\>]/.test(email)) {
          emailError.textContent = 'Invalid email format.';
        } else if (email.indexOf('@gmail.com') === -1) {
          emailError.textContent = 'Please enter a valid email address.';
        } else {
          emailError.textContent = 'Invalid email format.';
        }
        isValid = false;
      }

      if (!isValid) {
        e.preventDefault();
        return false;
      }
    });
    </script>
    <?php
          break;
        case 'teacher':
          // Teacher Dashboard Content
    ?>
    <div class="container py-4">
        <h3 class="mb-4 fw-bold" style="color: var(--bs-text-dark);">
            <i class="fas fa-chalkboard-teacher text-primary-custom me-2"></i>Welcome, Teacher!
        </h3>

        <!-- My Courses Card -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-book text-primary-custom me-2"></i>My Courses
                        <?php if (!empty($courses)): ?>
                            <span class="badge bg-primary-custom text-white ms-2"><?= count($courses) ?></span>
                        <?php endif; ?>
                    </h5>
                    <a href="<?= base_url('teacher/enrollments') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus me-2"></i>Manage Enrollments
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($courses)): ?>
                    <div class="row g-3">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card border h-100 shadow-sm">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title text-primary-custom fw-bold mb-2">
                                            <?= esc($course['title']) ?>
                                        </h6>
                                        <p class="card-text text-muted small mb-3 flex-grow-1">
                                            <?= esc($course['description']) ?>
                                        </p>
                                        <div class="mt-auto">
                                            <div class="d-flex gap-2 mb-2">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-tag me-1"></i><?= esc($course['category'] ?? 'General') ?>
                                                </span>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-signal me-1"></i><?= ucfirst($course['level'] ?? 'beginner') ?>
                                                </span>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('materials/upload/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                                    <i class="fas fa-upload me-1"></i>Upload Materials
                                                </a>
                                                <a href="<?= base_url('teacher/enrollments?course_id=' . $course['id']) ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-users me-1"></i>Students
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-book-open text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                        <p class="text-muted mb-0">No courses available yet.</p>
                        <p class="text-muted small">Courses will appear here once they are created.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- New Submissions Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                    <i class="fas fa-file-alt text-primary-custom me-2"></i>New Submissions
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">No new submissions.</p>
            </div>
        </div>
    </div>
    <?php
          break;
        case 'student':
          // Student Dashboard Content
    ?>
    <div class="container py-4" style="max-height: calc(100vh - 200px); overflow-y: auto;">
      <h3 class="mb-3 text-primary">Student Dashboard</h3>
      
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
      

      <!-- Enrolled Courses Section -->
      <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
          <span>My Enrolled Courses</span>
          <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-primary"><?= count($enrollments ?? []) ?></span>
          </div>
        </div>
        <div class="card-body">
          <?php if (!empty($enrollments)): ?>
            <div class="row g-3">
              <?php foreach ($enrollments as $enrollment): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card border-0 bg-light">
                    <div class="card-body">
                      <h6 class="card-title text-primary"><?= esc($enrollment['course_title']) ?></h6>
                      <p class="card-text text-muted small"><?= esc($enrollment['course_description']) ?></p>
                      <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                          Enrolled: <?= date('M d, Y', strtotime($enrollment['enrollment_date'])) ?>
                        </small>
                        <span class="badge bg-<?= $enrollment['status'] === 'active' ? 'success' : 'secondary' ?>">
                          <?= ucfirst($enrollment['status']) ?>
                        </span>
                      </div>
                      <div class="mt-2">
                        <a href="<?= base_url('student/materials/' . $enrollment['course_id']) ?>" class="btn btn-sm btn-outline-primary">
                          <i class="fas fa-download"></i> View Materials
                        </a>
                      </div>
                      <div class="mt-2">
                        <div class="progress" style="height: 6px;">
                          <div class="progress-bar" role="progressbar" style="width: <?= $enrollment['progress'] ?>%">
                          </div>
                        </div>
                        <small class="text-muted"><?= number_format($enrollment['progress'], 1) ?>% Complete</small>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
              <p class="text-muted">No enrolled courses yet.</p>
              <p class="text-muted small">Browse available courses below to get started!</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Available Courses Section -->
      <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
          <span>Available Courses</span>
          <span class="badge bg-secondary"><?= count($available_courses ?? []) ?></span>
        </div>
        <div class="card-body">
          <?php if (!empty($available_courses)): ?>
            <div class="row g-3">
              <?php foreach ($available_courses as $course): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card border-0 bg-light">
                    <div class="card-body">
                      <h6 class="card-title text-primary"><?= esc($course['title']) ?></h6>
                      <p class="card-text text-muted small"><?= esc($course['description']) ?></p>
                      <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                          Instructor: <?= esc($course['instructor_name'] ?? 'TBA') ?>
                        </small>
                        <form method="POST" action="<?= base_url('student/enroll') ?>" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-primary">
                              <i class="fas fa-plus"></i> Enroll
                            </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
              <p class="text-muted">No available courses at the moment.</p>
              <p class="text-muted small">Check back later for new courses!</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

  <!-- Quick Stats Row -->
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold">Upcoming Deadlines</div>
        <div class="card-body">
          <p class="text-muted mb-0">No deadlines.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold">Recent Grades</div>
        <div class="card-body">
          <p class="text-muted mb-0">No grades available.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold">Overall Progress</div>
        <div class="card-body">
          <div class="text-center">
            <h4 class="text-primary mb-0"><?= number_format($overall_progress ?? 0, 1) ?>%</h4>
            <small class="text-muted">Average completion</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Enrollment Success/Error Messages -->
<div id="enrollment-message" class="alert alert-dismissible fade" role="alert" style="display: none;">
  <span id="enrollment-message-text"></span>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<script>
$(document).ready(function() {
    console.log('jQuery is loaded and document is ready!'); // Test if jQuery is working
    
    // No JavaScript needed - let forms submit naturally
    
    function showMessage(message, type) {
        const $messageDiv = $('#enrollment-message');
        const $messageText = $('#enrollment-message-text');
        
        $messageDiv.removeClass('alert-success alert-danger')
                  .addClass(`alert-${type}`)
                  .addClass('show')
                  .show();
        
        $messageText.text(message);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $messageDiv.fadeOut();
        }, 5000);
    }
    
    function updateEnrolledCoursesList(courseId, courseTitle) {
        // Create new enrollment card
        const newEnrollmentCard = `
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h6 class="card-title text-primary">${courseTitle}</h6>
                        <p class="card-text text-muted small">Course description will be loaded on page refresh.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Enrolled: ${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            </small>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted">0.0% Complete</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add to enrolled courses section
        const $enrolledSection = $('.card:contains("My Enrolled Courses") .card-body .row');
        if ($enrolledSection.length > 0) {
            $enrolledSection.append(newEnrollmentCard);
        } else {
            // If no enrolled courses yet, replace the empty state
            const $enrolledCard = $('.card:contains("My Enrolled Courses") .card-body');
            $enrolledCard.html(`
                <div class="row g-3">
                    ${newEnrollmentCard}
                </div>
            `);
        }
        
        // Update enrolled courses count
        const currentCount = parseInt($('.card:contains("My Enrolled Courses") .badge').text()) || 0;
        $('.card:contains("My Enrolled Courses") .badge').text(currentCount + 1);
    }
    
    function updateAvailableCoursesCount() {
        const $availableCards = $('.card:contains("Available Courses") .card-body .row .col-md-6, .card:contains("Available Courses") .card-body .row .col-lg-4');
        const currentCount = $availableCards.length;
        $('.card:contains("Available Courses") .badge').text(currentCount);
        
        // If no available courses left, show empty state
        if (currentCount === 0) {
            $('.card:contains("Available Courses") .card-body').html(`
                <div class="text-center py-4">
                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No available courses at the moment.</p>
                    <p class="text-muted small">Check back later for new courses!</p>
                </div>
            `);
        }
    }
});
</script>
    <?php
          break;
        default:
          echo '<div class="alert alert-warning mt-3">Role not recognized.</div>';
          break;
      }
    ?>
</div>
<?= $this->endSection() ?>
