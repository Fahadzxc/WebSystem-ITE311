    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url(''); ?>">
                <?php if (session()->get('isLoggedIn')): ?>
                    Welcome, <?= session()->get('name') ?>
                <?php else: ?>
                    LMS-ALALAWI
                <?php endif; ?>
            </a>
            <ul class="navbar-nav">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <?php $role = strtolower(session('role') ?? ''); ?>
                        <?php if ($role === 'admin'): ?>
                            <li><a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                            <li><a class="nav-link" href="<?= base_url('admin/users'); ?>">Manage Users</a></li>
                            <li><a class="nav-link" href="<?= base_url('admin/settings'); ?>">Settings</a></li>
                        <?php elseif ($role === 'teacher'): ?>
                            <li><a class="nav-link" href="<?= base_url('teacher/dashboard'); ?>">Teacher Dashboard</a></li>
                            <li><a class="nav-link" href="<?= base_url('teacher/courses'); ?>">My Courses</a></li>
                            <li><a class="nav-link" href="<?= base_url('teacher/grades'); ?>">Grades</a></li>
                        <?php elseif ($role === 'student'): ?>
                            <li><a class="nav-link" href="<?= base_url('student/dashboard'); ?>">Dashboard</a></li>
                            <li><a class="nav-link" href="<?= base_url('student/announcement'); ?>">Announcements</a></li>
                            <li><a class="nav-link" href="<?= base_url('student/enrollments'); ?>">My Enrollments</a></li>
                            <li><a class="nav-link" href="<?= base_url('student/materials'); ?>">All Materials</a></li>
                            <li><a class="nav-link" href="<?= base_url('student/assignments'); ?>">Assignments</a></li>
                        <?php else: ?>
                            <li><a class="nav-link" href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                        <?php endif; ?>
                        
                        <!-- Notifications Link -->
                        <li>
                            <a class="nav-link" href="<?= base_url('notifications'); ?>" style="position: relative; display: inline-block;">
                                <i class="fas fa-bell <?= (isset($unread_count) && $unread_count > 0) ? 'text-danger' : '' ?>"></i> Notifications
                                <?php if (isset($unread_count) && $unread_count > 0): ?>
                                    <span style="position: absolute; top: -8px; right: -8px; background-color: #dc3545; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        <?= $unread_count ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        
                        <li>
                            <a class="nav-link" href="<?= base_url('logout'); ?>">Logout</a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a class="nav-link" href="<?= base_url(''); ?>">Home</a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= base_url('about'); ?>">About</a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= base_url('contact'); ?>">Contact</a>
                        </li>
                        <li>
                            <a class="nav-link" href="<?= base_url('login'); ?>">Login</a>
                        </li>
                    <?php endif; ?>
            </ul>
        </div>
    </nav>


