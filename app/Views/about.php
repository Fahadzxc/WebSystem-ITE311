<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - ITE311 Learning Management System</title>
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
        .about-section {
            margin-bottom: 2rem;
        }
        .about-section h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .team-member {
            text-align: center;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .team-member h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .team-member .role {
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 1rem;
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
            <h1>About Our Learning Management System</h1>

            <div class="about-section">
                <h2>Our Mission</h2>
                <p>We are committed to providing a comprehensive and user-friendly learning management system that empowers students and educators to achieve their educational goals. Our platform combines cutting-edge technology with intuitive design to create an exceptional learning experience.</p>
            </div>

            <div class="about-section">
                <h2>What We Offer</h2>
                <ul>
                    <li><strong>Course Management:</strong> Organize and manage courses with ease</li>
                    <li><strong>Lesson Tracking:</strong> Monitor progress through individual lessons</li>
                    <li><strong>Assignment Submission:</strong> Submit and track assignments seamlessly</li>
                    <li><strong>User Management:</strong> Comprehensive user profiles and enrollment tracking</li>
                    <li><strong>Progress Analytics:</strong> Detailed insights into learning progress</li>
                </ul>
            </div>

            <div class="about-section">
                <h2>Our Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <h3>Development Team</h3>
                        <div class="role">Software Engineers</div>
                        <p>Dedicated to creating robust and scalable solutions for educational technology.</p>
                    </div>
                    <div class="team-member">
                        <h3>Design Team</h3>
                        <div class="role">UI/UX Designers</div>
                        <p>Focused on creating intuitive and engaging user experiences.</p>
                    </div>
                    <div class="team-member">
                        <h3>Support Team</h3>
                        <div class="role">Customer Success</div>
                        <p>Committed to providing excellent support and assistance to all users.</p>
                    </div>
                </div>
            </div>

            <div class="about-section">
                <h2>Technology Stack</h2>
                <p>Our platform is built using modern web technologies including CodeIgniter 4, PHP, MySQL, HTML5, CSS3, and JavaScript. We prioritize security, performance, and scalability to ensure the best possible experience for our users.</p>
            </div>
        </div>
    </div>
</body>
</html>
