<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                📚
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Semester</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola semester akademik</p>
            </div>
        </div>
        <a href="<?= base_url('/academic/semesters/create') ?>" class="btn-enterprise btn-primary">
            <span>➕</span>
            <span>Tambah Semester</span>
        </a>
    </div>
</div>

<!-- Filter -->
<div class="enterprise-card mb-6">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="enterprise-form-group" style="flex: 1;">
            <label class="enterprise-label">Tahun Ajaran</label>
            <select name="year_id" class="enterprise-select" onchange="this.form.submit()">
                <option value="">Semua Tahun Ajaran</option>
                <?php if (!empty($years)): ?>
                    <?php foreach ($years as $year): ?>
                        <option value="<?= $year['id'] ?>" <?= ($filter_year_id ?? '') == $year['id'] ? 'selected' : '' ?>>
                            <?= esc($year['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <?php if (!empty($_GET['year_id'])): ?>
            <a href="<?= base_url('/academic/semesters') ?>" class="btn-enterprise btn-ghost">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📚</div>
        <div class="metric-label">Total Semester</div>
        <div class="metric-value"><?= count($semesters ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">✅</div>
        <div class="metric-label">Semester Aktif</div>
        <div class="metric-value"><?= count(array_filter($semesters ?? [], fn($s) => $s['is_active'] ?? false)) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📅</div>
        <div class="metric-label">Tahun Ajaran</div>
        <div class="metric-value"><?= count(array_unique(array_column($semesters ?? [], 'year_id'))) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">🎓</div>
        <div class="metric-label">Semester Berjalan</div>
        <div class="metric-value" style="font-size: 1.5rem;"><?= date('n') <= 6 ? '2' : '1' ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Daftar Semester</h2>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <table class="enterprise-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tahun Ajaran</th>
                    <th style="width: 150px;">Semester</th>
                    <th style="width: 150px;">Tanggal Mulai</th>
                    <th style="width: 150px;">Tanggal Selesai</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($semesters)): ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📚</div>
                                <h3 class="empty-state-title">Belum Ada Semester</h3>
                                <p class="empty-state-message">Tambahkan semester baru</p>
                                <a href="<?= base_url('/academic/semesters/create') ?>" class="btn-enterprise btn-primary" style="margin-top: 1rem;">
                                    <span>➕</span>
                                    <span>Tambah Semester</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($semesters as $index => $semester): ?>
                        <tr>
                            <td><strong><?= $index + 1 ?></strong></td>
                            <td>
                                <div style="font-weight: 600; color: var(--enterprise-text-primary);">
                                    <?= esc($semester['year_name'] ?? '-') ?>
                                </div>
                            </td>
                            <td>
                                <span class="enterprise-badge badge-info">
                                    Semester <?= $semester['semester'] ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($semester['start_date'])) ?></td>
                            <td><?= date('d M Y', strtotime($semester['end_date'])) ?></td>
                            <td>
                                <?php if ($semester['is_active']): ?>
                                    <span class="enterprise-badge badge-success">
                                        <span class="status-dot success"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="enterprise-badge badge-neutral">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/academic/semesters/edit/' . $semester['id']) ?>" class="btn-enterprise btn-secondary btn-sm">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </a>
                                    <button onclick="deleteSemester(<?= $semester['id'] ?>, '<?= esc($semester['year_name']) ?> - Semester <?= $semester['semester'] ?>')" class="btn-enterprise btn-ghost btn-sm" style="color: var(--enterprise-error-600);">
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
function deleteSemester(id, name) {
    if (confirm(`Yakin ingin menghapus ${name}?`)) {
        window.location.href = `<?= base_url('/academic/semesters/delete/') ?>${id}`;
    }
}
</script>
<?= $this->endSection() ?>
