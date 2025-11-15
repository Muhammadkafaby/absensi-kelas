<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Welcome Section -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; flex-shrink: 0;">
            👋
        </div>
        <div style="flex: 1;">
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--enterprise-text-primary);">
                Selamat Datang, <?= esc(session()->get('name')) ?>
            </h2>
            <p style="font-size: 1rem; color: var(--enterprise-text-secondary); margin: 0;">
                Kelola absensi siswa Anda dengan mudah dan efisien
            </p>
        </div>
        <div>
            <a href="<?= base_url('/attendance') ?>" class="btn-enterprise btn-primary">
                <span>✓</span>
                <span>Input Absensi</span>
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📚</div>
        <div class="metric-label">Mata Pelajaran</div>
        <div class="metric-value" data-count-up="<?= count($subjects) ?>"><?= count($subjects) ?></div>
        <div class="metric-trend positive">
            <span>▲</span>
            <span>Mapel Diampu</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📝</div>
        <div class="metric-label">Sesi Absensi</div>
        <div class="metric-value" data-count-up="<?= count($recent_sessions) ?>"><?= count($recent_sessions) ?></div>
        <div class="metric-trend positive">
            <span>▲</span>
            <span>Riwayat Terakhir</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📅</div>
        <div class="metric-label">Hari Ini</div>
        <div class="metric-value"><?= date('d') ?></div>
        <div class="metric-trend neutral" style="color: var(--enterprise-text-tertiary);">
            <span><?= date('F Y') ?></span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">⏰</div>
        <div class="metric-label">Waktu Sekarang</div>
        <div class="metric-value" id="current-time" style="font-size: 1.75rem;"><?= date('H:i') ?></div>
        <div class="metric-trend neutral" style="color: var(--enterprise-text-tertiary);">
            <span>WIB</span>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
    <!-- Mata Pelajaran yang Diampu -->
    <div class="enterprise-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Mata Pelajaran</h2>
                <p class="card-subtitle">Mapel yang Anda ampu</p>
            </div>
            <?php if (!empty($subjects)): ?>
                <span class="enterprise-badge badge-success"><?= count($subjects) ?> Mapel</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($subjects)): ?>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <?php foreach ($subjects as $index => $subject): ?>
                    <div style="padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: var(--radius-md); border: 1px solid var(--enterprise-border); transition: all var(--transition-base);"
                         onmouseover="this.style.background='var(--enterprise-primary-50)'; this.style.borderColor='var(--enterprise-primary-300)'"
                         onmouseout="this.style.background='var(--enterprise-bg-secondary)'; this.style.borderColor='var(--enterprise-border)'">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; flex-shrink: 0;">
                                <?= $index + 1 ?>
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; color: var(--enterprise-text-primary); margin-bottom: 0.25rem; font-size: 1rem;">
                                    <?= esc($subject['name']) ?>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--enterprise-text-tertiary);">
                                    Kode: <?= esc($subject['code']) ?>
                                </div>
                            </div>
                            <div style="color: var(--enterprise-primary-500); font-size: 1.25rem; flex-shrink: 0;">
                                📖
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state" style="padding: 2rem;">
                <div class="empty-state-icon">📚</div>
                <h3 class="empty-state-title">Belum Ada Mata Pelajaran</h3>
                <p class="empty-state-message">
                    Anda belum ditugaskan untuk mengajar mata pelajaran apapun.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Riwayat Absensi Terakhir -->
    <div class="enterprise-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Riwayat Absensi</h2>
                <p class="card-subtitle">Sesi absensi terakhir</p>
            </div>
            <?php if (!empty($recent_sessions)): ?>
                <span class="enterprise-badge badge-info"><?= count($recent_sessions) ?> Sesi</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($recent_sessions)): ?>
            <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
                <table class="enterprise-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_sessions as $index => $session): ?>
                            <tr>
                                <td><strong><?= $index + 1 ?></strong></td>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-weight: 600;">
                                            <?= date('d M Y', strtotime($session['date'])) ?>
                                        </span>
                                        <span style="font-size: 0.75rem; color: var(--enterprise-text-tertiary);">
                                            <?= date('l', strtotime($session['date'])) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="enterprise-badge badge-info">
                                        <?= esc($session['class_name']) ?>
                                    </span>
                                </td>
                                <td><?= esc($session['subject_name']) ?></td>
                                <td>
                                    <span class="enterprise-badge badge-neutral">
                                        Jam <?= esc($session['lesson_hour'] ?? '-') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state" style="padding: 2rem;">
                <div class="empty-state-icon">📅</div>
                <h3 class="empty-state-title">Belum Ada Riwayat</h3>
                <p class="empty-state-message">
                    Mulai input absensi untuk melihat riwayat di sini.
                </p>
                <a href="<?= base_url('/attendance') ?>" class="btn-enterprise btn-primary" style="margin-top: 1rem;">
                    <span>✓</span>
                    <span>Input Absensi Sekarang</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="enterprise-card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h2 class="card-title">Aksi Cepat</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
        <a href="<?= base_url('/attendance') ?>" style="text-decoration: none;">
            <div style="padding: 1.5rem; background: var(--enterprise-bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--enterprise-border); cursor: pointer; transition: all var(--transition-base);"
                 onmouseover="this.style.background='var(--enterprise-primary-50)'; this.style.borderColor='var(--enterprise-primary-300)'; this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.background='var(--enterprise-bg-secondary)'; this.style.borderColor='var(--enterprise-border)'; this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">
                        ✓
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Input Absensi</h3>
                        <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Catat kehadiran siswa</p>
                    </div>
                    <div style="color: var(--enterprise-text-tertiary); font-size: 1.5rem;">→</div>
                </div>
            </div>
        </a>

        <a href="<?= base_url('/recap/teacher') ?>" style="text-decoration: none;">
            <div style="padding: 1.5rem; background: var(--enterprise-bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--enterprise-border); cursor: pointer; transition: all var(--transition-base);"
                 onmouseover="this.style.background='var(--enterprise-primary-50)'; this.style.borderColor='var(--enterprise-primary-300)'; this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.background='var(--enterprise-bg-secondary)'; this.style.borderColor='var(--enterprise-border)'; this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">
                        📊
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Rekap Saya</h3>
                        <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Lihat rekap absensi</p>
                    </div>
                    <div style="color: var(--enterprise-text-tertiary); font-size: 1.5rem;">→</div>
                </div>
            </div>
        </a>

        <a href="<?= base_url('/user/profile') ?>" style="text-decoration: none;">
            <div style="padding: 1.5rem; background: var(--enterprise-bg-secondary); border-radius: var(--radius-lg); border: 1px solid var(--enterprise-border); cursor: pointer; transition: all var(--transition-base);"
                 onmouseover="this.style.background='var(--enterprise-primary-50)'; this.style.borderColor='var(--enterprise-primary-300)'; this.style.transform='translateY(-2px)'"
                 onmouseout="this.style.background='var(--enterprise-bg-secondary)'; this.style.borderColor='var(--enterprise-border)'; this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white;">
                        👤
                    </div>
                    <div style="flex: 1;">
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Profil Saya</h3>
                        <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Kelola akun Anda</p>
                    </div>
                    <div style="color: var(--enterprise-text-tertiary); font-size: 1.5rem;">→</div>
                </div>
            </div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
// Update current time
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('current-time').textContent = hours + ':' + minutes;
}

// Update time every minute
setInterval(updateTime, 60000);
updateTime();
</script>
<?= $this->endSection() ?>
