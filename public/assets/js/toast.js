/**
 * Toast Notification System
 * Simple, elegant toast notifications for user feedback
 */

class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create toast container if it doesn't exist
        if (!document.querySelector('.toast-container')) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector('.toast-container');
        }
    }

    show(type, message, title = null, duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        // Icon mapping
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        // Default titles
        const defaultTitles = {
            success: 'Berhasil',
            error: 'Error',
            warning: 'Peringatan',
            info: 'Informasi'
        };

        const icon = icons[type] || 'ℹ';
        const toastTitle = title || defaultTitles[type];

        toast.innerHTML = `
            <span class="toast-icon">${icon}</span>
            <div class="toast-content">
                ${toastTitle ? `<div class="toast-title">${toastTitle}</div>` : ''}
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;

        this.container.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, duration);

        return toast;
    }

    success(message, title = null) {
        return this.show('success', message, title);
    }

    error(message, title = null) {
        return this.show('error', message, title);
    }

    warning(message, title = null) {
        return this.show('warning', message, title);
    }

    info(message, title = null) {
        return this.show('info', message, title);
    }

    clear() {
        this.container.innerHTML = '';
    }
}

// Create global instance
const Toast = new ToastNotification();

// Alternative: Simple function-based API
function showToast(type, message, title = null, duration = 5000) {
    Toast.show(type, message, title, duration);
}

function successToast(message, title = null) {
    Toast.success(message, title);
}

function errorToast(message, title = null) {
    Toast.error(message, title);
}

function warningToast(message, title = null) {
    Toast.warning(message, title);
}

function infoToast(message, title = null) {
    Toast.info(message, title);
}
