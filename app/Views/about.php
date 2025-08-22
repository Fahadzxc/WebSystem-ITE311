<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFlow - About</title>
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

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
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

        /* Mission Section */
        .mission-section {
            margin-bottom: 4rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: white;
        }

        .mission-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .mission-text p {
            margin-bottom: 1.5rem;
        }

        .mission-features {
            list-style: none;
            margin-top: 2rem;
        }

        .mission-features li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .mission-features li i {
            color: #00C6FF;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        /* Values Section */
        .values-section {
            margin-bottom: 4rem;
        }

        .values-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .values-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .value-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .value-card::before {
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

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.08);
        }

        .value-card:hover::before {
            transform: scaleX(1);
        }

        .value-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            position: relative;
        }

        .value-icon.innovation {
            background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%);
            color: white;
        }

        .value-icon.accessibility {
            background: linear-gradient(135deg, #34D399 0%, #10B981 100%);
            color: white;
        }

        .value-icon.excellence {
            background: linear-gradient(135deg, #A855F7 0%, #7C3AED 100%);
            color: white;
        }

        .value-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .value-description {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            font-size: 1rem;
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

            .section-title,
            .values-title {
                font-size: 2rem;
            }

            .mission-section {
                padding: 0 1rem;
            }

            .values-cards {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1rem;
            }

            .value-card {
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

            .logo {
                font-size: 1.5rem;
            }

            .logo-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .section-title,
            .values-title {
                font-size: 1.8rem;
            }

            .mission-text {
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
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li><a href="<?= base_url('about') ?>" class="active">About</a></li>
                <li><a href="<?= base_url('contact') ?>">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section">
                <h1 class="hero-title">
                    About <span class="highlight">EduFlow</span>
                </h1>
                <p class="hero-subtitle">
                    We're passionate about democratizing education and making high-quality learning accessible to everyone, everywhere.
                </p>
            </section>

            <!-- Mission Section -->
            <section class="mission-section">
                <h2 class="section-title">Our Mission</h2>
                <div class="mission-text">
                    <p>
                        At EduFlow, we believe that education should be accessible, engaging, and transformative. Our mission is to break down barriers to learning and provide world-class educational experiences that empower individuals to achieve their goals.
                    </p>
                    <p>
                        We combine cutting-edge technology with proven pedagogical methods to create an immersive learning environment that adapts to each student's unique needs and learning style.
                    </p>
                </div>
                <ul class="mission-features">
                    <li><i class="fas fa-check"></i>Personalized Learning Paths</li>
                    <li><i class="fas fa-check"></i>Expert-Led Instruction</li>
                    <li><i class="fas fa-check"></i>Global Community</li>
                </ul>
            </section>

            <!-- Values Section -->
            <section class="values-section">
                <h2 class="values-title">Our Values</h2>
                <div class="values-cards">
                    <div class="value-card">
                        <div class="value-icon innovation">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="value-title">Innovation</h3>
                        <p class="value-description">
                            We continuously push the boundaries of educational technology to create better learning experiences.
                        </p>
                    </div>

                    <div class="value-card">
                        <div class="value-icon accessibility">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="value-title">Accessibility</h3>
                        <p class="value-description">
                            Quality education should be available to everyone, regardless of their background or circumstances.
                        </p>
                    </div>

                    <div class="value-card">
                        <div class="value-icon excellence">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="value-title">Excellence</h3>
                        <p class="value-description">
                            We maintain the highest standards in content quality, instruction, and student support.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>

