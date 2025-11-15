<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi SMA NU Kaplongan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/enterprise-login.css') ?>">
</head>
<body class="login-page">
    <!-- Floating particles -->
    <div class="login-particles">
        <div class="particle" style="left: 10%;"></div>
        <div class="particle" style="left: 20%;"></div>
        <div class="particle" style="left: 40%;"></div>
        <div class="particle" style="left: 60%;"></div>
        <div class="particle" style="left: 80%;"></div>
    </div>

    <div class="enterprise-login-container">
        <!-- Left Side - Branding -->
        <div class="login-brand-section">
            <div class="login-brand-content">
                <!-- Logo -->
                <div class="login-logo">
                    <div class="login-logo-text">SN</div>
                </div>

                <!-- Brand Text -->
                <h1 class="login-brand-title">Sistem Absensi Digital</h1>
                <p class="login-brand-subtitle">SMA NU Kaplongan</p>
                <p class="login-brand-description">
                    Platform modern untuk mengelola absensi siswa dengan mudah, cepat, dan akurat
                </p>

                <!-- Features -->
                <div class="login-features">
                    <div class="feature-item">
                        <div class="feature-icon">⚡</div>
                        <div class="feature-title">Cepat & Efisien</div>
                        <div class="feature-desc">Input absensi dalam hitungan detik</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📊</div>
                        <div class="feature-title">Laporan Real-time</div>
                        <div class="feature-desc">Pantau kehadiran secara langsung</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <div class="feature-title">Aman & Terpercaya</div>
                        <div class="feature-desc">Data terlindungi dengan baik</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📱</div>
                        <div class="feature-title">Responsif</div>
                        <div class="feature-desc">Akses dari mana saja</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-form-section">
            <div class="login-card-wrapper">
                <div class="enterprise-login-card">
                    <!-- Card Header -->
                    <div class="login-card-header">
                        <h2 class="login-card-title">Selamat Datang</h2>
                        <p class="login-card-subtitle">Silakan masuk ke akun Anda</p>
                    </div>

                    <!-- Error Message -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="login-alert error">
                            <div class="login-alert-icon">⚠️</div>
                            <div><?= esc(session()->getFlashdata('error')) ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Success Message -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="login-alert success">
                            <div class="login-alert-icon">✓</div>
                            <div><?= esc(session()->getFlashdata('success')) ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="<?= base_url('/login/do') ?>" method="POST" class="enterprise-login-form" id="loginForm">
                        <?= csrf_field() ?>

                        <!-- Username -->
                        <div class="enterprise-form-group">
                            <label for="username" class="enterprise-form-label">Username</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="enterprise-form-input"
                                placeholder="Masukkan username Anda"
                                required
                                autofocus
                                value="<?= old('username') ?>"
                            >
                        </div>

                        <!-- Password -->
                        <div class="enterprise-form-group">
                            <label for="password" class="enterprise-form-label">Password</label>
                            <div class="password-input-wrapper">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="enterprise-form-input"
                                    placeholder="Masukkan password Anda"
                                    required
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <span id="passwordIcon">👁️</span>
                                </button>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox" name="remember" id="remember">
                                <span>Ingat saya</span>
                            </label>
                            <a href="#" class="forgot-password" onclick="event.preventDefault(); alert('Hubungi administrator untuk reset password');">Lupa password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="enterprise-login-button" id="loginButton">
                            <span>Masuk</span>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="login-divider">
                        <span>Akun Default untuk Testing</span>
                    </div>

                    <!-- Default Accounts Info -->
                    <div class="default-accounts">
                        <div class="default-accounts-title">
                            <span>🔑</span>
                            <span>Kredensial Default</span>
                        </div>

                        <div class="account-item">
                            <div class="account-role">👨‍💼 Admin</div>
                            <div class="account-credentials">
                                <div class="credential-badge">admin</div>
                                <div class="credential-badge">admin123</div>
                            </div>
                        </div>

                        <div class="account-item">
                            <div class="account-role">👨‍🏫 Guru</div>
                            <div class="account-credentials">
                                <div class="credential-badge">guru</div>
                                <div class="credential-badge">guru123</div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="login-footer">
                        <p>&copy; <?= date('Y') ?> SMA NU Kaplongan. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.textContent = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                passwordIcon.textContent = '👁️';
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const button = document.getElementById('loginButton');
            button.classList.add('loading');
            button.querySelector('span').textContent = 'Memproses...';
        });

        // Auto-fill from default accounts
        document.querySelectorAll('.account-item').forEach(item => {
            item.addEventListener('click', function() {
                const credentials = this.querySelectorAll('.credential-badge');
                document.getElementById('username').value = credentials[0].textContent;
                document.getElementById('password').value = credentials[1].textContent;

                // Visual feedback
                this.style.background = 'rgba(16, 185, 129, 0.2)';
                setTimeout(() => {
                    this.style.background = '';
                }, 300);
            });
        });

        // Keyboard shortcut (Ctrl + K untuk quick login admin)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                document.getElementById('username').value = 'admin';
                document.getElementById('password').value = 'admin123';
                document.getElementById('username').focus();
            }
        });

        // Enter key submit
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').submit();
            }
        });

        // Floating animation for logo
        const logo = document.querySelector('.login-logo');
        let direction = 1;
        setInterval(() => {
            const currentTransform = logo.style.transform || 'translateY(0px)';
            const currentY = parseFloat(currentTransform.match(/-?\d+/)?.[0] || 0);
            const newY = currentY + (direction * 0.5);

            if (Math.abs(newY) > 10) {
                direction *= -1;
            }

            logo.style.transform = `translateY(${newY}px)`;
        }, 50);
    </script>
</body>
</html>
