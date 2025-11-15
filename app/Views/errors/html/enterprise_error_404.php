<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/enterprise-theme.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/enterprise-components.css') ?>">
</head>
<body style="margin: 0; padding: 0; min-height: 100vh; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; padding: 2rem; max-width: 600px;">
        <!-- 404 Illustration -->
        <div style="font-size: 10rem; font-weight: 700; background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 1rem;">
            404
        </div>

        <!-- Icon -->
        <div style="font-size: 5rem; margin-bottom: 2rem; animation: float 3s ease-in-out infinite;">
            🔍
        </div>

        <!-- Title -->
        <h1 style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">
            Halaman Tidak Ditemukan
        </h1>

        <!-- Description -->
        <p style="font-size: 1.125rem; color: #6b7280; margin-bottom: 2rem; line-height: 1.6;">
            Maaf, halaman yang Anda cari tidak dapat ditemukan.
            Mungkin halaman telah dipindahkan atau URL salah.
        </p>

        <!-- Error Details (if available) -->
        <?php if (ENVIRONMENT !== 'production' && !empty($message)): ?>
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 2rem; text-align: left;">
                <div style="font-weight: 600; color: #dc2626; margin-bottom: 0.5rem;">Debug Info:</div>
                <div style="font-family: monospace; font-size: 0.875rem; color: #6b7280;">
                    <?= esc($message) ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.75rem; background: white; color: #1f2937; border: 1px solid #d1d5db; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.2s;">
                <span>←</span>
                <span>Kembali</span>
            </a>

            <a href="<?= base_url('/dashboard') ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.2s;">
                <span>🏠</span>
                <span>Ke Dashboard</span>
            </a>
        </div>

        <!-- Help Links -->
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #d1d5db;">
            <p style="font-size: 0.875rem; color: #9ca3af; margin-bottom: 1rem;">
                Butuh bantuan? Hubungi administrator
            </p>
            <div style="display: flex; gap: 2rem; justify-content: center; flex-wrap: wrap;">
                <a href="mailto:admin@smanu-kaplongan.sch.id" style="color: #10b981; text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>📧</span>
                    <span>Email Support</span>
                </a>
                <a href="<?= base_url('/') ?>" style="color: #10b981; text-decoration: none; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>🏠</span>
                    <span>Homepage</span>
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        a:hover {
            transform: translateY(-2px);
        }
    </style>
</body>
</html>
