/**
 * Enterprise UI JavaScript
 * Handles sidebar, theme toggle, and advanced interactions
 */

class EnterpriseUI {
    constructor() {
        this.sidebar = document.querySelector('.enterprise-sidebar');
        this.sidebarToggle = document.querySelector('.sidebar-toggle');
        this.themeToggle = document.querySelector('.theme-toggle');
        this.mobileMenuToggle = document.querySelector('.mobile-menu-toggle');

        this.init();
    }

    init() {
        this.initSidebar();
        this.initTheme();
        this.initMobileMenu();
        this.initDropdowns();
        this.initModals();
        this.initTooltips();

        // Load saved preferences
        this.loadPreferences();
    }

    /**
     * Sidebar functionality
     */
    initSidebar() {
        if (!this.sidebarToggle || !this.sidebar) return;

        this.sidebarToggle.addEventListener('click', () => {
            this.toggleSidebar();
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!this.sidebar.contains(e.target) &&
                    !this.mobileMenuToggle?.contains(e.target) &&
                    this.sidebar.classList.contains('open')) {
                    this.closeSidebar();
                }
            }
        });
    }

    toggleSidebar() {
        if (window.innerWidth > 768) {
            // Desktop: collapse/expand
            this.sidebar.classList.toggle('collapsed');
            this.savePreference('sidebarCollapsed', this.sidebar.classList.contains('collapsed'));
        } else {
            // Mobile: open/close
            this.sidebar.classList.toggle('open');
        }
    }

    closeSidebar() {
        this.sidebar.classList.remove('open');
    }

    /**
     * Theme toggle (Dark/Light mode)
     */
    initTheme() {
        if (!this.themeToggle) return;

        this.themeToggle.addEventListener('click', () => {
            this.toggleTheme();
        });
    }

    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        document.documentElement.setAttribute('data-theme', newTheme);
        this.savePreference('theme', newTheme);

        // Update icon
        this.updateThemeIcon(newTheme);

        // Show toast notification
        if (window.Toast) {
            Toast.info(`Mode ${newTheme === 'dark' ? 'Gelap' : 'Terang'} diaktifkan`);
        }
    }

    updateThemeIcon(theme) {
        const icon = this.themeToggle?.querySelector('.theme-icon');
        if (icon) {
            icon.textContent = theme === 'dark' ? '☀️' : '🌙';
        }
    }

    /**
     * Mobile menu toggle
     */
    initMobileMenu() {
        if (!this.mobileMenuToggle) return;

        this.mobileMenuToggle.addEventListener('click', () => {
            this.sidebar.classList.add('open');
        });
    }

    /**
     * Dropdown menus
     */
    initDropdowns() {
        const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const dropdownId = toggle.getAttribute('data-dropdown-toggle');
                const dropdown = document.getElementById(dropdownId);

                if (dropdown) {
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== dropdown) {
                            menu.style.display = 'none';
                        }
                    });

                    // Toggle current dropdown
                    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });
    }

    /**
     * Modal functionality
     */
    initModals() {
        // Modal open triggers
        document.querySelectorAll('[data-modal-open]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modalId = trigger.getAttribute('data-modal-open');
                this.openModal(modalId);
            });
        });

        // Modal close triggers
        document.querySelectorAll('[data-modal-close]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modal = trigger.closest('.modal-overlay');
                if (modal) {
                    this.closeModal(modal.id);
                }
            });
        });

        // Close modal on overlay click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    this.closeModal(overlay.id);
                }
            });
        });

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal-overlay[style*="display: flex"]');
                if (openModal) {
                    this.closeModal(openModal.id);
                }
            }
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Tooltips
     */
    initTooltips() {
        // Tooltips are CSS-only, but we can add position adjustment if needed
        const tooltips = document.querySelectorAll('.tooltip');

        tooltips.forEach(tooltip => {
            const text = tooltip.querySelector('.tooltip-text');
            if (text) {
                tooltip.addEventListener('mouseenter', () => {
                    // Adjust position if tooltip goes off-screen
                    const rect = text.getBoundingClientRect();
                    if (rect.right > window.innerWidth) {
                        text.style.left = 'auto';
                        text.style.right = '0';
                        text.style.transform = 'none';
                    }
                });
            }
        });
    }

    /**
     * Save and load user preferences
     */
    savePreference(key, value) {
        try {
            localStorage.setItem(`enterprise_${key}`, JSON.stringify(value));
        } catch (e) {
            console.warn('Failed to save preference:', e);
        }
    }

    loadPreferences() {
        // Load theme
        try {
            const savedTheme = JSON.parse(localStorage.getItem('enterprise_theme'));
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
                this.updateThemeIcon(savedTheme);
            }
        } catch (e) {
            console.warn('Failed to load theme preference:', e);
        }

        // Load sidebar state (desktop only)
        if (window.innerWidth > 768) {
            try {
                const sidebarCollapsed = JSON.parse(localStorage.getItem('enterprise_sidebarCollapsed'));
                if (sidebarCollapsed && this.sidebar) {
                    this.sidebar.classList.add('collapsed');
                }
            } catch (e) {
                console.warn('Failed to load sidebar preference:', e);
            }
        }
    }
}

