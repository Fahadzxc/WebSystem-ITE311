<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - LMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .forgot-card {
      background: white;
      padding: 50px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      max-width: 450px;
      width: 100%;
      text-align: center;
    }
    .forgot-icon {
      font-size: 4rem;
      color: #3b82f6;
      margin-bottom: 20px;
    }
    .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 15px 20px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .btn-primary {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border: none;
      border-radius: 12px;
      padding: 15px 30px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
      width: 100%;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }
    .back-link {
      color: #3b82f6;
      text-decoration: none;
      font-weight: 500;
    }
    .back-link:hover {
      color: #2563eb;
    }
  </style>
</head>
<body>
  <div class="forgot-card">
    <div class="forgot-icon">
      <i class="fas fa-lock-open"></i>
    </div>
    <h2 class="mb-3">Forgot Password?</h2>
    <p class="text-muted mb-4">
      Enter your email address and we'll send you a link to reset your password.
    </p>
    
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
    
    <form action="<?= base_url('auth/reset-password') ?>" method="post">
      <?= csrf_field() ?>
      
      <div class="mb-4">
        <input type="email" 
               class="form-control" 
               name="email" 
               placeholder="Enter your email address" 
               required>
      </div>
      
      <button type="submit" class="btn btn-primary mb-4">
        <i class="fas fa-paper-plane me-2"></i>
        Send Reset Link
      </button>
    </form>
    
    <div class="text-center">
      <a href="<?= base_url('auth') ?>" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Login
      </a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 