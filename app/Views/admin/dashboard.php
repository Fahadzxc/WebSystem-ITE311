<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Admin Dashboard</h1>
                <p class="page-subtitle">Welcome back, <?= $user_name ?>!</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3>150</h3>
                        <p>Total Students</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stats-content">
                        <h3>25</h3>
                        <p>Total Teachers</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stats-content">
                        <h3>45</h3>
                        <p>Total Courses</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stats-content">
                        <h3>320</h3>
                        <p>Total Enrollments</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-user-plus text-success"></i>
                            </div>
                            <div class="activity-content">
                                <p><strong>New student registered:</strong> John Doe</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div class="activity-content">
                                <p><strong>New course created:</strong> Advanced PHP Programming</p>
                                <small class="text-muted">4 hours ago</small>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-chalkboard-teacher text-warning"></i>
                            </div>
                            <div class="activity-content">
                                <p><strong>Teacher assigned:</strong> Jane Smith to Web Development</p>
                                <small class="text-muted">6 hours ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="#" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-plus"></i> Add New Course
                        </a>
                        <a href="#" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-user-plus"></i> Add New Teacher
                        </a>
                        <a href="#" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-chart-bar"></i> View Reports
                        </a>
                        <a href="#" class="btn btn-warning btn-block">
                            <i class="fas fa-cog"></i> System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.stats-card .card-body {
    display: flex;
    align-items: center;
    padding: 20px;
}

.stats-icon {
    font-size: 2.5rem;
    margin-right: 15px;
    color: #007bff;
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

.activity-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    font-size: 1.2rem;
    margin-right: 15px;
    width: 30px;
    text-align: center;
}

.activity-content p {
    margin: 0;
    font-size: 0.9rem;
}

.quick-actions .btn {
    text-align: left;
}

.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin: 0;
}

.page-subtitle {
    color: #666;
    margin: 5px 0 0 0;
}
</style>
<?= $this->endSection() ?>