/**
 * Table search functionality
 */
class TableSearch {
    constructor(searchInput, table) {
        this.searchInput = searchInput;
        this.table = table;
        this.rows = table.querySelectorAll('tbody tr');

        this.init();
    }

    init() {
        this.searchInput.addEventListener('input', (e) => {
            this.search(e.target.value);
        });
    }

    search(query) {
        const lowerQuery = query.toLowerCase();

        this.rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const shouldShow = text.includes(lowerQuery);
            row.style.display = shouldShow ? '' : 'none';
        });

        // Update empty state
        const visibleRows = Array.from(this.rows).filter(row => row.style.display !== 'none');
        this.updateEmptyState(visibleRows.length === 0);
    }

    updateEmptyState(isEmpty) {
        let emptyRow = this.table.querySelector('.table-empty-state');

        if (isEmpty) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'table-empty-state';
                emptyRow.innerHTML = `
                    <td colspan="100%" style="text-align: center; padding: 3rem; color: var(--enterprise-text-secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                        <div style="font-weight: 600; margin-bottom: 0.5rem;">Tidak ada data ditemukan</div>
                        <div style="font-size: 0.875rem;">Coba kata kunci lain</div>
                    </td>
                `;
                this.table.querySelector('tbody').appendChild(emptyRow);
            }
        } else {
            if (emptyRow) {
                emptyRow.remove();
            }
        }
    }
}

/**
 * Initialize when DOM is ready
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Enterprise UI
    window.enterpriseUI = new EnterpriseUI();

    // Initialize table searches
    document.querySelectorAll('.table-search-input').forEach(input => {
        const wrapper = input.closest('.enterprise-table-wrapper');
        if (wrapper) {
            const table = wrapper.querySelector('.enterprise-table');
            if (table) {
                new TableSearch(input, table);
            }
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Auto-dismiss alerts
    document.querySelectorAll('.enterprise-alert[data-auto-dismiss]').forEach(alert => {
        const delay = parseInt(alert.getAttribute('data-auto-dismiss')) || 5000;
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, delay);
    });

    // Form validation helpers
    document.querySelectorAll('.enterprise-input[required], .enterprise-select[required], .enterprise-textarea[required]').forEach(input => {
        input.addEventListener('blur', () => {
            if (!input.value.trim()) {
                input.classList.add('error');
            } else {
                input.classList.remove('error');
            }
        });

        input.addEventListener('input', () => {
            if (input.value.trim()) {
                input.classList.remove('error');
            }
        });
    });

    // Number formatting for metrics
    document.querySelectorAll('[data-count-up]').forEach(element => {
        const target = parseInt(element.getAttribute('data-count-up'));
        const duration = 2000; // 2 seconds
        const start = 0;
        const increment = target / (duration / 16); // 60fps
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString('id-ID');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current).toLocaleString('id-ID');
            }
        }, 16);
    });

    // Copy to clipboard functionality
    document.querySelectorAll('[data-copy]').forEach(button => {
        button.addEventListener('click', () => {
            const text = button.getAttribute('data-copy');
            navigator.clipboard.writeText(text).then(() => {
                if (window.Toast) {
                    Toast.success('Disalin ke clipboard');
                }
            });
        });
    });
});

/**
 * Utility functions
 */
window.EnterpriseUtils = {
    /**
     * Format number with Indonesian locale
     */
    formatNumber: (num) => {
        return new Intl.NumberFormat('id-ID').format(num);
    },

    /**
     * Format currency (IDR)
     */
    formatCurrency: (num) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(num);
    },

    /**
     * Format date
     */
    formatDate: (date, format = 'long') => {
        const options = format === 'long'
            ? { year: 'numeric', month: 'long', day: 'numeric' }
            : { year: 'numeric', month: '2-digit', day: '2-digit' };
        return new Intl.DateTimeFormat('id-ID', options).format(new Date(date));
    },

    /**
     * Debounce function
     */
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Show loading overlay
     */
    showLoading: (container) => {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="spinner"></div>';
        container.style.position = 'relative';
        container.appendChild(overlay);
        return overlay;
    },

    /**
     * Hide loading overlay
     */
    hideLoading: (overlay) => {
        if (overlay && overlay.parentNode) {
            overlay.remove();
        }
    }
};
