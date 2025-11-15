<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Kelas SMA NU Kaplongan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1>Absensi Kelas<br>SMA NU Kaplongan</h1>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="error-message">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div style="color: #10b981; text-align: center; margin-bottom: 1rem; padding: 0.75rem; background: rgba(16, 185, 129, 0.1); border-radius: 0.5rem;">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('/login/do') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus value="<?= old('username') ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Masuk</button>
            </form>

            <!-- Info Akun Default -->
            <div class="login-info-card" style="margin-top: 2rem; padding: 1.5rem; background: rgba(99, 102, 241, 0.1); border-radius: 0.75rem;">
                <h3 style="font-size: 0.875rem; margin-bottom: 1rem; color: var(--text-primary);">Akun Default:</h3>
                <div class="credentials-list">
                    <div class="credential-item" style="padding: 0.75rem; background: rgba(255, 255, 255, 0.5); border-radius: 0.5rem; margin-bottom: 0.5rem;">
                        <strong>Admin:</strong> <code>admin</code> / <code>admin123</code>
                    </div>
                    <div class="credential-item" style="padding: 0.75rem; background: rgba(255, 255, 255, 0.5); border-radius: 0.5rem;">
                        <strong>Guru:</strong> <code>guru</code> / <code>guru123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
