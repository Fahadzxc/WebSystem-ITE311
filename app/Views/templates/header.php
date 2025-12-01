<!-- Bootstrap 5 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary-custom sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url(''); ?>">
            <?php if (session()->get('isLoggedIn')): ?>
                Welcome, <?= session()->get('name') ?>
            <?php else: ?>
                LMS-ALALAWI
            <?php endif; ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (session()->get('isLoggedIn')): ?>
                    <?php $role = strtolower(session('role') ?? ''); ?>
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">Admin Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/users'); ?>">Manage Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/academic-settings'); ?>">Settings</a>
                        </li>
                    <?php elseif ($role === 'teacher'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('teacher/dashboard'); ?>">Teacher Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('assignment'); ?>">Assignments</a>
                        </li>
                    <?php elseif ($role === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('student/dashboard'); ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('student/announcement'); ?>">Announcements</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('student/enrollments'); ?>">My Enrollments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('student/materials'); ?>">All Materials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('assignment'); ?>">Assignments</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Notifications Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i> Notifications
                            <?php if (isset($unread_count) && $unread_count > 0): ?>
                                <span id="notificationBadge" class="badge bg-danger rounded-pill notification-badge"><?= $unread_count ?></span>
                            <?php else: ?>
                                <span id="notificationBadge" class="badge bg-danger rounded-pill notification-badge" style="display: none;">0</span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" aria-labelledby="notificationDropdown" style="min-width: 400px; max-width: 450px; border-radius: 12px !important; overflow: hidden; margin-top: 0.5rem !important;">
                            <div class="bg-primary-custom text-white px-4 py-3 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                </h6>
                                <span id="notificationCount" class="badge bg-white text-primary-custom fw-bold px-2 py-1">0</span>
                            </div>
                            <div id="notificationList" class="p-0" style="max-height: 500px; overflow-y: auto; overflow-x: hidden;">
                                <div class="text-center py-5">
                                    <div class="spinner-border spinner-border-sm text-primary-custom" role="status" style="width: 1.5rem; height: 1.5rem;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted small mb-0 mt-3">Loading notifications...</p>
                                </div>
                            </div>
                            <div class="border-top bg-light">
                                <a href="<?= base_url('notifications') ?>" class="dropdown-item text-center py-3 fw-semibold text-primary-custom text-decoration-none" style="transition: all 0.2s ease;">
                                    <i class="fas fa-external-link-alt me-2"></i>View All Notifications
                                </a>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout'); ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url(''); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('about'); ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('contact'); ?>">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login'); ?>">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
