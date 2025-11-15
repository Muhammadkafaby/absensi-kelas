<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                📖
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Mata Pelajaran</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola mata pelajaran dan guru pengampu</p>
            </div>
        </div>
        <a href="<?= base_url('/master/subjects/create') ?>" class="btn-enterprise btn-primary">
            <span>➕</span>
            <span>Tambah Mapel</span>
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📖</div>
        <div class="metric-label">Total Mapel</div>
        <div class="metric-value"><?= count($subjects ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">👨‍🏫</div>
        <div class="metric-label">Guru Pengampu</div>
        <div class="metric-value"><?= count(array_unique(array_filter(array_column($subjects ?? [], 'teacher_id')))) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📊</div>
        <div class="metric-label">Mapel Aktif</div>
        <div class="metric-value"><?= count($subjects ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📚</div>
        <div class="metric-label">Kategori</div>
        <div class="metric-value"><?= count(array_unique(array_filter(array_column($subjects ?? [], 'code')))) ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Daftar Mata Pelajaran</h2>
        <span class="enterprise-badge badge-neutral"><?= count($subjects ?? []) ?> Mapel</span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" id="searchInput" class="table-search-input" placeholder="Cari kode atau nama mapel...">
            </div>
        </div>

        <table class="enterprise-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 120px;">Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Guru Pengampu</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📖</div>
                                <h3 class="empty-state-title">Belum Ada Data Mapel</h3>
                                <p class="empty-state-message">Tambahkan mata pelajaran baru</p>
                                <a href="<?= base_url('/master/subjects/create') ?>" class="btn-enterprise btn-primary" style="margin-top: 1rem;">
                                    <span>➕</span>
                                    <span>Tambah Mapel</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $index => $subject): ?>
                        <tr data-search="<?= strtolower(esc($subject['code'] . ' ' . $subject['name'])) ?>">
                            <td><strong><?= $index + 1 ?></strong></td>
                            <td>
                                <code style="font-weight: 600; color: var(--enterprise-primary-600);"><?= esc($subject['code']) ?></code>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--enterprise-text-primary);"><?= esc($subject['name']) ?></div>
                            </td>
                            <td>
                                <?php if (!empty($subject['teacher_name'])): ?>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="font-size: 1.25rem;">👨‍🏫</span>
                                        <span><?= esc($subject['teacher_name']) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--enterprise-text-tertiary);">Belum ditugaskan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/master/subjects/edit/' . $subject['id']) ?>" class="btn-enterprise btn-secondary btn-sm">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </a>
                                    <button onclick="deleteSubject(<?= $subject['id'] ?>, '<?= esc($subject['name']) ?>')" class="btn-enterprise btn-ghost btn-sm" style="color: var(--enterprise-error-600);">
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

function deleteSubject(id, name) {
    if (confirm(`Yakin ingin menghapus mata pelajaran ${name}?`)) {
        window.location.href = `<?= base_url('/master/subjects/delete/') ?>${id}`;
    }
}
</script>
<?= $this->endSection() ?>
