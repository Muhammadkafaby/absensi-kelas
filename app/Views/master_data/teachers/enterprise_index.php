<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                👨‍🏫
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Data Guru</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola informasi guru dan tenaga pengajar</p>
            </div>
        </div>
        <a href="<?= base_url('/master/teachers/create') ?>" class="btn-enterprise btn-primary">
            <span>➕</span>
            <span>Tambah Guru</span>
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">👨‍🏫</div>
        <div class="metric-label">Total Guru</div>
        <div class="metric-value"><?= count($teachers ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📚</div>
        <div class="metric-label">Mata Pelajaran</div>
        <div class="metric-value"><?= count(array_unique(array_filter(array_column($teachers ?? [], 'subjects')))) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">✅</div>
        <div class="metric-label">Guru Aktif</div>
        <div class="metric-value"><?= count($teachers ?? []) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📊</div>
        <div class="metric-label">Rata-rata Mapel</div>
        <div class="metric-value"><?= count($teachers) > 0 ? round(count(array_filter(array_column($teachers ?? [], 'subjects'))) / count($teachers), 1) : 0 ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Daftar Guru</h2>
        <span class="enterprise-badge badge-neutral"><?= count($teachers ?? []) ?> Guru</span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" id="searchInput" class="table-search-input" placeholder="Cari NIP atau nama guru...">
            </div>
        </div>

        <table class="enterprise-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 150px;">NIP</th>
                    <th>Nama Guru</th>
                    <th>Mata Pelajaran</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">👨‍🏫</div>
                                <h3 class="empty-state-title">Belum Ada Data Guru</h3>
                                <p class="empty-state-message">Tambahkan guru baru untuk memulai</p>
                                <a href="<?= base_url('/master/teachers/create') ?>" class="btn-enterprise btn-primary" style="margin-top: 1rem;">
                                    <span>➕</span>
                                    <span>Tambah Guru</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teachers as $index => $teacher): ?>
                        <tr data-search="<?= strtolower(esc(($teacher['nip'] ?? '') . ' ' . $teacher['name'])) ?>">
                            <td><strong><?= $index + 1 ?></strong></td>
                            <td><code style="font-weight: 600;"><?= esc($teacher['nip'] ?? '-') ?></code></td>
                            <td>
                                <div style="font-weight: 600; color: var(--enterprise-text-primary);"><?= esc($teacher['name']) ?></div>
                            </td>
                            <td>
                                <?php if (!empty($teacher['subjects'])): ?>
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                        <?php
                                        $subjects = is_array($teacher['subjects']) ? $teacher['subjects'] : explode(',', $teacher['subjects']);
                                        foreach (array_slice($subjects, 0, 3) as $subject):
                                        ?>
                                            <span class="enterprise-badge badge-info" style="font-size: 0.75rem;"><?= esc(trim($subject)) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($subjects) > 3): ?>
                                            <span class="enterprise-badge badge-neutral" style="font-size: 0.75rem;">+<?= count($subjects) - 3 ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--enterprise-text-tertiary);">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/master/teachers/edit/' . $teacher['id']) ?>" class="btn-enterprise btn-secondary btn-sm">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </a>
                                    <button onclick="deleteTeacher(<?= $teacher['id'] ?>, '<?= esc($teacher['name']) ?>')" class="btn-enterprise btn-ghost btn-sm" style="color: var(--enterprise-error-600);">
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

function deleteTeacher(id, name) {
    if (confirm(`Yakin ingin menghapus guru ${name}?`)) {
        window.location.href = `<?= base_url('/master/teachers/delete/') ?>${id}`;
    }
}
</script>
<?= $this->endSection() ?>
