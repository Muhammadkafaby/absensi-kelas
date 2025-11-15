<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <div class="card-header">
        <h2 class="gradient-text">📅 Tahun Ajaran</h2>
        <a href="<?= base_url('/academic/years/create') ?>" class="btn-primary ripple">+ Tambah Tahun Ajaran</a>
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
                        <td colspan="7" class="text-center" style="padding: 2rem;">Belum ada data tahun ajaran</td>
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
                                    <span class="badge badge-success pulse-green">Aktif</span>
                                <?php else: ?>
                                    <a href="<?= base_url('/academic/years/set-active/' . $year['id']) ?>"
                                       class="badge badge-info micro-interact"
                                       style="cursor: pointer;"
                                       onclick="return confirm('Aktifkan tahun ajaran <?= esc($year['name']) ?>?')">
                                        Aktifkan
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('/academic/semesters?year_id=' . $year['id']) ?>"
                                       class="btn-info btn-sm">
                                        Semester
                                    </a>
                                    <a href="<?= base_url('/academic/years/edit/' . $year['id']) ?>"
                                       class="btn-secondary btn-sm">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/academic/years/delete/' . $year['id']) ?>"
                                       class="btn-danger btn-sm"
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
