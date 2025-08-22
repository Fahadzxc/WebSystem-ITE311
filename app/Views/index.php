<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFlow - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0A1A3F 0%, #001F5C 100%);
            color: white;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Navigation */
        .navbar {
            background: rgba(10, 26, 63, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.5rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 2.2rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #00C6FF;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            border-radius: 1px;
        }

        /* Main Content */
        .main-content {
            padding-top: 100px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Hero Section */
        .hero-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1rem 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 6px 20px rgba(0, 198, 255, 0.3);
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            max-width: 500px;
            margin: 0 auto 1rem;
            line-height: 1.4;
        }

        .signin-btn {
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .signin-btn:hover {
            background: linear-gradient(135deg, #0072FF 0%, #0056CC 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 198, 255, 0.3);
        }

        /* Feature Cards */
        .feature-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
            padding-bottom: 1rem;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.08);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.2rem;
            position: relative;
        }

        .feature-icon.courses {
            background: transparent;
            color: #00C6FF;
            border: 2px solid #00C6FF;
        }

        .feature-icon.progress {
            background: transparent;
            color: #FFD700;
            border: 2px solid #FFD700;
        }

        .feature-icon.connect {
            background: transparent;
            color: #34D399;
            border: 2px solid #34D399;
        }

        .feature-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .feature-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.3;
            font-size: 0.8rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .feature-cards {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1rem;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .container {
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .logo-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="<?= base_url() ?>" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                EduFlow
            </a>
            <ul class="nav-links">
                <li><a href="<?= base_url() ?>" class="active">Home</a></li>
                <li><a href="<?= base_url('about') ?>">About</a></li>
                <li><a href="<?= base_url('contact') ?>">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1 class="hero-title">
                    Welcome to <span class="highlight">LMS</span>
                    <a href="<?= base_url('auth') ?>" class="signin-btn">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </h1>
                <p class="hero-subtitle">
                    Your Learning Management System is ready! Explore our comprehensive course catalog, learn about our mission, or get in touch with our support team.
                </p>
            </section>

            <!-- Feature Cards -->
            <section class="feature-cards">
                <div class="feature-card">
                    <div class="feature-icon courses">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="feature-title">Interactive Courses</h3>
                    <p class="feature-description">
                        Access engaging multimedia content and interactive learning materials.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon progress">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="feature-title">Track Progress</h3>
                    <p class="feature-description">
                        Monitor your learning journey with detailed progress analytics.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon connect">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Connect & Learn</h3>
                    <p class="feature-description">
                        Join study groups and collaborate with fellow learners worldwide.
                    </p>
                </div>
            </section>
        </div>
    </main>
</body>
</html>

