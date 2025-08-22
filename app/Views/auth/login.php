<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Learning Management System - Sign In</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      min-height: 100vh;
      overflow-x: hidden;
    }
    
    .login-container {
      display: flex;
      min-height: 100vh;
      width: 100%;
    }
    
    .left-section {
      flex: 1;
      background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1500&q=80') center center/cover no-repeat;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      padding: 0 60px;
      min-height: 100vh;
    }
    
    .left-content {
      color: white;
      max-width: 500px;
      position: relative;
      z-index: 2;
    }
    
    .left-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 23, 46, 0.6);
    }
    
    .welcome-text {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      line-height: 1.2;
    }
    
    .welcome-description {
      font-size: 1.2rem;
      line-height: 1.6;
      opacity: 0.95;
    }
    
    .right-section {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      background-color: #f8f9fa;
      min-height: 100vh;
    }
    
    .login-card {
      background: white;
      padding: 50px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 450px;
    }
    
    .login-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 10px;
      text-align: center;
    }
    
    .login-subtitle {
      color: #718096;
      text-align: center;
      margin-bottom: 40px;
      font-size: 1.1rem;
    }
    
    .alert {
      border-radius: 12px;
      border: none;
      margin-bottom: 25px;
    }
    
    .alert-danger {
      background-color: #fed7d7;
      color: #c53030;
      border-left: 4px solid #e53e3e;
    }
    
    .alert-success {
      background-color: #c6f6d5;
      color: #2f855a;
      border-left: 4px solid #38a169;
    }
    
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }
    
    .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 15px 20px 15px 50px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background-color: #f7fafc;
      width: 100%;
    }
    
    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      background-color: white;
      outline: none;
    }
    
    .form-control.is-invalid {
      border-color: #e53e3e;
      background-color: #fed7d7;
    }
    
    .form-control.is-valid {
      border-color: #38a169;
      background-color: #c6f6d5;
    }
    
    .form-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      font-size: 1.1rem;
      pointer-events: none;
    }
    
    .password-toggle {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      cursor: pointer;
      font-size: 1.1rem;
      transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
      color: #3b82f6;
    }
    
    .form-check {
      margin-bottom: 25px;
    }
    
    .form-check-input:checked {
      background-color: #3b82f6;
      border-color: #3b82f6;
    }
    
    .forgot-password {
      color: #3b82f6;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }
    
    .forgot-password:hover {
      color: #2563eb;
    }
    
    .btn-signin {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border: none;
      border-radius: 12px;
      padding: 15px 30px;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s ease;
      margin-bottom: 30px;
      cursor: pointer;
    }
    
    .btn-signin:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }
    
    .btn-signin:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }
    
    .create-account {
      text-align: center;
      color: #718096;
      margin-bottom: 30px;
    }
    
    .create-account a {
      color: #3b82f6;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    
    .create-account a:hover {
      color: #2563eb;
    }
    
    .copyright {
      text-align: center;
      color: #a0aec0;
      font-size: 0.9rem;
      margin-top: 20px;
    }
    
    .loading {
      display: none;
    }
    
    .loading.show {
      display: inline-block;
    }
    
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }
      
      .left-section {
        min-height: 300px;
        padding: 40px 20px;
        justify-content: center;
        text-align: center;
      }
      
      .welcome-text {
        font-size: 2.5rem;
      }
      
      .right-section {
        padding: 20px;
        min-height: auto;
      }
      
      .login-card {
        padding: 30px 20px;
      }
      
      .login-title {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Left Section - Background Image with Welcome Text -->
    <div class="left-section">
      <div class="left-overlay"></div>
      <div class="left-content">
        <h1 class="welcome-text">Welcome Back!</h1>
        <p class="welcome-description">Access your learning journey and continue building your skills with our comprehensive educational platform.</p>
      </div>
    </div>
    
    <!-- Right Section - Login Form -->
    <div class="right-section">
      <div class="login-card">
        <h2 class="login-title">Sign In</h2>
        <p class="login-subtitle">Enter your credentials to access your account</p>
        
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
          </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
          </div>
        <?php endif; ?>
        
        <form id="loginForm" action="<?= base_url('auth/login') ?>" method="post">
          <?= csrf_field() ?>
          
          <div class="form-group">
            <i class="fas fa-user form-icon"></i>
            <input type="text" 
                   class="form-control <?= (session()->getFlashdata('errors')['username'] ?? false) ? 'is-invalid' : '' ?>" 
                   name="username" 
                   placeholder="Enter your email or username" 
                   value="<?= old('username') ?>"
                   required>
            <?php if (session()->getFlashdata('errors')['username'] ?? false): ?>
              <div class="invalid-feedback"><?= session()->getFlashdata('errors')['username'] ?></div>
            <?php endif; ?>
          </div>
          
          <div class="form-group">
            <i class="fas fa-lock form-icon"></i>
            <input type="password" 
                   class="form-control <?= (session()->getFlashdata('errors')['password'] ?? false) ? 'is-invalid' : '' ?>" 
                   name="password" 
                   id="password" 
                   placeholder="Enter your password" 
                   required>
            <i class="fas fa-eye password-toggle" id="passwordToggle"></i>
            <?php if (session()->getFlashdata('errors')['password'] ?? false): ?>
              <div class="invalid-feedback"><?= session()->getFlashdata('errors')['password'] ?></div>
            <?php endif; ?>
          </div>
          
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="1">
              <label class="form-check-label" for="rememberMe">
                Remember me
              </label>
            </div>
            <a href="<?= base_url('auth/forgot-password') ?>" class="forgot-password">Forgot password?</a>
          </div>
          
          <button type="submit" class="btn btn-signin" id="signinBtn">
            <span class="loading" id="loading">
              <i class="fas fa-spinner fa-spin me-2"></i>
            </span>
            <span id="btnText">Sign In</span>
          </button>
          
          <div class="create-account">
            Don't have an account? <a href="<?= base_url('auth/register') ?>">Create one here</a>
          </div>
        </form>
        
        <div class="copyright">
          © 2025 Learning Management System. All rights reserved.
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom JavaScript -->
  <script>
    // Password toggle functionality
    document.getElementById('passwordToggle').addEventListener('click', function() {
      const passwordField = document.getElementById('password');
      const icon = this;
      
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
    
    // Form submission handling
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const submitBtn = document.getElementById('signinBtn');
      const loading = document.getElementById('loading');
      const btnText = document.getElementById('btnText');
      
      // Show loading state
      submitBtn.disabled = true;
      loading.classList.add('show');
      btnText.textContent = 'Signing In...';
    });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(function() {
          alert.remove();
        }, 500);
      });
    }, 5000);
  </script>
</body>
</html> 