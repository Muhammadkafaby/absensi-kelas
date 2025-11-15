<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            📋
        </div>
        <div style="flex: 1;">
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Log Aktivitas</h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">Riwayat aktivitas dan perubahan dalam sistem</p>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📊</div>
        <div class="metric-label">Total Log</div>
        <div class="metric-value"><?= count($logs ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">👥</div>
        <div class="metric-label">Total Users</div>
        <div class="metric-value"><?= count(array_unique(array_column($logs ?? [], 'username'))) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📅</div>
        <div class="metric-label">Hari Ini</div>
        <div class="metric-value"><?= date('d M') ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">⏰</div>
        <div class="metric-label">Waktu</div>
        <div class="metric-value" style="font-size: 1.75rem;"><?= date('H:i') ?></div>
    </div>
</div>

<!-- Logs Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Riwayat Aktivitas</h2>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" class="table-search-input" placeholder="Cari aktivitas...">
            </div>
        </div>

        <table class="enterprise-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 150px;">Waktu</th>
                    <th style="width: 120px;">User</th>
                    <th style="width: 100px;">Role</th>
                    <th>Aktivitas</th>
                    <th style="width: 150px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $index => $log): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <div style="font-weight: 600;"><?= date('d M Y', strtotime($log['created_at'])) ?></div>
                                    <div style="color: var(--enterprise-text-tertiary); font-size: 0.75rem;"><?= date('H:i:s', strtotime($log['created_at'])) ?></div>
                                </div>
                            </td>
                            <td><strong><?= esc($log['username']) ?></strong></td>
                            <td>
                                <span class="enterprise-badge <?= $log['role'] === 'admin' ? 'badge-error' : 'badge-info' ?>">
                                    <?= strtoupper($log['role']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <div style="font-weight: 600; color: var(--enterprise-text-primary);"><?= esc($log['action']) ?></div>
                                    <?php if (!empty($log['description'])): ?>
                                        <div style="color: var(--enterprise-text-tertiary); font-size: 0.75rem; margin-top: 0.25rem;"><?= esc($log['description']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="font-family: monospace; font-size: 0.875rem;"><?= esc($log['ip_address']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📋</div>
                                <h3 class="empty-state-title">Belum Ada Log</h3>
                                <p class="empty-state-message">Log aktivitas akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
