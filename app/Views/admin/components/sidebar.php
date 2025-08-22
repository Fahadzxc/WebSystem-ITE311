<?php
$currentPage = $currentPage ?? 'dashboard';
?>
<div class="sidebar">
    <div class="sidebar-header mb-4">
        <div class="d-flex align-items-center">
            <div class="sidebar-logo me-3">
                <i class="fas fa-graduation-cap text-primary" style="font-size: 2rem;"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold text-dark">EduPlatform</h5>
                <small class="text-muted">Admin Panel</small>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard') ?>" 
                   class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/users') ?>" 
                   class="nav-link <?= $currentPage === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/courses') ?>" 
                   class="nav-link <?= $currentPage === 'courses' ? 'active' : '' ?>">
                    <i class="fas fa-book"></i>
                    <span>Course Management</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/instructors') ?>" 
                   class="nav-link <?= $currentPage === 'instructors' ? 'active' : '' ?>">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Instructors</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/students') ?>" 
                   class="nav-link <?= $currentPage === 'students' ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/enrollments') ?>" 
                   class="nav-link <?= $currentPage === 'enrollments' ? 'active' : '' ?>">
                    <i class="fas fa-user-plus"></i>
                    <span>Enrollments</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/reports') ?>" 
                   class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/settings') ?>" 
                   class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-footer mt-auto">
        <div class="text-center">
            <small class="text-muted">© 2024 EduPlatform</small>
        </div>
    </div>
</div> 