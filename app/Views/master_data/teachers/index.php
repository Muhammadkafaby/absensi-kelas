<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Data Guru</h2>
        <a href="<?= base_url('/master/teachers/create') ?>" class="btn-primary">+ Tambah Guru</a>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari nama / NIP guru...">
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>No. Telepon</th>
                    <th>Jumlah Mapel</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Belum ada data guru</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teachers as $index => $teacher): ?>
                        <tr data-search="<?= strtolower(esc($teacher['name'] . ' ' . ($teacher['nip'] ?? ''))) ?>">
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($teacher['name']) ?></strong></td>
                            <td><?= esc($teacher['nip'] ?? '-') ?></td>
                            <td><?= esc($teacher['phone'] ?? '-') ?></td>
                            <td><?= $teacher['subject_count'] ?? 0 ?> mapel</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/master/teachers/edit/' . $teacher['id']) ?>"
                                       class="btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/master/teachers/delete/' . $teacher['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem; background: #ef4444; border-color: #ef4444; color: white;"
                                       onclick="return confirm('Yakin ingin menghapus guru <?= esc($teacher['name']) ?>?')">
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
