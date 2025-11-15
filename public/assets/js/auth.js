// Authentication and session management
class Auth {
    constructor() {
        this.token = localStorage.getItem('token');
        this.user = JSON.parse(localStorage.getItem('user') || 'null');
        this.checkAuth();
    }

    async login(username, password) {
        try {
            const response = await fetch(`${CONFIG.API_BASE_URL}?mode=login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ username, password })
            });

            const data = await response.json();

            if (data.ok) {
                this.token = data.token;
                this.user = {
                    nama: data.nama,
                    role: data.role,
                    mapel: data.mapel
                };

                localStorage.setItem('token', this.token);
                localStorage.setItem('user', JSON.stringify(this.user));

                this.showNotification('Login berhasil!', 'success');
                this.redirectBasedOnRole();
            } else {
                throw new Error('Login gagal');
            }
        } catch (error) {
            console.error('Login error:', error);
            this.showNotification('Username atau password salah', 'error');
        }
    }

    logout() {
        this.token = null;
        this.user = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = 'login.html';
    }

    checkAuth() {
        if (!this.token || !this.user) {
            if (window.location.pathname !== '/login.html' && !window.location.pathname.includes('login.html')) {
                window.location.href = 'login.html';
            }
        } else {
            if (window.location.pathname.includes('login.html')) {
                this.redirectBasedOnRole();
            }
        }
    }

    redirectBasedOnRole() {
        if (this.user.role === 'admin') {
            window.location.href = 'dashboard.html';
        } else if (this.user.role === 'guru') {
            window.location.href = 'dashboard.html';
        }
    }

    isAuthenticated() {
        return !!this.token && !!this.user;
    }

    getUser() {
        return this.user;
    }

    getToken() {
        return this.token;
    }

    showNotification(message, type = 'info') {
        // Simple notification implementation
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            color: white;
            background-color: ${type === 'success' ? '#2E7D32' : type === 'error' ? '#D32F2F' : '#1976D2'};
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
}

// Initialize auth on page load
const auth = new Auth();

// Logout functionality
document.addEventListener('DOMContentLoaded', function() {
    const logoutLinks = document.querySelectorAll('[data-action="logout"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            auth.logout();
        });
    });

    // Update user info in header
    if (auth.isAuthenticated()) {
        const userNameEl = document.getElementById('userName');
        const userRoleEl = document.getElementById('userRole');

        if (userNameEl && userRoleEl) {
            const user = auth.getUser();
            userNameEl.textContent = user.nama;
            userRoleEl.textContent = user.role;
        }
    }
});