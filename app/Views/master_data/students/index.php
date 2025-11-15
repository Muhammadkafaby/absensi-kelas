<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Data Siswa</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= base_url('/master/students/import') ?>" class="btn-secondary">📥 Import Excel</a>
            <a href="<?= base_url('/master/students/create') ?>" class="btn-primary">+ Tambah Siswa</a>
        </div>
    </div>

    <div class="form-row" style="margin-bottom: 1rem;">
        <div class="form-group">
            <input type="text" id="searchInput" placeholder="Cari nama / NIS / NISN...">
        </div>
        <div class="form-group">
            <select id="filterClass">
                <option value="">Semua Kelas</option>
                <?php
                $classModel = new \App\Models\ClassModel();
                $allClasses = $classModel->orderBy('level', 'ASC')->orderBy('name', 'ASC')->findAll();
                foreach ($allClasses as $c):
                ?>
                    <option value="<?= strtolower($c['name']) ?>"><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <select id="filterStatus">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>JK</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem;">Belum ada data siswa</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $index => $student): ?>
                        <tr data-search="<?= strtolower(esc($student['nis'] . ' ' . $student['nisn'] . ' ' . $student['name'])) ?>"
                            data-class="<?= strtolower(esc($student['class_name'])) ?>"
                            data-status="<?= $student['status'] ?>">
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($student['nis']) ?></td>
                            <td><?= esc($student['nisn'] ?? '-') ?></td>
                            <td><strong><?= esc($student['name']) ?></strong></td>
                            <td><?= esc($student['class_name']) ?></td>
                            <td><?= $student['gender'] ?></td>
                            <td>
                                <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;
                                             background: <?= $student['status'] == 'active' ? '#10b981' : '#ef4444' ?>; color: white;">
                                    <?= $student['status'] == 'active' ? 'Aktif' : 'Non-Aktif' ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/master/students/edit/' . $student['id']) ?>"
                                       class="btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/master/students/delete/' . $student['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem; background: #ef4444; border-color: #ef4444; color: white;"
                                       onclick="return confirm('Yakin ingin menghapus siswa <?= esc($student['name']) ?>?')">
                                        Hapus
                                    </a>
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
// Filter dan search functionality
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filterClass = document.getElementById('filterClass').value;
    const filterStatus = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(row => {
        const searchData = row.getAttribute('data-search');
        const classData = row.getAttribute('data-class');
        const statusData = row.getAttribute('data-status');

        let showRow = true;

        // Search filter
        if (searchData && searchTerm && !searchData.includes(searchTerm)) {
            showRow = false;
        }

        // Class filter
        if (classData && filterClass && classData !== filterClass) {
            showRow = false;
        }

        // Status filter
        if (statusData && filterStatus && statusData !== filterStatus) {
            showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
    });
}

document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('filterClass').addEventListener('change', filterTable);
document.getElementById('filterStatus').addEventListener('change', filterTable);
</script>
<?= $this->endSection() ?>
