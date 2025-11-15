<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <div class="card-header">
        <h2 class="gradient-text">📖 Data Mata Pelajaran</h2>
        <a href="<?= base_url('/master/subjects/create') ?>" class="btn-primary ripple">+ Tambah Mapel</a>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari nama / kode mapel...">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Guru Pengampu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($subjects)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">Belum ada data mata pelajaran</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($subjects as $index => $subject): ?>
                        <tr data-search="<?= strtolower(esc($subject['name'] . ' ' . $subject['code'])) ?>">
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($subject['code']) ?></strong></td>
                            <td><?= esc($subject['name']) ?></td>
                            <td><?= esc($subject['teacher_name'] ?? 'Belum ditentukan') ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/master/subjects/edit/' . $subject['id']) ?>"
                                       class="btn-secondary btn-sm">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/master/subjects/delete/' . $subject['id']) ?>"
                                       class="btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus mapel <?= esc($subject['name']) ?>?')">
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
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(row => {
        const searchData = row.getAttribute('data-search');
        if (searchData && searchData.includes(searchTerm)) {
            row.style.display = '';
        } else if (searchData) {
            row.style.display = 'none';
        }
    });
});
</script>
<?= $this->endSection() ?>
