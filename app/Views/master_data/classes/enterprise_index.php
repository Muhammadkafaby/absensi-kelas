<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                🏫
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Data Kelas</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola informasi kelas di sekolah</p>
            </div>
        </div>
        <a href="<?= base_url('/master/classes/create') ?>" class="btn-enterprise btn-primary">
            <span>➕</span>
            <span>Tambah Kelas</span>
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">🏫</div>
        <div class="metric-label">Total Kelas</div>
        <div class="metric-value"><?= count($classes ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">👥</div>
        <div class="metric-label">Total Siswa</div>
        <div class="metric-value"><?= array_sum(array_column($classes ?? [], 'student_count')) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📊</div>
        <div class="metric-label">Rata-rata/Kelas</div>
        <div class="metric-value"><?= count($classes) > 0 ? round(array_sum(array_column($classes, 'student_count')) / count($classes)) : 0 ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📈</div>
        <div class="metric-label">Kelas Aktif</div>
        <div class="metric-value"><?= count($classes ?? []) ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Daftar Kelas</h2>
        <span class="enterprise-badge badge-neutral"><?= count($classes ?? []) ?> Kelas</span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" id="searchInput" class="table-search-input" placeholder="Cari nama kelas...">
            </div>
            <div class="table-actions">
                <button class="btn-enterprise btn-secondary btn-sm" onclick="printTable()">
                    <span>🖨️</span>
                    <span>Print</span>
                </button>
            </div>
        </div>

        <table class="enterprise-table" id="classesTable">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kelas</th>
                    <th style="width: 100px;">Tingkat</th>
                    <th style="width: 150px;">Jurusan</th>
                    <th style="width: 130px;">Jumlah Siswa</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($classes)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">🏫</div>
                                <h3 class="empty-state-title">Belum Ada Data Kelas</h3>
                                <p class="empty-state-message">Tambahkan kelas baru untuk memulai</p>
                                <a href="<?= base_url('/master/classes/create') ?>" class="btn-enterprise btn-primary" style="margin-top: 1rem;">
                                    <span>➕</span>
                                    <span>Tambah Kelas Pertama</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($classes as $index => $class): ?>
                        <tr data-name="<?= strtolower(esc($class['name'])) ?>">
                            <td><strong><?= $index + 1 ?></strong></td>
                            <td>
                                <div style="font-weight: 600; color: var(--enterprise-text-primary);"><?= esc($class['name']) ?></div>
                            </td>
                            <td>
                                <span class="enterprise-badge badge-info">Kelas <?= esc($class['level']) ?></span>
                            </td>
                            <td><?= esc($class['major'] ?? '-') ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-weight: 600; color: var(--enterprise-primary-600);"><?= $class['student_count'] ?? 0 ?></span>
                                    <span style="color: var(--enterprise-text-tertiary); font-size: 0.875rem;">siswa</span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/master/classes/edit/' . $class['id']) ?>" class="btn-enterprise btn-secondary btn-sm" title="Edit">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </a>
                                    <button onclick="deleteClass(<?= $class['id'] ?>, '<?= esc($class['name']) ?>')" class="btn-enterprise btn-ghost btn-sm" style="color: var(--enterprise-error-600);" title="Hapus">
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
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr[data-name]');

    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        row.style.display = name.includes(query) ? '' : 'none';
    });
});

function deleteClass(id, name) {
    if (confirm(`Yakin ingin menghapus kelas ${name}?\n\nPeringatan: Semua data siswa di kelas ini akan terhapus!`)) {
        window.location.href = `<?= base_url('/master/classes/delete/') ?>${id}`;
    }
}

function printTable() {
    window.print();
}
</script>
<?= $this->endSection() ?>
