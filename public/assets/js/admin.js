// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Sidebar toggle for mobile
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Search functionality
    const searchBar = document.querySelector('.search-bar');
    if (searchBar) {
        searchBar.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            // Add your search logic here
            console.log('Searching for:', searchTerm);
        });
    }
    
    // Notification click handler
    const notificationItems = document.querySelectorAll('.dropdown-item[href="#"]');
    notificationItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            // Add your notification handling logic here
            console.log('Notification clicked:', this.textContent);
        });
    });
    
    // Profile dropdown actions
    const profileActions = document.querySelectorAll('.dropdown-item[href*="admin/"]');
    profileActions.forEach(action => {
        action.addEventListener('click', function(e) {
            // Add your profile action logic here
            console.log('Profile action clicked:', this.textContent);
        });
    });
    
    // Logout confirmation
    const logoutBtn = document.querySelector('a[href*="auth/logout"]');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    }
    
    // Add loading state to buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.add('loading');
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        });
    });
    
    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    });
    
    // Table row selection
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            this.classList.toggle('table-active');
        });
    });
    
    // Bulk actions
    const selectAllCheckbox = document.querySelector('#selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Update select all checkbox state
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const totalBoxes = rowCheckboxes.length;
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedBoxes.length === totalBoxes;
                selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
            }
        });
    });
    
    // Chart.js integration (if you want to add charts later)
    if (typeof Chart !== 'undefined') {
        // Add your chart initialization code here
        console.log('Chart.js is available');
    }
    
    // DataTables integration (if you want to add data tables later)
    if (typeof $.fn.DataTable !== 'undefined') {
        // Add your DataTables initialization code here
        console.log('DataTables is available');
    }
    
    // AJAX request helper
    window.adminAjax = function(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        return fetch(url, finalOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('AJAX request failed:', error);
                throw error;
            });
    };
    
    // Toast notification helper
    window.showToast = function(message, type = 'info', duration = 3000) {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, { delay: duration });
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    };
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1055';
        document.body.appendChild(container);
        return container;
    }
    
    // Export functionality
    window.exportData = function(format, data, filename) {
        let content, mimeType;
        
        switch (format) {
            case 'csv':
                content = convertToCSV(data);
                mimeType = 'text/csv';
                break;
            case 'json':
                content = JSON.stringify(data, null, 2);
                mimeType = 'application/json';
                break;
            default:
                console.error('Unsupported export format:', format);
                return;
        }
        
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || `export.${format}`;
        a.click();
        window.URL.revokeObjectURL(url);
    };
    
    function convertToCSV(data) {
        if (!Array.isArray(data) || data.length === 0) return '';
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
        ].join('\n');
        
        return csvContent;
    }
    
    console.log('Admin dashboard JavaScript loaded successfully');
}); 