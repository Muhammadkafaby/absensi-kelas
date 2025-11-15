<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                📅
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Tahun Ajaran</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola tahun ajaran akademik</p>
            </div>
        </div>
        <a href="<?= base_url('/academic/years/create') ?>" class="btn-enterprise btn-primary">
            <span>➕</span>
            <span>Tambah Tahun Ajaran</span>
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📅</div>
        <div class="metric-label">Total Tahun Ajaran</div>
        <div class="metric-value"><?= count($years ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">✅</div>
        <div class="metric-label">Tahun Aktif</div>
        <div class="metric-value"><?= count(array_filter($years ?? [], fn($y) => $y['is_active'] ?? false)) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📊</div>
        <div class="metric-label">Semester</div>
        <div class="metric-value"><?= array_sum(array_column($years ?? [], 'semester_count')) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">🎓</div>
        <div class="metric-label">Tahun Berjalan</div>
        <div class="metric-value" style="font-size: 1.5rem;"><?= date('Y') ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Daftar Tahun Ajaran</h2>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <table class="enterprise-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tahun Ajaran</th>
                    <th style="width: 150px;">Tanggal Mulai</th>
                    <th style="width: 150px;">Tanggal Selesai</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 100px;">Semester</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($years)): ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📅</div>
                                <h3 class="empty-state-title">Belum Ada Tahun Ajaran</h3>
                                <p class="empty-state-message">Tambahkan tahun ajaran baru</p>
                                <a href="<?= base_url('/academic/years/create') ?>" class="btn-enterprise btn-primary" style="margin-top: 1rem;">
                                    <span>➕</span>
                                    <span>Tambah Tahun Ajaran</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($years as $index => $year): ?>
                        <tr>
                            <td><strong><?= $index + 1 ?></strong></td>
                            <td>
                                <div style="font-weight: 600; font-size: 1.125rem; color: var(--enterprise-text-primary);">
                                    <?= esc($year['name']) ?>
                                </div>
                            </td>
                            <td><?= date('d M Y', strtotime($year['start_date'])) ?></td>
                            <td><?= date('d M Y', strtotime($year['end_date'])) ?></td>
                            <td>
                                <?php if ($year['is_active']): ?>
                                    <span class="enterprise-badge badge-success">
                                        <span class="status-dot success"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="enterprise-badge badge-neutral">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="enterprise-badge badge-info"><?= $year['semester_count'] ?? 0 ?> Semester</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/academic/semesters?year_id=' . $year['id']) ?>" class="btn-enterprise btn-secondary btn-sm" title="Kelola Semester">
                                        <span>📚</span>
                                    </a>
                                    <a href="<?= base_url('/academic/years/edit/' . $year['id']) ?>" class="btn-enterprise btn-secondary btn-sm" title="Edit">
                                        <span>✏️</span>
                                    </a>
                                    <button onclick="deleteYear(<?= $year['id'] ?>, '<?= esc($year['name']) ?>')" class="btn-enterprise btn-ghost btn-sm" style="color: var(--enterprise-error-600);" title="Hapus">
                                        <span>🗑️</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
function deleteYear(id, name) {
    if (confirm(`Yakin ingin menghapus tahun ajaran ${name}?\n\nSemester di tahun ajaran ini juga akan terhapus!`)) {
        window.location.href = `<?= base_url('/academic/years/delete/') ?>${id}`;
    }
}
</script>
<?= $this->endSection() ?>
