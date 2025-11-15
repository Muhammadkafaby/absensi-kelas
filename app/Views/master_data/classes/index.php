<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <div class="card-header">
        <h2 class="gradient-text">📚 Data Kelas</h2>
        <a href="<?= base_url('/master/classes/create') ?>" class="btn-primary ripple">+ Tambah Kelas</a>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari nama kelas...">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Jumlah Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($classes)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 2rem;">Belum ada data kelas</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($classes as $index => $class): ?>
                        <tr data-name="<?= strtolower(esc($class['name'])) ?>">
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($class['name']) ?></strong></td>
                            <td><?= esc($class['level']) ?></td>
                            <td><?= esc($class['major'] ?? '-') ?></td>
                            <td><?= $class['student_count'] ?? 0 ?> siswa</td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/master/classes/edit/' . $class['id']) ?>"
                                       class="btn-secondary btn-sm">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/master/classes/delete/' . $class['id']) ?>"
                                       class="btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus kelas <?= esc($class['name']) ?>? Semua data siswa di kelas ini akan terhapus!')">
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
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        if (name && name.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
<?= $this->endSection() ?>
