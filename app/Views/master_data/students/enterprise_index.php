<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                👥
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Data Siswa</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola informasi siswa dan status keaktifan</p>
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="<?= base_url('/master/students/import') ?>" class="btn-enterprise btn-secondary">
                <span>📥</span>
                <span>Import Excel</span>
            </a>
            <a href="<?= base_url('/master/students/create') ?>" class="btn-enterprise btn-primary">
                <span>➕</span>
                <span>Tambah Siswa</span>
            </a>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">👥</div>
        <div class="metric-label">Total Siswa</div>
        <div class="metric-value"><?= count($students ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">✅</div>
        <div class="metric-label">Siswa Aktif</div>
        <div class="metric-value"><?= count(array_filter($students ?? [], fn($s) => $s['status'] === 'active')) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">❌</div>
        <div class="metric-label">Tidak Aktif</div>
        <div class="metric-value"><?= count(array_filter($students ?? [], fn($s) => $s['status'] !== 'active')) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">🏫</div>
        <div class="metric-label">Total Kelas</div>
        <div class="metric-value"><?= count(array_unique(array_column($students ?? [], 'class_id'))) ?></div>
    </div>
</div>

<!-- Filter -->
<div class="enterprise-card mb-6">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div class="enterprise-form-group" style="flex: 1;">
            <label class="enterprise-label">Filter Kelas</label>
            <select name="class_id" class="enterprise-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php if (!empty($classes)): ?>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= ($filter_class_id ?? '') == $class['id'] ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="enterprise-form-group" style="flex: 1;">
            <label class="enterprise-label">Status</label>
            <select name="status" class="enterprise-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active" <?= ($filter_status ?? '') == 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="inactive" <?= ($filter_status ?? '') == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
            </select>
        </div>

        <?php if (!empty($_GET['class_id']) || !empty($_GET['status'])): ?>
            <a href="<?= base_url('/master/students') ?>" class="btn-enterprise btn-ghost">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Daftar Siswa</h2>
        <span class="enterprise-badge badge-neutral"><?= count($students ?? []) ?> Siswa</span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" id="searchInput" class="table-search-input" placeholder="Cari NIS atau nama siswa...">
            </div>
            <div class="table-actions">
                <button class="btn-enterprise btn-secondary btn-sm" onclick="exportExcel()">
                    <span>📥</span>
                    <span>Export</span>
                </button>
            </div>
        </div>

        <table class="enterprise-table" id="studentsTable">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 120px;">NIS</th>
                    <th>Nama Siswa</th>
                    <th style="width: 150px;">Kelas</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">👥</div>
                                <h3 class="empty-state-title">Belum Ada Data Siswa</h3>
                                <p class="empty-state-message">Tambahkan siswa baru atau import dari Excel</p>
                                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1rem;">
                                    <a href="<?= base_url('/master/students/create') ?>" class="btn-enterprise btn-primary">
                                        <span>➕</span>
                                        <span>Tambah Siswa</span>
                                    </a>
                                    <a href="<?= base_url('/master/students/import') ?>" class="btn-enterprise btn-secondary">
                                        <span>📥</span>
                                        <span>Import Excel</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $index => $student): ?>
                        <tr data-search="<?= strtolower(esc($student['nis'] . ' ' . $student['name'])) ?>">
                            <td><strong><?= $index + 1 ?></strong></td>
                            <td><code style="font-weight: 600;"><?= esc($student['nis']) ?></code></td>
                            <td>
                                <div style="font-weight: 600; color: var(--enterprise-text-primary);"><?= esc($student['name']) ?></div>
                            </td>
                            <td>
                                <span class="enterprise-badge badge-info"><?= esc($student['class_name'] ?? '-') ?></span>
                            </td>
                            <td>
                                <?php if ($student['status'] === 'active'): ?>
                                    <span class="enterprise-badge badge-success">
                                        <span class="status-dot success"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="enterprise-badge badge-neutral">
                                        Tidak Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/master/students/edit/' . $student['id']) ?>" class="btn-enterprise btn-secondary btn-sm">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </a>
                                    <button onclick="deleteStudent(<?= $student['id'] ?>, '<?= esc($student['name']) ?>')" class="btn-enterprise btn-ghost btn-sm" style="color: var(--enterprise-error-600);">
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
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr[data-search]');

    rows.forEach(row => {
        const text = row.getAttribute('data-search');
        row.style.display = text.includes(query) ? '' : 'none';
    });
});

function deleteStudent(id, name) {
    if (confirm(`Yakin ingin menghapus siswa ${name}?\n\nData kehadiran siswa ini juga akan terhapus!`)) {
        window.location.href = `<?= base_url('/master/students/delete/') ?>${id}`;
    }
}

function exportExcel() {
    window.location.href = '<?= base_url('/master/students/export') ?>';
}
</script>
<?= $this->endSection() ?>
