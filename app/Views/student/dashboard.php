<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Student Dashboard</h1>
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
                        <h3>5</h3>
                        <p>Enrolled Courses</p>
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
                        <h3>12</h3>
                        <p>Pending Assignments</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-content">
                        <h3>8</h3>
                        <p>Completed Tasks</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stats-content">
                        <h3>85%</h3>
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
                    <div class="course-list">
                        <div class="course-item">
                            <div class="course-info">
                                <h6>Web Development Fundamentals</h6>
                                <p class="text-muted">Instructor: John Smith</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar" style="width: 75%"></div>
                                </div>
                                <small class="text-muted">75% Complete</small>
                            </div>
                            <div class="course-actions">
                                <a href="#" class="btn btn-sm btn-primary">Continue</a>
                            </div>
                        </div>
                        <div class="course-item">
                            <div class="course-info">
                                <h6>Database Design</h6>
                                <p class="text-muted">Instructor: Jane Doe</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar" style="width: 45%"></div>
                                </div>
                                <small class="text-muted">45% Complete</small>
                            </div>
                            <div class="course-actions">
                                <a href="#" class="btn btn-sm btn-primary">Continue</a>
                            </div>
                        </div>
                        <div class="course-item">
                            <div class="course-info">
                                <h6>PHP Programming</h6>
                                <p class="text-muted">Instructor: Mike Johnson</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar" style="width: 90%"></div>
                                </div>
                                <small class="text-muted">90% Complete</small>
                            </div>
                            <div class="course-actions">
                                <a href="#" class="btn btn-sm btn-primary">Continue</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Assignments</h5>
                </div>
                <div class="card-body">
                    <div class="assignment-list">
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h6>HTML/CSS Project</h6>
                                <small class="text-muted">Due: Tomorrow</small>
                            </div>
                            <span class="badge badge-warning">Pending</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h6>Database Quiz</h6>
                                <small class="text-muted">Due: 3 days</small>
                            </div>
                            <span class="badge badge-info">In Progress</span>
                        </div>
                        <div class="assignment-item">
                            <div class="assignment-info">
                                <h6>PHP Assignment</h6>
                                <small class="text-muted">Due: 1 week</small>
                            </div>
                            <span class="badge badge-success">Completed</span>
                        </div>
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
    color: #17a2b8;
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
    margin: 5px 0;
    font-size: 0.9rem;
}

.course-actions .btn {
    margin-left: 5px;
}

.assignment-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.assignment-item:last-child {
    border-bottom: none;
}

.assignment-info h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #333;
}

.assignment-info small {
    font-size: 0.8rem;
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

.progress {
    height: 8px;
    background-color: #e9ecef;
    border-radius: 4px;
}

.progress-bar {
    background-color: #007bff;
    border-radius: 4px;
}
</style>
<?= $this->endSection() ?>
