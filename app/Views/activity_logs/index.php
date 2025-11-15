<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <div class="flex-between mb-2">
        <h2 class="gradient-text">📋 Log Aktivitas Sistem</h2>
        <button type="button" onclick="showClearModal()" class="btn-danger btn-sm">
            🗑️ Hapus Log Lama
        </button>
    </div>

    <!-- Filter Section -->
    <form method="get" action="<?= base_url('/activity-logs') ?>">
        <div class="form-row mb-2">
            <div class="form-group">
                <label>Aksi</label>
                <select name="action" id="filterAction">
                    <option value="">Semua Aksi</option>
                    <?php foreach ($actions as $act): ?>
                        <option value="<?= esc($act['action']) ?>" <?= $filters['action'] == $act['action'] ? 'selected' : '' ?>>
                            <?= esc($act['action']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Dari</label>
                <input type="date" name="date_from" value="<?= esc($filters['date_from']) ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Sampai</label>
                <input type="date" name="date_to" value="<?= esc($filters['date_to']) ?>">
            </div>
            <div class="form-group flex" style="align-items: flex-end;">
                <button type="submit" class="btn-primary ripple">Filter</button>
            </div>
        </div>
    </form>

    <!-- Logs Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">Tidak ada log aktivitas</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $index => $log): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <small>
                                    <?= date('d M Y', strtotime($log['created_at'])) ?><br>
                                    <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($log['user_name']): ?>
                                    <strong><?= esc($log['user_name']) ?></strong><br>
                                    <small style="color: var(--text-secondary);"><?= esc($log['username']) ?></small>
                                <?php else: ?>
                                    <span style="color: var(--text-secondary);">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($log['role']): ?>
                                    <span style="padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;
                                                 background: <?= $log['role'] == 'admin' ? '#3b82f6' : '#10b981' ?>; color: white;">
                                        <?= strtoupper($log['role']) ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">
                                    <?= esc($log['action']) ?>
                                </code>
                            </td>
                            <td><?= esc($log['description'] ?? '-') ?></td>
                            <td><small><?= esc($log['ip_address']) ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Hapus Log Lama -->
<div id="clearModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="card" style="max-width: 500px; margin: 2rem;">
        <h3 style="margin-bottom: 1rem;">Hapus Log Lama</h3>
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
            Log yang lebih lama dari jumlah hari yang ditentukan akan dihapus permanen.
        </p>

        <form method="post" action="<?= base_url('/activity-logs/clear') ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Hapus log lebih dari (hari)</label>
                <input type="number" name="days" value="90" min="1" required>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn-primary" style="background: #ef4444; border-color: #ef4444;">
                    Hapus
                </button>
                <button type="button" onclick="hideClearModal()" class="btn-secondary">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
function showClearModal() {
    document.getElementById('clearModal').style.display = 'flex';
}

function hideClearModal() {
    document.getElementById('clearModal').style.display = 'none';
}

// Close modal on outside click
document.getElementById('clearModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideClearModal();
    }
});
</script>
<?= $this->endSection() ?>
