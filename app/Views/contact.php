<?= $this->extend('template') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 fw-bold mb-3" style="color: var(--bs-text-dark);">
                Contact Us
            </h1>
            <p class="lead text-muted">
                Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-envelope text-primary-custom me-2"></i>Send us a Message
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form>
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-user me-2"></i>Name
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Enter your name"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter your email"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label fw-semibold">
                                <i class="fas fa-comment me-2"></i>Message
                            </label>
                            <textarea class="form-control" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      placeholder="Enter your message"
                                      required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i>Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--bs-text-dark);">
                        <i class="fas fa-address-card text-primary-custom me-2"></i>Contact Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="color: var(--bs-text-dark);">Email</h6>
                                <p class="text-muted mb-0">
                                    <a href="mailto:info@lmssystem.com" class="text-decoration-none">info@lmssystem.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="color: var(--bs-text-dark);">Phone</h6>
                                <p class="text-muted mb-0">
                                    <a href="tel:+631234567890" class="text-decoration-none">+63 123 456 7890</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="color: var(--bs-text-dark);">Address</h6>
                                <p class="text-muted mb-0">
                                    Alabel, Sarangani Province<br>
                                    Philippines
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-top">
                        <a href="<?= base_url('') ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
