<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>
            Semester
            <?php if ($selectedYear): ?>
                - <?= esc($selectedYear['name']) ?>
            <?php endif; ?>
        </h2>
        <div style="display: flex; gap: 0.5rem;">
            <?php if ($selectedYear): ?>
                <a href="<?= base_url('/academic/semesters/create?year_id=' . $selectedYear['id']) ?>" class="btn-primary">
                    + Tambah Semester
                </a>
            <?php endif; ?>
            <a href="<?= base_url('/academic/years') ?>" class="btn-secondary">
                ← Tahun Ajaran
            </a>
        </div>
    </div>

    <?php if (!$selectedYear): ?>
        <div class="form-group" style="max-width: 400px; margin-bottom: 1.5rem;">
            <label>Filter Tahun Ajaran</label>
            <select id="yearFilter" onchange="window.location.href = '/academic/semesters?year_id=' + this.value">
                <option value="">Pilih Tahun Ajaran</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?= $year['id'] ?>">
                        <?= esc($year['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <?php if (!$selectedYear): ?>
                        <th>Tahun Ajaran</th>
                    <?php endif; ?>
                    <th>Semester</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($semesters)): ?>
                    <tr>
                        <td colspan="<?= $selectedYear ? 6 : 7 ?>" style="text-align: center; padding: 2rem;">
                            <?php if ($selectedYear): ?>
                                Belum ada semester untuk tahun ajaran ini
                            <?php else: ?>
                                Pilih tahun ajaran untuk melihat semester
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($semesters as $index => $semester): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <?php if (!$selectedYear): ?>
                                <td><?= esc($semester['academic_year_name']) ?></td>
                            <?php endif; ?>
                            <td><strong><?= esc($semester['name']) ?></strong></td>
                            <td><?= date('d M Y', strtotime($semester['start_date'])) ?></td>
                            <td><?= date('d M Y', strtotime($semester['end_date'])) ?></td>
                            <td>
                                <?php if ($semester['is_active']): ?>
                                    <span style="padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;
                                                 background: #10b981; color: white;">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <a href="<?= base_url('/academic/semesters/set-active/' . $semester['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.25rem 0.75rem; font-size: 0.75rem;"
                                       onclick="return confirm('Aktifkan semester <?= esc($semester['name']) ?>?')">
                                        Aktifkan
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/academic/semesters/edit/' . $semester['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('/academic/semesters/delete/' . $semester['id']) ?>"
                                       class="btn-secondary"
                                       style="padding: 0.5rem 1rem; font-size: 0.875rem; background: #ef4444; border-color: #ef4444; color: white;"
                                       onclick="return confirm('Yakin ingin menghapus semester <?= esc($semester['name']) ?>?')">
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
