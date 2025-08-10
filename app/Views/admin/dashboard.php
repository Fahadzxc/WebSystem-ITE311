<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Mobile Sidebar Toggle Button -->
<button class="btn sidebar-toggle d-md-none">
    <i class="fas fa-bars"></i>
</button>

<!-- Dashboard Content -->
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">1,234</div>
                    <div class="stats-label">Total Students</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">89</div>
                    <div class="stats-label">Active Courses</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">45</div>
                    <div class="stats-label">Instructors</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">98.5%</div>
                    <div class="stats-label">Satisfaction Rate</div>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and Quick Actions -->
<div class="row">
    <!-- Recent Activity -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Activity
                </h5>
                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <div class="activity-item d-flex align-items-center py-3 border-bottom">
                        <div class="activity-icon me-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <div class="fw-bold">New Student Enrollment</div>
                            <div class="text-muted">John Doe enrolled in "Web Development Fundamentals"</div>
                            <small class="text-muted">2 minutes ago</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-center py-3 border-bottom">
                        <div class="activity-icon me-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <div class="fw-bold">Course Completed</div>
                            <div class="text-muted">Sarah Wilson completed "Advanced JavaScript"</div>
                            <small class="text-muted">1 hour ago</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-center py-3 border-bottom">
                        <div class="activity-icon me-3">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <div class="fw-bold">Pending Review</div>
                            <div class="text-muted">5 assignments waiting for instructor review</div>
                            <small class="text-muted">3 hours ago</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-center py-3">
                        <div class="activity-icon me-3">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                        </div>
                        <div class="activity-content flex-grow-1">
                            <div class="fw-bold">New Course Added</div>
                            <div class="text-muted">"Machine Learning Basics" course published</div>
                            <small class="text-muted">5 hours ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </a>
                    <a href="<?= base_url('admin/courses/create') ?>" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Create Course
                    </a>
                    <a href="<?= base_url('admin/reports') ?>" class="btn btn-info">
                        <i class="fas fa-chart-bar me-2"></i>Generate Report
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="btn btn-secondary">
                        <i class="fas fa-cog me-2"></i>System Settings
                    </a>
                </div>
            </div>
        </div>
        
        <!-- System Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-server me-2"></i>System Status
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Database</span>
                    <span class="badge bg-success">Online</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Storage</span>
                    <span class="badge bg-warning">75% Used</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Memory</span>
                    <span class="badge bg-info">45% Used</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>CPU</span>
                    <span class="badge bg-success">Normal</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users and Courses -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Recent Users
                </h5>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">John Doe</div>
                                            <small class="text-muted">john@example.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary">Student</span></td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>2 days ago</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-chalkboard-teacher text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Jane Smith</div>
                                            <small class="text-muted">jane@example.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">Instructor</span></td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>1 week ago</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Courses -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>Recent Courses
                </h5>
                <a href="<?= base_url('admin/courses') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Students</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">Web Development Fundamentals</div>
                                        <small class="text-muted">Programming</small>
                                    </div>
                                </td>
                                <td>Mike Johnson</td>
                                <td>45</td>
                                <td><span class="badge bg-success">Active</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">Data Science Basics</div>
                                        <small class="text-muted">Data Science</small>
                                    </div>
                                </td>
                                <td>Sarah Wilson</td>
                                <td>32</td>
                                <td><span class="badge bg-warning">Draft</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Dashboard-specific JavaScript can go here
document.addEventListener('DOMContentLoaded', function() {
    // Add any dashboard-specific functionality
    console.log('Dashboard loaded');
});
</script>
<?= $this->endSection() ?> 