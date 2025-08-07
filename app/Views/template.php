<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CODEIGNITER</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar {
      border-radius: 20px 20px 0 0;
    }
    .hero-section {
      background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1500&q=80') center center/cover no-repeat;
      min-height: 450px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-align: center;
      position: relative;
      border-radius: 0 0 20px 20px;
    }
    .hero-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 23, 46, 0.7);
      border-radius: 0 0 20px 20px;
    }
    .hero-content {
      position: relative;
      z-index: 2;
      width: 100%;
    }
    .hero-title {
      font-size: 3rem;
      font-weight: 700;
    }
    .hero-highlight {
      color: #3b82f6;
    }
    .features-section {
      margin-top: -60px;
      z-index: 3;
      position: relative;
    }
    .feature-icon {
      font-size: 2.5rem;
      color: #3b82f6;
      margin-bottom: 15px;
    }
    .footer {
      background: #222;
      color: #fff;
      padding: 30px 0 10px 0;
      border-radius: 0 0 20px 20px;
      margin-top: 40px;
    }
    .footer a {
      color: #3b82f6;
      margin: 0 10px;
      font-size: 1.3rem;
      transition: color 0.2s;
    }
    .footer a:hover {
      color: #fff;
    }
    @media (max-width: 767px) {
      .hero-title { font-size: 2rem; }
    }
  </style>
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#" style="font-family: 'Pacifico', cursive;">logo</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section position-relative">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
      <h1 class="hero-title mb-3">Transform Your Business with <span class="hero-highlight">Innovative Solutions</span></h1>
      <p class="lead mb-4">We deliver cutting-edge digital services that drive growth, enhance efficiency, and create exceptional user experiences for businesses worldwide.</p>
      <a href="#" class="btn btn-primary btn-lg">Get Started Today</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features-section container py-5">
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="feature-icon mb-3"><i class="fas fa-bolt"></i></div>
        <h5>Fast Performance</h5>
        <p>Optimized solutions for speed and reliability, ensuring your business runs smoothly.</p>
      </div>
      <div class="col-md-4 mb-4">
        <div class="feature-icon mb-3"><i class="fas fa-shield-alt"></i></div>
        <h5>Secure & Reliable</h5>
        <p>Advanced security features to protect your data and maintain business continuity.</p>
      </div>
      <div class="col-md-4 mb-4">
        <div class="feature-icon mb-3"><i class="fas fa-users"></i></div>
        <h5>User-Centric Design</h5>
        <p>Intuitive interfaces and experiences designed for your customers and teams.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer text-center">
    <div class="mb-3">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-linkedin-in"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
    <div>&copy; 2025 Your Company. All rights reserved.</div>
  </footer>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
