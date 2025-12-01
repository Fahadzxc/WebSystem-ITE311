<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'LMS System' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Color Palette CSS Variables -->
    <style>
        :root {
            --bs-primary: #1E40AF;
            --bs-primary-rgb: 30, 64, 175;
            --bs-accent: #3B82F6;
            --bs-accent-rgb: 59, 130, 246;
            --bs-bg-light: #F8FAFC;
            --bs-text-dark: #0F172A;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: var(--bs-bg-light);
            color: var(--bs-text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .bg-primary-custom {
            background-color: var(--bs-primary) !important;
        }
        
        .text-primary-custom {
            color: var(--bs-primary) !important;
        }
        
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        
        .btn-primary:hover {
            background-color: #1E3A8A;
            border-color: #1E3A8A;
        }
        
        .btn-primary:focus {
            background-color: #1E3A8A;
            border-color: #1E3A8A;
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.5);
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }
        
        main {
            flex: 1;
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
        
        /* Custom scrollbar for notification dropdown */
        #notificationList::-webkit-scrollbar {
            width: 6px;
        }
        
        #notificationList::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        #notificationList::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        #notificationList::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Notification item hover effect */
        .notification-item {
            cursor: pointer;
        }
        
        .notification-item:hover {
            background-color: #f8fafc !important;
        }
        
        .notification-item:last-child {
            border-bottom: none !important;
        }
        
        /* Button styling */
        .btn-primary-custom {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        
        .btn-primary-custom:hover {
            background-color: #1E3A8A;
            border-color: #1E3A8A;
        }
        
        /* Notification dropdown link hover */
        .dropdown-item.text-primary-custom:hover {
            background-color: var(--bs-primary) !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <?= $this->include('templates/header') ?>

    <!-- Main Content Area -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?php if (!session()->get('isLoggedIn')): ?>
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container">
            <p class="text-center text-muted mb-0">&copy; <?= date('Y') ?> LMS System. All rights reserved.</p>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Page-specific scripts -->
    <?= $this->renderSection('scripts') ?>
    
    <!-- Notification JavaScript -->
    <?php if (session()->get('isLoggedIn')): ?>
    <script>
    $(document).ready(function() {
        // Load notifications when page loads
        loadNotifications();
        
        // Set up real-time updates every 60 seconds
        setInterval(function() {
            loadNotifications();
        }, 60000);
        
        // Reload notifications when dropdown is opened
        $('#notificationDropdown').on('click', function() {
            loadNotifications();
        });
        
        // Function to load notifications via AJAX
        function loadNotifications() {
            $.get('<?= base_url('notifications/api') ?>', function(response) {
                if (response && response.success) {
                    updateNotificationBadge(response.unread_count);
                    populateNotificationList(response.notifications);
                } else {
                    const errorMsg = response && response.message ? response.message : 'Unknown error';
                    console.error('Failed to load notifications:', errorMsg);
                    $('#notificationList').html(`
                        <div class="text-center py-4 px-4">
                            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                            <p class="text-danger mb-0 mt-2 small">Error loading notifications</p>
                        </div>
                    `);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error loading notifications:', error);
                $('#notificationList').html(`
                    <div class="text-center py-4 px-4">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                        <p class="text-danger mb-0 mt-2 small">Error loading notifications</p>
                    </div>
                `);
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
        
        // Function to populate notification list
        function populateNotificationList(notifications) {
            const listContainer = $('#notificationList');
            const notificationCount = $('#notificationCount');
            
            // Update count badge
            notificationCount.text(notifications.length);
            
            if (notifications.length === 0) {
                listContainer.html(`
                    <div class="text-center py-5 px-4">
                        <i class="fas fa-bell-slash text-muted" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        <p class="text-muted mb-0 mt-3">No notifications</p>
                        <small class="text-muted">You're all caught up!</small>
                    </div>
                `);
                return;
            }
            
            // Limit to 5 notifications to avoid scrollbar
            const displayNotifications = notifications.slice(0, 5);
            const hasMore = notifications.length > 5;
            
            let html = '';
            displayNotifications.forEach(function(notification) {
                const isUnread = notification.is_read == 0;
                const timeAgo = formatTimeAgo(notification.created_at);
                const icon = isUnread ? 'fa-circle text-primary-custom' : 'fa-check-circle text-muted';
                
                html += `
                    <div class="notification-item ${isUnread ? 'bg-light border-start border-primary border-3' : ''} p-3" style="transition: all 0.2s ease; border-bottom: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div class="rounded-circle ${isUnread ? 'bg-primary-custom' : 'bg-secondary'}" style="width: 8px; height: 8px; opacity: ${isUnread ? '1' : '0.5'};"></div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-2 fw-semibold" style="font-size: 0.95rem; color: ${isUnread ? '#0F172A' : '#6B7280'}; line-height: 1.5;">
                                    ${notification.message}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>${timeAgo}
                                    </small>
                                    ${isUnread ? `
                                        <button class="btn btn-sm btn-primary-custom text-white" onclick="markAsRead(${notification.id})" style="font-size: 0.75rem; padding: 0.3rem 0.8rem; border-radius: 6px;">
                                            <i class="fas fa-check me-1"></i>Mark Read
                                        </button>
                                    ` : `
                                        <span class="badge bg-light text-muted border" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                            <i class="fas fa-check me-1"></i>Read
                                        </span>
                                    `}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            if (hasMore) {
                html += `
                    <div class="text-center py-2 border-top bg-light">
                        <small class="text-muted">
                            <i class="fas fa-ellipsis-h me-1"></i>
                            ${notifications.length - 5} more notification${notifications.length - 5 > 1 ? 's' : ''}
                        </small>
                    </div>
                `;
            }
            
            listContainer.html(html);
        }
        
        // Function to format time ago
        function formatTimeAgo(dateString) {
            if (!dateString) return 'Just now';
            
            // Parse MySQL datetime format (YYYY-MM-DD HH:MM:SS) properly
            // MySQL datetime is stored without timezone, so we need to parse it as local time
            let date;
            if (dateString.includes(' ')) {
                // MySQL datetime format: "2025-11-21 13:40:00"
                // Split into date and time parts
                const parts = dateString.split(' ');
                const datePart = parts[0]; // "2025-11-21"
                const timePart = parts[1]; // "13:40:00"
                
                // Create date object using local time (not UTC)
                // Format: new Date(year, month, day, hour, minute, second)
                const [year, month, day] = datePart.split('-').map(Number);
                const [hour, minute, second] = timePart.split(':').map(Number);
                date = new Date(year, month - 1, day, hour, minute, second || 0);
            } else {
                date = new Date(dateString);
            }
            
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            // Handle negative differences (future dates) or invalid dates
            if (isNaN(date.getTime()) || diffInSeconds < 0) {
                return 'Just now';
            }
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' minutes ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
            return Math.floor(diffInSeconds / 86400) + ' days ago';
        }
        
        // Global function to mark notification as read
        window.markAsRead = function(notificationId) {
            $.ajaxSetup({
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                }
            });
            
            $.post('<?= base_url('notifications/mark_read') ?>/' + notificationId, {
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            }, function(response) {
                if (response && response.success) {
                    // Simply reload notifications to update the UI
                    loadNotifications();
                } else {
                    const errorMsg = response && response.message ? response.message : 'Unknown error';
                    alert('Failed to mark notification as read: ' + errorMsg);
                }
            }, 'json').fail(function(xhr, status, error) {
                alert('Error marking notification as read: ' + error);
            });
        };
    });
    </script>
    <?php endif; ?>
</body>
</html>
