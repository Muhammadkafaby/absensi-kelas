<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/enterprise-theme.css') ?>">
</head>
<body style="margin: 0; padding: 0; min-height: 100vh; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; padding: 2rem; max-width: 600px;">
        <!-- Error Icon -->
        <div style="font-size: 6rem; margin-bottom: 2rem; animation: pulse 2s ease-in-out infinite;">
            ⚠️
        </div>

        <!-- Title -->
        <h1 style="font-size: 2.5rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">
            Oops! Terjadi Kesalahan
        </h1>

        <!-- Description -->
        <p style="font-size: 1.125rem; color: #6b7280; margin-bottom: 2rem; line-height: 1.6;">
            Mohon maaf, terjadi kesalahan pada server. Tim kami telah menerima notifikasi dan sedang memperbaikinya.
        </p>

        <!-- Error Code -->
        <div style="display: inline-block; padding: 0.5rem 1rem; background: rgba(239, 68, 68, 0.1); border-radius: 8px; font-family: monospace; color: #dc2626; margin-bottom: 2rem;">
            Error Code: <?= $code ?? 500 ?>
        </div>

        <!-- Actions -->
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <button onclick="location.reload()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.75rem; background: white; color: #1f2937; border: 1px solid #d1d5db; border-radius: 12px; font-weight: 600; cursor: pointer;">
                <span>🔄</span>
                <span>Muat Ulang</span>
            </button>

            <a href="<?= base_url('/dashboard') ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                <span>🏠</span>
                <span>Ke Dashboard</span>
            </a>
        </div>

        <!-- Support Info -->
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #d1d5db;">
            <p style="font-size: 0.875rem; color: #9ca3af;">
                Jika masalah berlanjut, silakan hubungi:
            </p>
            <a href="mailto:admin@smanu-kaplongan.sch.id" style="color: #10b981; text-decoration: none; font-weight: 600;">
                admin@smanu-kaplongan.sch.id
            </a>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
    </style>
</body>
</html>
