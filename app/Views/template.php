<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LMS System' ?></title>
    
    <!-- Professional & Formal CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Georgia, serif;
            line-height: 1.6;
            color: #2c3e50;
            background-color: #ffffff;
        }
        
        .navbar {
            background-color: #2c3e50;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            color: #ffffff;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 1px;
        }
        
        .navbar-nav {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-link {
            color: #ecf0f1;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            transition: all 0.3s;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .nav-link:hover {
            color: #ffffff;
            background-color: #34495e;
        }
        
        .nav-link.active {
            color: #ffffff;
            background-color: #3498db;
        }
        
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .hero {
            text-align: center;
            padding: 3rem 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .hero p {
            font-size: 1.1rem;
            color: #5a6c7d;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-style: italic;
        }
        
        .btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background-color: #2c3e50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            border: 2px solid #2c3e50;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0.5rem;
        }
        
        .btn:hover {
            background-color: #34495e;
            border-color: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-outline {
            background-color: transparent;
            color: #2c3e50;
            border: 2px solid #2c3e50;
        }
        
        .btn-outline:hover {
            background-color: #2c3e50;
            color: #ffffff;
        }
        
        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 2.5rem;
            margin: 2rem 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6;
        }
        
        .card h1 {
            font-size: 2.2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 0.5rem;
        }
        
        .card h2 {
            font-size: 1.4rem;
            font-weight: bold;
            color: #34495e;
            margin: 2rem 0 1rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .card p {
            color: #5a6c7d;
            margin-bottom: 1.5rem;
            line-height: 1.8;
            text-align: justify;
        }
        
        .card ul {
            color: #5a6c7d;
            margin-left: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .card li {
            margin-bottom: 0.75rem;
            line-height: 1.7;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-3 {
            margin-top: 1.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1.5rem;
        }
        
        .footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
            border-top: 4px solid #3498db;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #dee2e6;
            border-radius: 4px;
            font-size: 1rem;
            font-family: 'Times New Roman', Georgia, serif;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .auth-form {
            max-width: 450px;
            margin: 1rem auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6;
        }
        
        .auth-form h2 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 0.5rem;
        }
        
        .auth-form .form-group {
            margin-bottom: 1rem;
        }
        
        .auth-form .btn {
            width: 100%;
            margin: 0;
        }
        
        .auth-form .text-center {
            margin-top: 1.5rem;
        }
        
        .auth-form a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-form a:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .navbar-nav {
                gap: 1rem;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .main-content {
                margin: 1rem auto;
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                LMS System
            </a>
            
            <ul class="navbar-nav">
                <li><a class="nav-link <?= ($page ?? '') === 'home' ? 'active' : '' ?>" href="<?= base_url('home') ?>">Home</a></li>
                <li><a class="nav-link <?= ($page ?? '') === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>">About</a></li>
                <li><a class="nav-link <?= ($page ?? '') === 'contact' ? 'active' : '' ?>" href="<?= base_url('contact') ?>">Contact</a></li>
                <?php if (session()->get('isLoggedIn')): ?>
                    <li><a class="nav-link" href="<?= base_url('logout') ?>" style="color: #e74c3c;">Logout</a></li>
                <?php else: ?>
                    <li><a class="nav-link" href="<?= base_url('login') ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> LMS System. All rights reserved.</p>
    </footer>
</body>
</html>
