# Admin Dashboard - Modular Structure

This document explains the new modular structure for the admin dashboard that separates concerns into reusable components.

## 🏗️ Structure Overview

```
app/Views/admin/
├── components/           # Reusable UI components
│   ├── sidebar.php      # Navigation sidebar
│   ├── header.php       # Top navigation bar
│   └── footer.php       # Footer component
├── layouts/             # Layout templates
│   └── main.php         # Main layout wrapper
├── dashboard.php        # Dashboard page (refactored)
└── users/              # Example module
    └── index.php        # Users listing page
```

## 🔧 Components

### 1. Sidebar (`components/sidebar.php`)
- **Purpose**: Navigation menu for admin sections
- **Features**: 
  - Logo and branding
  - Navigation links with icons
  - Active state highlighting
  - Responsive design
- **Usage**: Automatically included in main layout

### 2. Header (`components/header.php`)
- **Purpose**: Top navigation bar with search, notifications, and user profile
- **Features**:
  - Page title and breadcrumbs
  - Search functionality
  - Notification dropdown
  - User profile dropdown
  - Responsive design
- **Usage**: Automatically included in main layout

### 3. Footer (`components/footer.php`)
- **Purpose**: Footer with copyright and version information
- **Features**:
  - Copyright notice
  - Version information
  - Responsive layout
- **Usage**: Automatically included in main layout

### 4. Main Layout (`layouts/main.php`)
- **Purpose**: Wrapper template that includes all components
- **Features**:
  - HTML structure
  - CSS and JS includes
  - Flash message handling
  - Content sections
  - Script sections
- **Usage**: Extended by individual pages

## 📱 Responsive Design

- **Desktop**: Full sidebar visible, content area adjusted
- **Mobile**: Sidebar hidden by default, toggle button available
- **Breakpoint**: 768px (Bootstrap's `md` breakpoint)

## 🎨 Styling

### CSS Variables
```css
:root {
    --primary-color: #3b82f6;
    --secondary-color: #64748b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --sidebar-width: 280px;
}
```

### Key Features
- Modern card-based design
- Smooth hover animations
- Consistent spacing and typography
- Bootstrap 5 integration
- Font Awesome icons

## 🚀 Usage

### Creating a New Admin Page

1. **Create the view file**:
```php
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Your page content here -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Page-specific JavaScript
</script>
<?= $this->endSection() ?>
```

2. **Update the controller**:
```php
public function yourPage()
{
    $data = [
        'title' => 'Page Title',
        'currentPage' => 'pagename', // For sidebar highlighting
        'breadcrumb' => [
            ['text' => 'Page Name', 'url' => '#', 'active' => true]
        ],
        'user' => [
            'name' => session()->get('first_name') . ' ' . session()->get('last_name'),
            'role' => ucfirst(session()->get('role')),
            'email' => session()->get('email')
        ]
    ];
    return view('admin/yourpage/index', $data);
}
```

### Adding Navigation Items

Edit `components/sidebar.php` to add new navigation items:
```php
<li class="nav-item">
    <a href="<?= base_url('admin/yourpage') ?>" 
       class="nav-link <?= $currentPage === 'yourpage' ? 'active' : '' ?>">
        <i class="fas fa-icon-name"></i>
        <span>Page Name</span>
    </a>
</li>
```

## 🔌 JavaScript Features

### Built-in Functionality
- Sidebar toggle for mobile
- Auto-hiding alerts
- Tooltip and popover initialization
- Form validation enhancement
- Table row selection
- Bulk actions
- AJAX helper functions
- Toast notifications
- Export functionality

### Custom Functions
```javascript
// Show toast notification
showToast('Success message', 'success', 3000);

// Make AJAX request
adminAjax('/api/endpoint', { method: 'POST', body: data });

// Export data
exportData('csv', dataArray, 'filename.csv');
```

## 📁 File Organization

### Assets
- **CSS**: `public/assets/css/admin.css`
- **JavaScript**: `public/assets/js/admin.js`

### Views
- **Components**: `app/Views/admin/components/`
- **Layouts**: `app/Views/admin/layouts/`
- **Pages**: `app/Views/admin/{module}/`

## 🎯 Benefits

1. **Maintainability**: Components are reusable and easy to update
2. **Consistency**: All pages have the same look and feel
3. **Scalability**: Easy to add new pages and features
4. **Responsiveness**: Mobile-first design approach
5. **Performance**: Optimized CSS and JavaScript
6. **Accessibility**: Proper ARIA labels and semantic HTML

## 🚨 Important Notes

1. **Session Data**: Ensure user session data is available in all admin pages
2. **Authentication**: All admin routes should be protected
3. **Bootstrap**: Uses Bootstrap 5.3.3 - ensure compatibility
4. **Font Awesome**: Uses Font Awesome 6.4.2 for icons
5. **jQuery**: Optional dependency for DataTables integration

## 🔄 Migration from Old Structure

The old dashboard has been refactored to use the new structure:
- Removed inline styles
- Separated concerns into components
- Added proper layout inheritance
- Improved responsive design
- Enhanced JavaScript functionality

## 📞 Support

For questions or issues with the admin dashboard structure, refer to:
- CodeIgniter 4 documentation
- Bootstrap 5 documentation
- Font Awesome documentation
- This README file 