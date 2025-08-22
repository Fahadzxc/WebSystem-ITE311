<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Learning Management System</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
      font-family: 'Inter', sans-serif;
      color: white;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }
    .header {
      background: #374151;
      padding: 20px 0;
      border-bottom: none;
    }
    .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
    }
    .logo-icon {
      width: 40px;
      height: 40px;
      background: #60a5fa;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      font-size: 1.2rem;
    }
    .nav-link {
      color: white !important;
      margin: 0 20px;
      font-weight: 500;
      text-decoration: none;
      transition: color 0.3s;
    }
    .nav-link:hover, .nav-link.active {
      color: #60a5fa !important;
    }
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 60px 0 40px 0;
    }
    .hero-section {
      text-align: center;
      margin-bottom: 60px;
    }
    .hero-icon {
      width: 80px;
      height: 80px;
      background: #60a5fa;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 30px;
      font-size: 2rem;
      color: white;
    }
    .hero-title {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.1;
    }
    .hero-title .highlight {
      color: #60a5fa;
    }
    .hero-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 40px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.6;
    }
    .signin-btn {
      background: #60a5fa;
      border: none;
      border-radius: 8px;
      padding: 15px 30px;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: all 0.3s;
    }
    .signin-btn:hover {
      background: #3b82f6;
      color: white;
      transform: translateY(-2px);
    }
    .feature-cards {
      padding: 0;
    }
    .feature-card {
      background: rgba(30, 58, 138, 0.9);
      border-radius: 12px;
      padding: 25px;
      text-align: center;
      height: 100%;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      border: 1px solid rgba(255,255,255,0.1);
    }
    .feature-icon {
      font-size: 2.5rem;
      color: white;
      margin-bottom: 15px;
    }
    .feature-title {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .feature-text {
      opacity: 0.9;
      line-height: 1.5;
      font-size: 0.95rem;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <a href="/" class="logo">
            <div class="logo-icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            EduFlow
          </a>
        </div>
        <div class="col-md-6 text-end">
          <a href="/" class="nav-link active">Home</a>
          <a href="/about" class="nav-link">About</a>
          <a href="/contact" class="nav-link">Contact</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="container">
        <div class="hero-icon">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <h1 class="hero-title">Welcome to <span class="highlight">LMS</span></h1>
        <p class="hero-subtitle">
          Your Learning Management System is ready! Explore our comprehensive course catalog, learn about our mission, or get in touch with our support team.
        </p>
        <a href="<?= base_url('auth') ?>" class="signin-btn">
          <i class="fas fa-arrow-right me-2"></i>Sign In
        </a>
      </div>
    </section>

    <!-- Feature Cards -->
    <section class="feature-cards">
      <div class="container">
        <div class="row g-4">
          <div class="col-md-4">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-book-open"></i>
              </div>
              <h3 class="feature-title">Interactive Courses</h3>
              <p class="feature-text">Access engaging multimedia content and interactive learning materials.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-trophy"></i>
              </div>
              <h3 class="feature-title">Track Progress</h3>
              <p class="feature-text">Monitor your learning journey with detailed progress analytics.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="feature-card">
              <div class="feature-icon">
                <i class="fas fa-users"></i>
              </div>
              <h3 class="feature-title">Connect & Learn</h3>
              <p class="feature-text">Join study groups and collaborate with fellow learners worldwide.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
