<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - SMA NU Kaplongan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?= base_url('assets/css/enterprise-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/enterprise-components.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/toast.css') ?>">
    <?= $this->renderSection('extra_css') ?>
</head>
<body>
    <div class="enterprise-layout">
        <!-- Sidebar Navigation -->
        <aside class="enterprise-sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="sidebar-logo-icon">SN</div>
                    <div class="sidebar-logo-text">
                        <h1>SMA NU</h1>
                        <span>Sistem Absensi</span>
                    </div>
                </div>
                <button class="sidebar-toggle" aria-label="Toggle Sidebar">
                    <span style="font-size: 1.25rem;">☰</span>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <?php if (session()->get('role') === 'admin'): ?>
                    <!-- Admin Navigation -->
                    <div class="nav-section">
                        <div class="nav-section-title">Menu Utama</div>
                        <div class="nav-items">
                            <a href="<?= base_url('/dashboard') ?>" class="nav-item <?= current_url() == base_url('/dashboard') ? 'active' : '' ?>">
                                <span class="nav-icon">📊</span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                            <a href="<?= base_url('/attendance/admin') ?>" class="nav-item <?= strpos(current_url(), '/attendance') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">✓</span>
                                <span class="nav-text">Absensi</span>
                            </a>
                            <a href="<?= base_url('/recap/admin') ?>" class="nav-item <?= strpos(current_url(), '/recap') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">📈</span>
                                <span class="nav-text">Rekap Absensi</span>
                            </a>
                        </div>
                    </div>

                    <div class="nav-section">
                        <div class="nav-section-title">Data Master</div>
                        <div class="nav-items">
                            <a href="<?= base_url('/master') ?>" class="nav-item <?= strpos(current_url(), '/master') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">📚</span>
                                <span class="nav-text">Master Data</span>
                            </a>
                            <a href="<?= base_url('/academic/years') ?>" class="nav-item <?= strpos(current_url(), '/academic') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">📅</span>
                                <span class="nav-text">Tahun Ajaran</span>
                            </a>
                        </div>
                    </div>

                    <div class="nav-section">
                        <div class="nav-section-title">Sistem</div>
                        <div class="nav-items">
                            <a href="<?= base_url('/activity-logs') ?>" class="nav-item <?= strpos(current_url(), '/activity-logs') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">📋</span>
                                <span class="nav-text">Log Aktivitas</span>
                            </a>
                            <a href="<?= base_url('/user/profile') ?>" class="nav-item <?= strpos(current_url(), '/user/profile') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">⚙️</span>
                                <span class="nav-text">Pengaturan</span>
                            </a>
                        </div>
                    </div>

                <?php elseif (session()->get('role') === 'guru'): ?>
                    <!-- Teacher Navigation -->
                    <div class="nav-section">
                        <div class="nav-section-title">Menu Utama</div>
                        <div class="nav-items">
                            <a href="<?= base_url('/dashboard') ?>" class="nav-item <?= current_url() == base_url('/dashboard') ? 'active' : '' ?>">
                                <span class="nav-icon">📊</span>
                                <span class="nav-text">Dashboard</span>
                            </a>
                            <a href="<?= base_url('/attendance') ?>" class="nav-item <?= strpos(current_url(), '/attendance') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">✓</span>
                                <span class="nav-text">Input Absensi</span>
                            </a>
                            <a href="<?= base_url('/recap/teacher') ?>" class="nav-item <?= strpos(current_url(), '/recap') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">📈</span>
                                <span class="nav-text">Rekap Saya</span>
                            </a>
                        </div>
                    </div>

                    <div class="nav-section">
                        <div class="nav-section-title">Akun</div>
                        <div class="nav-items">
                            <a href="<?= base_url('/user/profile') ?>" class="nav-item <?= strpos(current_url(), '/user/profile') !== false ? 'active' : '' ?>">
                                <span class="nav-icon">👤</span>
                                <span class="nav-text">Profil Saya</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </nav>

            <!-- Sidebar Footer with User Info -->
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="user-avatar">
                        <?= strtoupper(substr(session()->get('name'), 0, 2)) ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= esc(session()->get('name')) ?></div>
                        <div class="user-role"><?= esc(session()->get('role')) ?></div>
                    </div>
                </div>
                <a href="<?= base_url('/logout') ?>" class="nav-item" style="margin-top: 0.5rem; color: var(--enterprise-error-600);">
                    <span class="nav-icon">🚪</span>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="enterprise-main">
            <!-- Header -->
            <header class="enterprise-header">
                <div class="header-left">
                    <button class="mobile-menu-toggle header-action" aria-label="Toggle Menu" style="display: none;">
                        <span style="font-size: 1.25rem;">☰</span>
                    </button>
                    <h1 class="page-title"><?= esc($title ?? 'Dashboard') ?></h1>
                </div>

                <div class="header-right">
                    <!-- Dark Mode Toggle -->
                    <button class="theme-toggle" aria-label="Toggle Theme">
                        <span class="theme-icon" style="font-size: 1.25rem;">🌙</span>
                    </button>

                    <!-- Notifications (placeholder for future) -->
                    <div class="header-action" style="display: none;">
                        <span style="font-size: 1.25rem;">🔔</span>
                        <span class="header-action-badge">3</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="enterprise-content">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/toast.js') ?>"></script>
    <script src="<?= base_url('assets/js/enterprise.js') ?>"></script>

    <!-- Flash Messages as Toast -->
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.success('<?= addslashes(session()->getFlashdata('success')) ?>');
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.error('<?= addslashes(session()->getFlashdata('error')) ?>');
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.warning('<?= addslashes(session()->getFlashdata('warning')) ?>');
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.info('<?= addslashes(session()->getFlashdata('info')) ?>');
            });
        </script>
    <?php endif; ?>

    <?= $this->renderSection('extra_js') ?>

    <style>
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex !important;
            }

            .page-title {
                font-size: 1.25rem !important;
            }
        }
    </style>
</body>
</html>
