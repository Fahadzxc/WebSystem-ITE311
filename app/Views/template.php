<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LMS System' ?></title>
    
    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Minimal, clean CSS per spec -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.7;
            color: #6B7280;
            background-color: #F9FAFB;
        }
        
        .navbar {
            background-color: #1E293B;
            padding: 1rem 0;
            box-shadow: 0 1px 2px rgba(2,6,23,0.08);
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
            color: #FFFFFF;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .navbar-nav {
            display: flex;
            list-style: none;
            gap: 2rem;
            margin: 0;
            padding: 0;
        }
        
        .navbar-nav li {
            list-style: none;
        }
        
        .nav-link {
            color: #E5E7EB;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: color 0.2s ease, background-color 0.2s ease;
            font-weight: 500;
            letter-spacing: 0.2px;
            font-size: 0.95rem;
        }
        
        .nav-link:hover {
            color: #14B8A6;
            background-color: transparent;
        }
        
        .nav-link.active {
            color: #FFFFFF;
            background-color: rgba(37,99,235,0.18);
        }
        
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .hero {
            text-align: center;
            padding: 4rem 1rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F1F5F9 100%);
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        
        .hero h1 {
            font-size: 2.75rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        
        .hero p {
            font-size: 1.1rem;
            color: #6B7280;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.25rem;
            background-color: #2563EB;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid #2563EB;
            cursor: pointer;
            transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.05s ease;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.2px;
            margin: 0.25rem;
        }
        
        .btn:hover {
            background-color: #1D4ED8;
            border-color: #1D4ED8;
            transform: translateY(-1px);
        }
        
        .btn-outline {
            background-color: transparent;
            color: #1E293B;
            border: 1px solid #1E293B;
        }
        
        .btn-outline:hover {
            background-color: #14B8A6;
            color: #FFFFFF;
            border-color: #14B8A6;
        }
        
        .card {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 1px 2px rgba(15,23,42,0.06);
            border: 1px solid #E5E7EB;
        }

        /* Responsive layout helpers */
        .grid-3-220 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .grid-2-260 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 2rem;
            align-items: start;
        }
        .grid-2-280 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            align-items: start;
        }
        .stack-gap { display: grid; gap: 1rem; }

        /* Simple content tile */
        .tile {
            padding: 1rem;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            background: #FFFFFF;
        }
        
        .card h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 2px solid rgba(37,99,235,0.2);
            padding-bottom: 0.5rem;
        }
        
        .card h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin: 2rem 0 1rem 0;
            letter-spacing: 0.2px;
        }
        
        .card p {
            color: #6B7280;
            margin-bottom: 1.5rem;
            line-height: 1.8;
            text-align: left;
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
            background-color: #FFFFFF;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            text-align: center;
            padding: 1.25rem 0;
            margin-top: 3rem;
            font-size: 0.875rem;
        }
        .footer a { color: #6B7280; text-decoration: none; margin: 0 0.75rem; }
        .footer a:hover { color: #14B8A6; }
        
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
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
    <?= $this->include('templates/header') ?>

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
