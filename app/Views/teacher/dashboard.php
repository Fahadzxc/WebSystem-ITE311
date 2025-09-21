<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Teacher Dashboard</h1>
                <p class="page-subtitle">Welcome back, <?= $user_name ?>!</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stats-content">
                        <h3>8</h3>
                        <p>My Courses</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3>120</h3>
                        <p>Total Students</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stats-content">
                        <h3>15</h3>
                        <p>Pending Assignments</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
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
                    <div class="course-list">
                        <div class="course-item">
                            <div class="course-info">
                                <h6>Web Development Fundamentals</h6>
                                <p class="text-muted">45 students enrolled</p>
                            </div>
                            <div class="course-actions">
                                <a href="#" class="btn btn-sm btn-primary">View</a>
                                <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                            </div>
                        </div>
                        <div class="course-item">
                            <div class="course-info">
                                <h6>Database Design</h6>
                                <p class="text-muted">32 students enrolled</p>
                            </div>
                            <div class="course-actions">
                                <a href="#" class="btn btn-sm btn-primary">View</a>
                                <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                            </div>
                        </div>
                        <div class="course-item">
                            <div class="course-info">
                                <h6>PHP Programming</h6>
                                <p class="text-muted">28 students enrolled</p>
                            </div>
                            <div class="course-actions">
                                <a href="#" class="btn btn-sm btn-primary">View</a>
                                <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
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
                            <i class="fas fa-plus"></i> Create New Course
                        </a>
                        <a href="#" class="btn btn-success btn-block mb-2">
                            <i class="fas fa-file-alt"></i> Create Assignment
                        </a>
                        <a href="#" class="btn btn-info btn-block mb-2">
                            <i class="fas fa-chart-line"></i> View Analytics
                        </a>
                        <a href="#" class="btn btn-warning btn-block">
                            <i class="fas fa-comments"></i> Messages
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
    color: #28a745;
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

.course-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.course-item:last-child {
    border-bottom: none;
}

.course-info h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.course-info p {
    margin: 5px 0 0 0;
    font-size: 0.9rem;
}

.course-actions .btn {
    margin-left: 5px;
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
