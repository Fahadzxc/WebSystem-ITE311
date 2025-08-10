<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Learning Management System</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .welcome-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    .welcome-card {
      background: white;
      padding: 60px 40px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      max-width: 500px;
    }
    .welcome-icon {
      font-size: 4rem;
      color: #3b82f6;
      margin-bottom: 20px;
    }
    .welcome-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 20px;
    }
    .welcome-text {
      color: #718096;
      font-size: 1.1rem;
      margin-bottom: 30px;
      line-height: 1.6;
    }
    .btn-primary {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border: none;
      border-radius: 12px;
      padding: 15px 30px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }
  </style>
</head>
<body>
  <div class="welcome-container">
    <div class="welcome-card">
      <div class="welcome-icon">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <h1 class="welcome-title">Welcome to LMS</h1>
      <p class="welcome-text">
        Your Learning Management System is ready! Please sign in to access your dashboard and start your learning journey.
      </p>
      <a href="<?= base_url('auth') ?>" class="btn btn-primary">
        <i class="fas fa-sign-in-alt me-2"></i>
        Sign In
      </a>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
