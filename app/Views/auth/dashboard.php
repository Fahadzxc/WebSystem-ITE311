x<?= $this->extend('template') ?>

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

      <!-- Teacher Course Assignment Section -->
      <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0 fw-semibold"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Course Assignment</h5>
              <p class="text-muted small mb-0 mt-1">Assign teachers to courses</p>
            </div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCourseModal">
              <i class="fas fa-plus me-2"></i>Add New Course
            </button>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="px-4 py-3">Course ID</th>
                  <th class="py-3">Course Title</th>
                  <th class="py-3">Current Teacher</th>
                  <th class="py-3">Schedule</th>
                  <th class="py-3">Status</th>
                  <th class="py-3 text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($courses)): ?>
                  <tr>
                    <td colspan="6" class="text-center py-5">
                      <i class="fas fa-book text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                      <p class="text-muted mt-2">No courses found.</p>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($courses as $course): ?>
                    <tr>
                      <td class="px-4 py-3"><?= $course['id'] ?></td>
                      <td class="py-3">
                        <strong><?= esc($course['title']) ?></strong>
                        <?php if (!empty($course['description'])): ?>
                          <br><small class="text-muted"><?= esc(substr($course['description'], 0, 60)) ?><?= strlen($course['description']) > 60 ? '...' : '' ?></small>
                        <?php endif; ?>
                      </td>
                      <td class="py-3">
                        <?php if (!empty($course['instructor_name']) && $course['instructor_name'] !== 'Unassigned'): ?>
                          <span class="badge bg-success"><?= esc($course['instructor_name']) ?></span>
                        <?php else: ?>
                          <span class="badge bg-secondary">Unassigned</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-3">
                        <?php 
                        $scheduleModel = new \App\Models\CourseScheduleModel();
                        $schedules = $scheduleModel->getSchedulesByCourse($course['id']);
                        if (!empty($schedules)): 
                        ?>
                          <div class="d-flex flex-column gap-1">
                            <?php foreach ($schedules as $schedule): ?>
                              <small class="text-muted">
                                <i class="fas fa-calendar-day me-1"></i><?= esc($schedule['day_of_week']) ?><br>
                                <i class="fas fa-clock me-1"></i><?= date('g:i A', strtotime($schedule['start_time'])) ?> - <?= date('g:i A', strtotime($schedule['end_time'])) ?>
                              </small>
                            <?php endforeach; ?>
                          </div>
                        <?php else: ?>
                          <span class="badge bg-warning text-dark">No Schedule</span>
                        <?php endif; ?>
                      </td>
                      <td class="py-3">
                        <?php 
                        $statusClass = match($course['status'] ?? 'draft') {
                          'published' => 'bg-success',
                          'draft' => 'bg-warning',
                          'archived' => 'bg-secondary',
                          default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($course['status'] ?? 'draft') ?></span>
                      </td>
                      <td class="py-3 text-center">
                        <button class="btn btn-sm btn-outline-primary" onclick="assignTeacher(<?= $course['id'] ?>, '<?= esc($course['title']) ?>', <?= $course['instructor_id'] ?? 'null' ?>)">
                          <i class="fas fa-user-edit me-1"></i>Assign Teacher
                        </button>
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

    <!-- Create Course Modal -->
    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="createCourseModalLabel"><i class="fas fa-book-plus me-2"></i>Add New Course</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="<?= base_url('admin/courses/create') ?>" method="POST" id="createCourseForm">
            <?= csrf_field() ?>
            <div class="modal-body">
              <div class="mb-3">
                <label for="courseTitle" class="form-label">Course Title <span class="text-danger">*</span></label>
                <select class="form-select" id="courseTitle" name="title" required>
                  <option value="">-- Select Course Title --</option>
                  <?php
                  // Define all available course templates
                  $allCourseTemplates = [
                    'HTML and CSS Basics',
                    'MySQL Database Design',
                    'Object-Oriented Programming',
                    'RESTful API Development',
                    'Software Engineering Principles'
                  ];
                  
                  // Get existing course titles (case-insensitive comparison)
                  $existingTitles = [];
                  if (!empty($courses)) {
                    foreach ($courses as $course) {
                      $existingTitles[] = strtolower(trim($course['title']));
                    }
                  }
                  
                  // Only show courses that don't exist yet
                  foreach ($allCourseTemplates as $template) {
                    if (!in_array(strtolower(trim($template)), $existingTitles)) {
                      echo '<option value="' . esc($template) . '">' . esc($template) . '</option>';
                    }
                  }
                  ?>
                </select>
                <small class="text-muted">Course title must be unique. Duplicate titles will be rejected.</small>
                <div class="invalid-feedback" id="courseTitleError"></div>
              </div>
              <div class="mb-3">
                <label for="courseDescription" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="courseDescription" name="description" rows="4" required></textarea>
                <small class="text-muted">Description will be automatically filled based on selected course title. You can edit it if needed.</small>
                <div class="invalid-feedback" id="courseDescriptionError"></div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="courseCategory" class="form-label">Category <span class="text-danger">*</span></label>
                  <select class="form-select" id="courseCategory" name="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Web Development">Web Development</option>
                    <option value="Database">Database</option>
                    <option value="Programming">Programming</option>
                    <option value="Framework">Framework</option>
                    <option value="Frontend">Frontend</option>
                    <option value="Backend">Backend</option>
                    <option value="Full Stack">Full Stack</option>
                    <option value="Software Engineering">Software Engineering</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="courseLevel" class="form-label">Level</label>
                  <select class="form-select" id="courseLevel" name="level">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="courseStatus" class="form-label">Status</label>
                <select class="form-select" id="courseStatus" name="status">
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                  <option value="archived">Archived</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create Course</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Assign Teacher Modal -->
    <div class="modal fade" id="assignTeacherModal" tabindex="-1" aria-labelledby="assignTeacherModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="assignTeacherModalLabel"><i class="fas fa-chalkboard-teacher me-2"></i>Assign Teacher to Course</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="assignTeacherForm" action="" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Course</label>
                <input type="text" class="form-control" id="assignCourseTitle" readonly>
                <input type="hidden" id="assignCourseId" name="course_id">
              </div>
              <div class="mb-3">
                <label for="assignTeacherSelect" class="form-label">Select Teacher</label>
                <select class="form-select" id="assignTeacherSelect" name="teacher_id" required>
                  <option value="">-- Select Teacher --</option>
                  <?php if (!empty($teachers)): ?>
                    <?php foreach ($teachers as $teacher): ?>
                      <option value="<?= $teacher['id'] ?>"><?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)</option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
                <?php if (empty($teachers)): ?>
                  <small class="text-danger">No teachers available. Please create a teacher account first.</small>
                <?php endif; ?>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Course Schedule</label>
                <div class="mb-3">
                  <label class="form-label">Select Days</label>
                  <div class="row g-2">
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Monday" id="dayMonday">
                        <label class="form-check-label" for="dayMonday">Monday</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Tuesday" id="dayTuesday">
                        <label class="form-check-label" for="dayTuesday">Tuesday</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Wednesday" id="dayWednesday">
                        <label class="form-check-label" for="dayWednesday">Wednesday</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Thursday" id="dayThursday">
                        <label class="form-check-label" for="dayThursday">Thursday</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Friday" id="dayFriday">
                        <label class="form-check-label" for="dayFriday">Friday</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Saturday" id="daySaturday">
                        <label class="form-check-label" for="daySaturday">Saturday</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-check">
                        <input class="form-check-input schedule-day" type="checkbox" value="Sunday" id="daySunday">
                        <label class="form-check-label" for="daySunday">Sunday</label>
                      </div>
                    </div>
                  </div>
                  <small class="text-muted">Select one or more days for the same time schedule.</small>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="assignStartTime" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="assignStartTime" name="start_time" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="assignEndTime" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="assignEndTime" name="end_time" required>
                  </div>
                </div>
              </div>
              <div id="scheduleConflictAlert" class="alert alert-warning d-none" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><span id="conflictMessage"></span>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="removeTeacherBtn" onclick="removeTeacherFromCourse()" style="display: none;">
                <i class="fas fa-user-times me-2"></i>Remove Teacher
              </button>
              <button type="submit" class="btn btn-primary" id="assignTeacherSubmitBtn"><i class="fas fa-save me-2"></i>Assign Teacher</button>
            </div>
          </form>
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
      const role = document.getElementById('createRole').value;
      const nameInput = document.getElementById('createName');
      const emailInput = document.getElementById('createEmail');
      const roleInput = document.getElementById('createRole');
      const nameError = document.getElementById('createNameError');
      const emailError = document.getElementById('createEmailError');
      let isValid = true;

      nameInput.classList.remove('is-invalid');
      emailInput.classList.remove('is-invalid');
      roleInput.classList.remove('is-invalid');

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

      // Validate role is selected
      if (!role || role === '') {
        roleInput.classList.add('is-invalid');
        isValid = false;
        alert('Please select a role.');
      }

      if (!isValid) {
        e.preventDefault();
        return false;
      }

      // Debug: Log the role being submitted
      console.log('Submitting form with role:', role);
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

    // Assign Teacher function
    function assignTeacher(courseId, courseTitle, currentTeacherId) {
      const modal = new bootstrap.Modal(document.getElementById('assignTeacherModal'));
      const removeBtn = document.getElementById('removeTeacherBtn');
      document.getElementById('assignCourseId').value = courseId;
      document.getElementById('assignCourseTitle').value = courseTitle;
      document.getElementById('assignTeacherSelect').value = currentTeacherId || '';
      document.getElementById('assignTeacherForm').action = '<?= base_url('admin/courses/assign-teacher') ?>';
      
      // Show/hide remove button based on whether teacher is assigned
      if (currentTeacherId && currentTeacherId != 0 && currentTeacherId != 'null') {
        removeBtn.style.display = 'inline-block';
      } else {
        removeBtn.style.display = 'none';
      }
      
      // Clear all checkboxes and time fields
      document.querySelectorAll('.schedule-day').forEach(cb => cb.checked = false);
      document.getElementById('assignStartTime').value = '';
      document.getElementById('assignEndTime').value = '';
      
      // Load existing schedules if teacher is assigned
      if (currentTeacherId && currentTeacherId != 0 && currentTeacherId != 'null') {
        fetch('<?= base_url('admin/courses/get-schedules') ?>?course_id=' + courseId)
          .then(response => response.json())
          .then(data => {
            if (data.schedules && data.schedules.length > 0) {
              // Get unique start and end times (should be same for all)
              const firstSchedule = data.schedules[0];
              document.getElementById('assignStartTime').value = firstSchedule.start_time;
              document.getElementById('assignEndTime').value = firstSchedule.end_time;
              
              // Check the day checkboxes
              data.schedules.forEach(schedule => {
                const checkbox = document.getElementById('day' + schedule.day_of_week);
                if (checkbox) {
                  checkbox.checked = true;
                }
              });
            }
          })
          .catch(error => {
            console.error('Error loading schedules:', error);
          });
      }
      
      // Hide conflict alert initially and enable submit button
      document.getElementById('scheduleConflictAlert').classList.add('d-none');
      document.getElementById('assignTeacherSubmitBtn').disabled = false;
      
      modal.show();
    }

    // Remove Teacher from Course function
    function removeTeacherFromCourse() {
      const courseId = document.getElementById('assignCourseId').value;
      const courseTitle = document.getElementById('assignCourseTitle').value;
      
      if (!confirm(`Are you sure you want to remove the teacher from "${courseTitle}"?`)) {
        return;
      }

      const formData = new FormData();
      formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
      formData.append('course_id', courseId);
      // Send empty teacher_id to trigger removal
      formData.append('teacher_id', '');

      fetch('<?= base_url('admin/courses/assign-teacher') ?>', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => {
        if (response.redirected) {
          // If redirected, it means it's not AJAX, reload the page
          location.reload();
        } else {
          return response.json();
        }
      })
      .then(data => {
        if (data && data.success !== undefined) {
          if (data.success) {
            // Close modal and reload page
            const modal = bootstrap.Modal.getInstance(document.getElementById('assignTeacherModal'));
            modal.hide();
            location.reload();
          } else {
            alert('Failed to remove teacher: ' + (data.message || 'Unknown error'));
          }
        }
      })
      .catch(error => {
        console.error('Error removing teacher:', error);
        // If error, just reload the page
        location.reload();
      });
    }
    
    // Check schedule conflicts
    function checkScheduleConflicts() {
      const teacherId = document.getElementById('assignTeacherSelect').value;
      const courseId = document.getElementById('assignCourseId').value;
      const startTime = document.getElementById('assignStartTime').value;
      const endTime = document.getElementById('assignEndTime').value;
      const conflictAlert = document.getElementById('scheduleConflictAlert');
      const conflictMessage = document.getElementById('conflictMessage');
      const submitBtn = document.getElementById('assignTeacherSubmitBtn');
      
      if (!teacherId || !startTime || !endTime) {
        conflictAlert.classList.add('d-none');
        submitBtn.disabled = false;
        return;
      }
      
      // Validate end time is after start time
      if (endTime <= startTime) {
        conflictAlert.classList.remove('d-none');
        conflictMessage.textContent = 'End time must be after start time.';
        submitBtn.disabled = true;
        return;
      }
      
      // Get selected days
      const selectedDays = [];
      document.querySelectorAll('.schedule-day:checked').forEach(cb => {
        selectedDays.push(cb.value);
      });
      
      if (selectedDays.length === 0) {
        conflictAlert.classList.add('d-none');
        submitBtn.disabled = false;
        return;
      }
      
      // Build schedules array (same time for all selected days)
      const schedules = selectedDays.map(day => ({
        day_of_week: day,
        start_time: startTime,
        end_time: endTime
      }));
      
      // Check for conflicts via AJAX
      fetch('<?= base_url('admin/courses/check-conflict') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          teacher_id: teacherId,
          schedules: schedules,
          course_id: courseId,
          <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.has_conflict) {
          conflictAlert.classList.remove('d-none');
          conflictMessage.textContent = data.message || 'Schedule conflict detected! This teacher already has a class at this time.';
          submitBtn.disabled = true;
        } else {
          conflictAlert.classList.add('d-none');
          submitBtn.disabled = false;
        }
      })
      .catch(error => {
        console.error('Error checking conflict:', error);
        conflictAlert.classList.add('d-none');
        submitBtn.disabled = false;
      });
    }
    
    // Add event listeners when modal is shown
    document.getElementById('assignTeacherModal').addEventListener('shown.bs.modal', function() {
      // Add listeners to day checkboxes
      document.querySelectorAll('.schedule-day').forEach(cb => {
        cb.addEventListener('change', checkScheduleConflicts);
      });
      
      // Add listeners to time fields
      document.getElementById('assignStartTime').addEventListener('change', checkScheduleConflicts);
      document.getElementById('assignEndTime').addEventListener('change', checkScheduleConflicts);
      
      // Add listener to teacher select
      document.getElementById('assignTeacherSelect').addEventListener('change', checkScheduleConflicts);
    });
    
    // Auto-fill course description based on selected title
    const courseTitleSelect = document.getElementById('courseTitle');
    const courseDescriptionTextarea = document.getElementById('courseDescription');
    const courseCategorySelect = document.getElementById('courseCategory');
    
    // Course templates - only include courses that are available in the dropdown
    const courseTemplates = {
      <?php
      $allTemplates = [
        'HTML and CSS Basics' => [
          'description' => 'Learn the building blocks of web development. Master HTML structure and CSS styling to create beautiful web pages.',
          'category' => 'Frontend'
        ],
        'MySQL Database Design' => [
          'description' => 'Learn database design principles, normalization, and SQL queries. Master MySQL for building robust database systems.',
          'category' => 'Database'
        ],
        'Object-Oriented Programming' => [
          'description' => 'Understand OOP concepts, classes, inheritance, polymorphism, and design patterns. Apply OOP principles in real-world projects.',
          'category' => 'Programming'
        ],
        'RESTful API Development' => [
          'description' => 'Learn to design and build RESTful APIs. Understand HTTP methods, status codes, authentication, and API best practices.',
          'category' => 'Backend'
        ],
        'Software Engineering Principles' => [
          'description' => 'Master software engineering fundamentals including system design, testing, version control, and project management methodologies.',
          'category' => 'Software Engineering'
        ]
      ];
      
      // Get existing course titles
      $existingTitles = [];
      if (!empty($courses)) {
        foreach ($courses as $course) {
          $existingTitles[] = strtolower(trim($course['title']));
        }
      }
      
      // Only output templates for courses that don't exist yet
      $templateOutput = [];
      foreach ($allTemplates as $title => $data) {
        if (!in_array(strtolower(trim($title)), $existingTitles)) {
          $templateOutput[] = "'" . esc($title, 'js') . "': {" .
            "description: '" . esc($data['description'], 'js') . "'," .
            "category: '" . esc($data['category'], 'js') . "'" .
            "}";
        }
      }
      echo implode(",\n      ", $templateOutput);
      ?>
    };
    
    if (courseTitleSelect) {
      courseTitleSelect.addEventListener('change', function() {
        const selectedTitle = this.value;
        if (selectedTitle && courseTemplates[selectedTitle]) {
          courseDescriptionTextarea.value = courseTemplates[selectedTitle].description;
          courseCategorySelect.value = courseTemplates[selectedTitle].category;
        } else {
          courseDescriptionTextarea.value = '';
          courseCategorySelect.value = '';
        }
      });
    }

    // Handle form submission - collect selected days and create schedules
    document.getElementById('assignTeacherForm').addEventListener('submit', function(e) {
      const startTime = document.getElementById('assignStartTime').value;
      const endTime = document.getElementById('assignEndTime').value;
      const selectedDays = [];
      
      // Get all checked days
      document.querySelectorAll('.schedule-day:checked').forEach(cb => {
        selectedDays.push(cb.value);
      });
      
      if (selectedDays.length === 0) {
        e.preventDefault();
        alert('Please select at least one day for the schedule.');
        return false;
      }
      
      if (!startTime || !endTime) {
        e.preventDefault();
        alert('Please provide both start time and end time.');
        return false;
      }
      
      // Create schedules array (same time for all selected days)
      const schedules = selectedDays.map(day => ({
        day_of_week: day,
        start_time: startTime,
        end_time: endTime
      }));
      
      // Add hidden input for schedules
      const schedulesInput = document.createElement('input');
      schedulesInput.type = 'hidden';
      schedulesInput.name = 'schedules';
      schedulesInput.value = JSON.stringify(schedules);
      this.appendChild(schedulesInput);
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

        <!-- Pending Enrollment Requests Alert -->
        <?php if (!empty($pendingEnrollments ?? [])): ?>
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">You have <?= count($pendingEnrollments) ?> pending enrollment request<?= count($pendingEnrollments) > 1 ? 's' : '' ?>!</h5>
                        <p class="mb-0">Students are waiting for your approval. <a href="<?= base_url('teacher/enrollments') ?>" class="alert-link fw-bold">Click here to review and approve/reject requests</a></p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

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
                                        
                                        <!-- Schedule Information -->
                                        <?php if (!empty($course['schedules'])): ?>
                                            <div class="mb-3 p-2 bg-light rounded">
                                                <small class="text-muted d-block mb-1"><i class="fas fa-calendar-alt me-1"></i><strong>Schedule:</strong></small>
                                                <?php foreach ($course['schedules'] as $schedule): ?>
                                                    <div class="d-flex align-items-center mb-1">
                                                        <span class="badge bg-primary me-2"><?= esc($schedule['day_of_week']) ?></span>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <?= date('g:i A', strtotime($schedule['start_time'])) ?> - <?= date('g:i A', strtotime($schedule['end_time'])) ?>
                                                        </small>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="mb-3 p-2 bg-warning bg-opacity-10 rounded">
                                                <small class="text-muted"><i class="fas fa-exclamation-circle me-1"></i>No schedule assigned yet.</small>
                                            </div>
                                        <?php endif; ?>
                                        
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
      

      <!-- Pending Enrollment Requests Section -->
      <div class="card shadow-sm mb-4 border-warning">
        <div class="card-header bg-warning bg-opacity-10 fw-semibold d-flex justify-content-between align-items-center">
          <span><i class="fas fa-clock text-warning me-2"></i>Pending Enrollment Requests</span>
          <span class="badge <?= !empty($pendingEnrollments ?? []) ? 'bg-warning text-dark' : 'bg-secondary' ?>">
            <?= count($pendingEnrollments ?? []) ?>
          </span>
        </div>
        <div class="card-body">
          <?php if (!empty($pendingEnrollments ?? [])): ?>
            <div class="row g-3">
              <?php foreach ($pendingEnrollments as $enrollment): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card border-warning bg-light">
                    <div class="card-body">
                      <h6 class="card-title text-primary"><?= esc($enrollment['course_title'] ?? 'Unknown Course') ?></h6>
                      <p class="card-text text-muted small"><?= esc($enrollment['course_description'] ?? '') ?></p>
                      <div class="alert alert-warning alert-sm mt-2 mb-2 py-2">
                        <small><i class="fas fa-clock me-1"></i><strong>Status:</strong> Waiting for instructor approval...</small>
                      </div>
                      <small class="text-muted">
                        Requested: <?php 
                            $date = new \DateTime($enrollment['enrollment_date'] ?? 'now', new \DateTimeZone('UTC'));
                            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
                            echo $date->format('M d, Y');
                        ?>
                      </small>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
              <p class="text-muted mb-0">No pending enrollment requests.</p>
              <p class="text-muted small">Your enrollment requests will appear here while waiting for instructor approval.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Rejected Enrollment Requests Section -->
      <?php if (!empty($rejectedEnrollments ?? [])): ?>
        <div class="card shadow-sm mb-4 border-danger">
          <div class="card-header bg-danger bg-opacity-10 fw-semibold d-flex justify-content-between align-items-center">
            <span><i class="fas fa-times-circle text-danger me-2"></i>Rejected Enrollment Requests</span>
            <span class="badge bg-danger"><?= count($rejectedEnrollments) ?></span>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <?php foreach ($rejectedEnrollments as $enrollment): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card border-danger bg-light">
                    <div class="card-body">
                      <h6 class="card-title text-primary"><?= esc($enrollment['course_title']) ?></h6>
                      <p class="card-text text-muted small"><?= esc($enrollment['course_description']) ?></p>
                      <?php if (!empty($enrollment['rejection_reason'])): ?>
                        <div class="alert alert-danger alert-sm mt-2 mb-2 py-2">
                          <small><i class="fas fa-times-circle me-1"></i><strong>Reason:</strong> <?= esc($enrollment['rejection_reason']) ?></small>
                        </div>
                      <?php endif; ?>
                      <small class="text-muted">
                        Requested: <?= date('M d, Y', strtotime($enrollment['enrollment_date'])) ?>
                      </small>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Enrolled Courses Section (Active Only) -->
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
                          Enrolled: <?php 
                            $date = new \DateTime($enrollment['enrollment_date'], new \DateTimeZone('UTC'));
                            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
                            echo $date->format('M d, Y');
                          ?>
                        </small>
                        <span class="badge bg-success">
                          Active
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
                        <?php 
                        $enrollmentStatus = null;
                        // Check in active, pending, and rejected enrollments
                        foreach (array_merge($enrollments ?? [], $pendingEnrollments ?? [], $rejectedEnrollments ?? []) as $enrollment) {
                            if ($enrollment['course_id'] == $course['id']) {
                                $enrollmentStatus = $enrollment['status'];
                                break;
                            }
                        }
                        ?>
                        <?php if ($enrollmentStatus === 'pending'): ?>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i>Pending Approval
                            </span>
                        <?php elseif ($enrollmentStatus === 'rejected'): ?>
                            <span class="badge bg-danger">
                                <i class="fas fa-times me-1"></i>Rejected
                            </span>
                        <?php elseif (empty($course['instructor_id']) || $course['instructor_id'] == 0 || $course['instructor_id'] == null): ?>
                            <span class="badge bg-secondary">
                                <i class="fas fa-info-circle me-1"></i>No Instructor
                            </span>
                        <?php else: ?>
                            <form method="POST" action="<?= base_url('student/enroll') ?>" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-primary">
                                  <i class="fas fa-plus"></i> Enroll
                                </button>
                            </form>
                        <?php endif; ?>
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
