<div class="top-navbar">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Left side - Search and breadcrumb -->
        <div class="d-flex align-items-center">
            <div class="me-4">
                <h4 class="mb-0 fw-bold text-dark"><?= $title ?? 'Admin Dashboard' ?></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none">Home</a></li>
                        <?php if (isset($breadcrumb)): ?>
                            <?php foreach ($breadcrumb as $item): ?>
                                <li class="breadcrumb-item <?= $item['active'] ? 'active' : '' ?>">
                                    <?php if ($item['active']): ?>
                                        <?= $item['text'] ?>
                                    <?php else: ?>
                                        <a href="<?= $item['url'] ?>" class="text-decoration-none"><?= $item['text'] ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Right side - Search, notifications, user profile -->
        <div class="d-flex align-items-center">
            <!-- Search Bar -->
            <div class="me-3">
                <div class="input-group">
                    <input type="text" class="form-control search-bar" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Notifications -->
            <div class="me-3 position-relative">
                <button class="btn btn-link text-dark text-decoration-none position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bell fa-lg"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-plus text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0 small">New student enrollment</p>
                                <small class="text-muted">2 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0 small">Course submission pending</p>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="#">View all notifications</a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <button class="btn btn-link text-dark text-decoration-none d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <div class="avatar me-2">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div class="fw-bold"><?= $user['name'] ?? 'Admin User' ?></div>
                        <small class="text-muted"><?= $user['role'] ?? 'Administrator' ?></small>
                    </div>
                    <i class="fas fa-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>">
                        <i class="fas fa-user me-2"></i>Profile
                    </a></li>
                    <li><a class="dropdown-item" href="<?= base_url('admin/settings') ?>">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</div> 