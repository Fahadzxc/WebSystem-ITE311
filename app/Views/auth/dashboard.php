<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Welcome back, <?= $user_name ?>!</p>
                <p class="text-muted">Role: <span class="badge bg-primary"><?= ucfirst($role) ?></span></p>
            </div>
        </div>
    </div>

    <!-- Conditional Content Based on Role -->
    <?php if($role === 'admin'): ?>
        <!-- ADMIN DASHBOARD CONTENT -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $total_students ?? 0 ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-chalkboard-teacher text-success"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $total_teachers ?? 0 ?></h3>
                            <p>Total Teachers</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-user-friends text-info"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $total_users ?? 0 ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-cog text-warning"></i>
                        </div>
                        <div class="stats-content">
                            <h3>Admin</h3>
                            <p>System Admin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($recent_users) && !empty($recent_users)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($recent_users as $user): ?>
                                        <tr>
                                            <td><?= $user['name'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></td>
                                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No recent users found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-primary">Manage Users</a>
                            <a href="<?= base_url('admin/courses') ?>" class="btn btn-success">Manage Courses</a>
                            <a href="#" class="btn btn-info">View Reports</a>
                            <a href="#" class="btn btn-warning">System Settings</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif($role === 'instructor'): ?>
        <!-- INSTRUCTOR DASHBOARD CONTENT -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-book text-primary"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= count($my_courses ?? []) ?></h3>
                            <p>My Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-users text-success"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $total_students ?? 0 ?></h3>
                            <p>Total Students</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-tasks text-warning"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $pending_assignments ?? 0 ?></h3>
                            <p>Pending Assignments</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-star text-info"></i>
                        </div>
                        <div class="stats-content">
                            <h3>4.8</h3>
                            <p>Average Rating</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>My Courses</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($my_courses) && !empty($my_courses)): ?>
                            <div class="list-group">
                                <?php foreach($my_courses as $course): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $course['title'] ?? 'Course Title' ?></h6>
                                        <small><?= $course['students'] ?? 0 ?> students</small>
                                    </div>
                                    <p class="mb-1"><?= $course['description'] ?? 'Course description' ?></p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No courses assigned yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-primary">Create Course</a>
                            <a href="#" class="btn btn-success">Create Assignment</a>
                            <a href="#" class="btn btn-info">View Analytics</a>
                            <a href="#" class="btn btn-warning">Messages</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif($role === 'student'): ?>
        <!-- STUDENT DASHBOARD CONTENT -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-book text-primary"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= count($enrolled_courses ?? []) ?></h3>
                            <p>Enrolled Courses</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-tasks text-warning"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $pending_assignments ?? 0 ?></h3>
                            <p>Pending Assignments</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $completed_tasks ?? 0 ?></h3>
                            <p>Completed Tasks</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-trophy text-info"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?= $overall_progress ?? 0 ?>%</h3>
                            <p>Overall Progress</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>My Courses</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($enrolled_courses) && !empty($enrolled_courses)): ?>
                            <div class="list-group">
                                <?php foreach($enrolled_courses as $course): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $course['title'] ?? 'Course Title' ?></h6>
                                        <small><?= $course['progress'] ?? 0 ?>% complete</small>
                                    </div>
                                    <p class="mb-1"><?= $course['description'] ?? 'Course description' ?></p>
                                    <div class="progress mt-2">
                                        <div class="progress-bar" style="width: <?= $course['progress'] ?? 0 ?>%"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No enrolled courses yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Assignments</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">HTML/CSS Project</h6>
                                    <small class="text-warning">Due: Tomorrow</small>
                                </div>
                                <p class="mb-1">Web Development Assignment</p>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Database Quiz</h6>
                                    <small class="text-info">Due: 3 days</small>
                                </div>
                                <p class="mb-1">Database Design Quiz</p>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">PHP Assignment</h6>
                                    <small class="text-success">Completed</small>
                                </div>
                                <p class="mb-1">PHP Programming Assignment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- DEFAULT DASHBOARD CONTENT -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>Welcome to LMS System</h3>
                        <p class="text-muted">Your role: <?= ucfirst($role) ?></p>
                        <p>This is a default dashboard view.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Logout Button -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="<?= site_url('logout') ?>" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<style>
.stats-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    transition: transform 0.2s;
}

.stats-card:hover {
    transform: translateY(-2px);
}

.stats-card .card-body {
    display: flex;
    align-items: center;
    padding: 20px;
}

.stats-icon {
    font-size: 2.5rem;
    margin-right: 15px;
    width: 60px;
    text-align: center;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: bold;
    margin: 0;
    color: #333;
}

.stats-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.page-header {
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.page-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #333;
    margin: 0;
}

.page-subtitle {
    color: #666;
    margin: 5px 0;
    font-size: 1.1rem;
}

.progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 4px;
}

.progress-bar {
    background-color: #007bff;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.list-group-item {
    border: 1px solid #dee2e6;
    margin-bottom: 5px;
    border-radius: 5px;
}

.badge {
    font-size: 0.8rem;
}
</style>
<?= $this->endSection() ?>
