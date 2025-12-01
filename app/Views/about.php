<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <!-- About Header -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 fw-bold mb-4" style="color: var(--bs-text-dark);">
                About Our LMS
            </h1>
            <p class="lead text-muted">
                We build simple, modern tools that help instructors teach effectively and help students learn with focus.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Left Column - Mission & Values -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h2 class="fw-bold mb-4" style="color: var(--bs-text-dark);">
                        <i class="fas fa-bullseye text-primary-custom me-2"></i>Our Mission
                    </h2>
                    <p class="text-muted mb-4">
                        Enable high-quality learning through a clean, accessible platform that reduces friction for learners and educators.
                    </p>
                    
                    <h2 class="fw-bold mb-4 mt-5" style="color: var(--bs-text-dark);">
                        <i class="fas fa-heart text-primary-custom me-2"></i>Our Values
                    </h2>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-primary-custom me-3 mt-1"></i>
                                <div>
                                    <strong style="color: var(--bs-text-dark);">Clarity</strong>
                                    <p class="text-muted mb-0">Prioritize readability and ease of use</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-primary-custom me-3 mt-1"></i>
                                <div>
                                    <strong style="color: var(--bs-text-dark);">Reliability</strong>
                                    <p class="text-muted mb-0">Stable performance and data security</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle text-primary-custom me-3 mt-1"></i>
                                <div>
                                    <strong style="color: var(--bs-text-dark);">Progress</strong>
                                    <p class="text-muted mb-0">Continuous improvement with thoughtful features</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('contact') ?>" class="btn btn-primary">
                            <i class="fas fa-envelope me-2"></i>Get in Touch
                        </a>
                        <a href="<?= base_url('') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Features -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-book-reader" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">Learning</h3>
                            <p class="text-muted mb-0">Track progress and manage coursework with ease.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-chalkboard-teacher" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">Teaching</h3>
                            <p class="text-muted mb-0">Create courses, assignments, and communicate with students.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-users" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-2" style="color: var(--bs-text-dark);">Community</h3>
                            <p class="text-muted mb-0">Bring learners and instructors together around shared goals.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
