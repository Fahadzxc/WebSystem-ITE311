<<<<<<< HEAD
<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="card">
    <h1 class="text-center">Contact Us</h1>
    <p class="text-center">Get in touch with our team for any questions or support</p>
    
    <h2>Contact Information</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin: 1rem 0;">
        <div>
            <h3>Address</h3>
            <p>
                Alabel<br>
                Sarangani province<br>
                Philippines
            </p>
        </div>
        
        <div>
            <h3>Phone</h3>
            <p>
                +63 123 456 7890<br>
                +63 987 654 3210<br>
                Mon-Fri: 9AM-6PM
            </p>
        </div>
        
        <div>
            <h3>Email</h3>
            <p>
                info@lmssystem.com<br>
                support@lmssystem.com<br>
                sales@lmssystem.com
            </p>
        </div>
    </div>
    
    <h2>Send us a Message</h2>
    <form>
        <div class="form-group">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" required>
        </div>
        
        <div class="form-group">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" required>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" required>
        </div>
        
        <div class="form-group">
            <label for="subject" class="form-label">Subject</label>
            <input type="text" class="form-control" id="subject" required>
        </div>
        
        <div class="form-group">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" rows="5" required></textarea>
        </div>
        
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Send Message</button>
        </div>
    </form>
    
    <div class="text-center mt-3">
        <a href="<?= base_url('/') ?>" class="btn">Back to Home</a>
        <a href="<?= base_url('/about') ?>" class="btn">About Us</a>
    </div>
</div>
<?= $this->endSection() ?>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - ITE311 Learning Management System</title>
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
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 2rem;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }
        .contact-info h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        .contact-item {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        .contact-item .icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: #3498db;
        }
        .contact-form {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }
        .contact-form h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-group textarea {
            height: 120px;
            resize: vertical;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .office-hours {
            background-color: #e8f5e8;
            padding: 1.5rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        .office-hours h3 {
            color: #27ae60;
            margin-top: 0;
        }
        @media (max-width: 768px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
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
            <h1>Contact Us</h1>
            <p style="text-align: center; color: #7f8c8d; font-size: 1.1rem;">Get in touch with us for support, questions, or feedback about our Learning Management System.</p>

            <div class="contact-grid">
                <div class="contact-info">
                    <h2>Get in Touch</h2>
                    
                    <div class="contact-item">
                        <div class="icon">📧</div>
                        <div>
                            <strong>Email</strong><br>
                            support@ite311-lms.com<br>
                            info@ite311-lms.com
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon">📞</div>
                        <div>
                            <strong>Phone</strong><br>
                            +1 (555) 123-4567<br>
                            +1 (555) 987-6543
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon">📍</div>
                        <div>
                            <strong>Address</strong><br>
                            123 Education Street<br>
                            Learning City, LC 12345<br>
                            United States
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="icon">🌐</div>
                        <div>
                            <strong>Website</strong><br>
                            www.ite311-lms.com
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h2>Send us a Message</h2>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>

            <div class="office-hours">
                <h3>📅 Office Hours</h3>
                <p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM (EST)</p>
                <p><strong>Saturday:</strong> 10:00 AM - 4:00 PM (EST)</p>
                <p><strong>Sunday:</strong> Closed</p>
                <p><em>We typically respond to emails within 24 hours during business days.</em></p>
            </div>
        </div>
    </div>
</body>
</html>
>>>>>>> 6792f4b9228d9b5d4ba0e8ffb7ffe8aadfd2764c
