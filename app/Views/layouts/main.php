<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Absensi Kelas') ?> - SMA NU Kaplongan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/toast.css') ?>">
    <?= $this->renderSection('extra_css') ?>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1><?= esc($title ?? 'Absensi Kelas') ?></h1>
            <div class="user-info">
                <span><?= esc(session()->get('name')) ?></span>
                <span class="role-badge"><?= strtoupper(esc(session()->get('role'))) ?></span>
            </div>
        </div>
    </header>

    <nav class="nav">
        <ul>
            <?php if (session()->get('role') === 'admin'): ?>
                <li><a href="<?= base_url('/dashboard') ?>" <?= current_url() == base_url('/dashboard') ? 'class="active"' : '' ?>>Dashboard</a></li>
                <li><a href="<?= base_url('/master/classes') ?>" <?= strpos(current_url(), '/master') !== false ? 'class="active"' : '' ?>>Master Data</a></li>
                <li><a href="<?= base_url('/academic/years') ?>" <?= strpos(current_url(), '/academic') !== false ? 'class="active"' : '' ?>>Tahun Ajaran</a></li>
                <li><a href="<?= base_url('/recap/admin') ?>" <?= strpos(current_url(), '/recap/admin') !== false ? 'class="active"' : '' ?>>Rekap Absensi</a></li>
                <li><a href="<?= base_url('/activity-logs') ?>" <?= strpos(current_url(), '/activity-logs') !== false ? 'class="active"' : '' ?>>Log Aktivitas</a></li>
                <li><a href="<?= base_url('/user/profile') ?>" <?= strpos(current_url(), '/user/profile') !== false ? 'class="active"' : '' ?>>Profil</a></li>
            <?php elseif (session()->get('role') === 'guru'): ?>
                <li><a href="<?= base_url('/dashboard') ?>" <?= current_url() == base_url('/dashboard') ? 'class="active"' : '' ?>>Dashboard</a></li>
                <li><a href="<?= base_url('/attendance') ?>" <?= strpos(current_url(), '/attendance') !== false ? 'class="active"' : '' ?>>Input Absensi</a></li>
                <li><a href="<?= base_url('/recap/teacher') ?>" <?= strpos(current_url(), '/recap/teacher') !== false ? 'class="active"' : '' ?>>Rekap Saya</a></li>
                <li><a href="<?= base_url('/user/profile') ?>" <?= strpos(current_url(), '/user/profile') !== false ? 'class="active"' : '' ?>>Profil</a></li>
            <?php endif; ?>
            <li><a href="<?= base_url('/logout') ?>">Logout</a></li>
        </ul>
    </nav>

    <main class="main">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="footer">
        <p>SMA NU Kaplongan - Sistem Absensi Kelas | Kontak: admin@smanu-kaplongan.sch.id</p>
    </footer>

    <script src="<?= base_url('assets/js/toast.js') ?>"></script>

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
</body>
</html>
