<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary mb-0">User Management</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="fas fa-plus me-2"></i>Add New User
        </button>
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

    <!-- Users Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 fw-semibold">All Users</h5>
                </div>
                <div class="col-auto">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search users..." style="width: 250px;">
                </div>
            </div>
        </div>
        <div class="card-body p-0">
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
                                            <!-- User is deleted - show restore button -->
                                            <span class="badge bg-danger me-2">Deleted</span>
                                            <button class="btn btn-sm btn-outline-success" onclick="confirmRestore(<?= $u['id'] ?>, '<?= esc($u['name']) ?>')" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        <?php elseif ($u['id'] != session('user_id')): ?>
                                            <!-- Can edit/delete other users -->
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(<?= $u['id'] ?>)" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?= $u['id'] ?>, '<?= esc($u['name']) ?>')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <!-- This is the logged-in admin - lock actions -->
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
    // Show loading state
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    
    // Fetch user data
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
    // Only letters and spaces allowed
    return /^[a-zA-Z\s]+$/.test(name);
}

function validateEmail(email) {
    // Check if valid email format
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return false;
    }
    
    // Check if contains invalid special characters
    if (/[\/\'"\\\;\<\>]/.test(email)) {
        return false;
    }
    
    // Must be @gmail.com
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

    // Reset previous errors
    nameInput.classList.remove('is-invalid');
    emailInput.classList.remove('is-invalid');

    // Validate name
    if (!validateName(name)) {
        nameInput.classList.add('is-invalid');
        nameError.textContent = 'Name contains invalid characters.';
        isValid = false;
    }

    // Validate email
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

    // Reset previous errors
    nameInput.classList.remove('is-invalid');
    emailInput.classList.remove('is-invalid');

    // Validate name
    if (!validateName(name)) {
        nameInput.classList.add('is-invalid');
        nameError.textContent = 'Name contains invalid characters.';
        isValid = false;
    }

    // Validate email
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
<?= $this->endSection() ?>
