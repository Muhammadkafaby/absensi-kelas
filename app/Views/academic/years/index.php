<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Tahun Ajaran</h2>
        <a href="<?= base_url('/academic/years/create') ?>" class="btn-primary">+ Tambah Tahun Ajaran</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun Ajaran</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Jumlah Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($years)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">Belum ada data tahun ajaran</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($years as $index => $year): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($year['name']) ?></strong></td>
                            <td><?= date('d M Y', strtotime($year['start_date'])) ?></td>
                            <td><?= date('d M Y', strtotime($year['end_date'])) ?></td>
                            <td><?= $year['semester_count'] ?? 0 ?> Semester</td>
                            <td>
                                <?php if ($year['is_active']): ?>
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;
                                                 background: #10b981; color: white;">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <a href="<?= base_url('/academic/years/set-active/' . $year['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.25rem 0.75rem; font-size: 0.75rem;"
                                       onclick="return confirm('Aktifkan tahun ajaran <?= esc($year['name']) ?>?')">
                                        Aktifkan
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/academic/semesters?year_id=' . $year['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem; background: #3b82f6; border-color: #3b82f6; color: white;">
                                        Semester
                                    </a>
                                    <a href="<?= base_url('/academic/years/edit/' . $year['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/academic/years/delete/' . $year['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem; background: #ef4444; border-color: #ef4444; color: white;"
                                       onclick="return confirm('Yakin ingin menghapus tahun ajaran <?= esc($year['name']) ?>?')">
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
