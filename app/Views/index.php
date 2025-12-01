<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold mb-4" style="color: var(--bs-text-dark);">
                    Learning Management System
                </h1>
                <p class="lead text-muted mb-5">
                    A comprehensive educational platform designed to facilitate effective learning and academic excellence.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= base_url('about') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                    <a href="<?= base_url('contact') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-6 fw-bold mb-3" style="color: var(--bs-text-dark);">Key Features</h2>
                <p class="lead text-muted">Discover what makes our platform special</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary-custom text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-mobile-alt" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3" style="color: var(--bs-text-dark);">Responsive</h5>
                        <p class="card-text text-muted">Crafted with a mobile-first approach to work beautifully on all screens.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-universal-access" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3" style="color: var(--bs-text-dark);">Accessible</h5>
                        <p class="card-text text-muted">Readable typography, clear contrast, and simple interactions.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-code" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3" style="color: var(--bs-text-dark);">Maintainable</h5>
                        <p class="card-text text-muted">Minimal, clean code structure that's easy to extend.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
