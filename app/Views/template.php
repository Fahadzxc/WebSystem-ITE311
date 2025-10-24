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
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
            overflow: auto;
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
            gap: 1.5rem;
            margin: 0;
            padding: 0;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .navbar-nav li {
            list-style: none;
        }
        
        .nav-link {
            color: #E5E7EB;
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            letter-spacing: 0.3px;
            font-size: 0.9rem;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        
        .nav-link:hover {
            color: #FFFFFF;
            background-color: rgba(20, 184, 166, 0.15);
            border-color: rgba(20, 184, 166, 0.3);
            transform: translateY(-1px);
        }
        
        .nav-link.active {
            color: #FFFFFF;
            background-color: rgba(37,99,235,0.18);
        }
        
        .notification-link {
            position: relative;
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.7rem;
            font-weight: bold;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }
        
        .notification-dropdown {
            min-width: 350px;
            max-width: 400px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #F3F4F6;
            background: #FFFFFF;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }
        
        .notification-item:hover {
            background-color: #F9FAFB;
        }
        
        .notification-item.unread {
            background-color: #EFF6FF;
            border-left: 3px solid #3B82F6;
        }
        
        .notification-message {
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: #6B7280;
            margin-bottom: 6px;
        }
        
        .mark-read-btn {
            font-size: 0.75rem;
            padding: 4px 8px;
            border: none;
            background-color: #3B82F6;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .mark-read-btn:hover {
            background-color: #2563EB;
        }
        
        /* Bootstrap dropdown compatibility */
        .dropdown-menu {
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 0;
            margin-top: 8px;
        }
        
        .dropdown-header {
            padding: 12px 16px;
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            background-color: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .dropdown-divider {
            margin: 0;
            border-color: #E5E7EB;
        }
        
        .dropdown-item-text {
            padding: 12px 16px;
            color: #6B7280;
        }
        
        /* Pulse animation for new notifications */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse 0.6s ease-in-out 3;
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
            margin-top: 1rem;
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
                padding: 0 1rem;
            }
            
            .navbar-nav {
                gap: 0.8rem;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .nav-link {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
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
    <?php if (!session()->get('isLoggedIn')): ?>
    <footer class="footer">
        <p>&copy; <?= date('Y') ?> LMS System. All rights reserved.</p>
    </footer>
    <?php endif; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Notification JavaScript -->
    <?php if (session()->get('isLoggedIn')): ?>
    <script>
    $(document).ready(function() {
        // Load notifications when page loads
        loadNotifications();
        
        // Set up real-time updates every 60 seconds (optional advanced task)
        setInterval(function() {
            loadNotifications();
            console.log('Auto-refreshing notifications...');
        }, 60000); // 60 seconds = 60,000 milliseconds
        
        // Reload notifications when dropdown is opened
        $('#notificationDropdown').on('click', function() {
            loadNotifications();
        });
        
        // Function to load notifications via AJAX using $.get()
        function loadNotifications() {
            $.get('<?= base_url('notifications') ?>', function(response) {
                if (response.success) {
                    updateNotificationBadge(response.unread_count);
                    populateNotificationList(response.notifications);
                } else {
                    console.error('Failed to load notifications:', response.message);
                    $('#notificationList').html('<li><span class="dropdown-item-text text-danger">Error loading notifications</span></li>');
                }
            }).fail(function() {
                console.error('Error loading notifications');
                $('#notificationList').html('<li><span class="dropdown-item-text text-danger">Error loading notifications</span></li>');
            });
        }
        
        // Function to update notification badge
        function updateNotificationBadge(count) {
            const badge = $('#notificationBadge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }
        
        // Function to populate notification list with Bootstrap alert classes
        function populateNotificationList(notifications) {
            const listContainer = $('#notificationList');
            
            if (notifications.length === 0) {
                listContainer.html('<li><span class="dropdown-item-text text-muted">No notifications</span></li>');
                return;
            }
            
            let html = '';
            notifications.forEach(function(notification) {
                const isUnread = notification.is_read == 0;
                const timeAgo = formatTimeAgo(notification.created_at);
                
                html += `
                    <li class="notification-item ${isUnread ? 'unread' : ''}" data-id="${notification.id}">
                        <div class="alert ${isUnread ? 'alert-info' : 'alert-secondary'} mb-0" style="padding: 8px 12px;">
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${timeAgo}</div>
                            ${isUnread ? `<button class="mark-read-btn" onclick="markAsRead(${notification.id})">Mark as Read</button>` : '<small class="text-muted">Read</small>'}
                        </div>
                    </li>
                `;
            });
            
            listContainer.html(html);
        }
        
        // Function to format time ago
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' minutes ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
            return Math.floor(diffInSeconds / 86400) + ' days ago';
        }
        
        // Global function to mark notification as read using $.post()
        window.markAsRead = function(notificationId) {
            $.post('<?= base_url('notifications/mark_read') ?>/' + notificationId, function(response) {
                if (response.success) {
                    // Remove notification from list with animation
                    $(`.notification-item[data-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        // Reload notifications to update count
                        loadNotifications();
                    });
                } else {
                    alert('Failed to mark notification as read: ' + response.message);
                }
            }).fail(function() {
                alert('Error marking notification as read');
            });
        };
    });
    </script>
    <?php endif; ?>
</body>
</html>
