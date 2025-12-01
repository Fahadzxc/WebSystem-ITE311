<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg rounded-4 border-0">
                <div class="card-body p-5">
                    <!-- Register Title -->
                    <h1 class="card-title text-center mb-4 fw-bold" style="color: var(--bs-text-dark); font-size: 2.5rem;">
                        Register
                    </h1>
                    <div class="border-bottom border-primary border-2 mb-4"></div>
                    
                    <!-- Flash Messages -->
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($validation)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $validation->listErrors() ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Register Form -->
                    <form action="" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-uppercase small" style="color: var(--bs-text-dark); letter-spacing: 0.5px;">
                                Name
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg" 
                                id="name" 
                                name="name" 
                                value="<?= set_value('name') ?>" 
                                placeholder="Enter your full name"
                                required
                                autofocus
                            >
                        </div>
                        
                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-uppercase small" style="color: var(--bs-text-dark); letter-spacing: 0.5px;">
                                Email
                            </label>
                            <input 
                                type="email" 
                                class="form-control form-control-lg" 
                                id="email" 
                                name="email" 
                                value="<?= set_value('email') ?>" 
                                placeholder="Enter your email"
                                required
                            >
                        </div>
                        
                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-uppercase small" style="color: var(--bs-text-dark); letter-spacing: 0.5px;">
                                Password
                            </label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password"
                                required
                            >
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password_confirm" class="form-label fw-semibold text-uppercase small" style="color: var(--bs-text-dark); letter-spacing: 0.5px;">
                                Confirm Password
                            </label>
                            <input 
                                type="password" 
                                class="form-control form-control-lg" 
                                id="password_confirm" 
                                name="password_confirm" 
                                placeholder="Confirm your password"
                                required
                            >
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold py-3">
                                Register
                            </button>
                        </div>
                    </form>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="mb-0 text-muted">
                            Already have an account? 
                            <a href="<?= site_url('login') ?>" class="text-decoration-none fw-semibold" style="color: var(--bs-primary);">
                                Login
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
