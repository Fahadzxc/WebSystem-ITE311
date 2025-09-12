<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - ITE311 Learning Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 20px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        nav ul li a:hover {
            background-color: #34495e;
        }
        .content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .feature-card {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .feature-card h3 {
            color: #2c3e50;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li><a href="<?= base_url('about') ?>">About</a></li>
                    <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <div class="welcome-section">
                <h1>Welcome to ITE311 Learning Management System</h1>
                <p>Your comprehensive platform for online learning and course management.</p>
            </div>

            <div class="features">
                <div class="feature-card">
                    <h3>📚 Course Management</h3>
                    <p>Access and manage your courses with ease. View lessons, track progress, and submit assignments all in one place.</p>
                </div>
                <div class="feature-card">
                    <h3>👥 User-Friendly Interface</h3>
                    <p>Intuitive design that makes learning accessible to everyone. Navigate through your educational journey effortlessly.</p>
                </div>
                <div class="feature-card">
                    <h3>📊 Progress Tracking</h3>
                    <p>Monitor your learning progress with detailed analytics and personalized insights to help you succeed.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
