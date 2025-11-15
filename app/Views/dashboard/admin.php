<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="dashboard-cards">
    <div class="dashboard-card">
        <h3>Total Kelas</h3>
        <p><?= $total_classes ?> Kelas</p>
    </div>
    <div class="dashboard-card">
        <h3>Total Siswa Aktif</h3>
        <p><?= $total_students ?> Siswa</p>
    </div>
    <div class="dashboard-card">
        <h3>Total Guru</h3>
        <p><?= $total_teachers ?> Guru</p>
    </div>
    <div class="dashboard-card">
        <h3>Mata Pelajaran</h3>
        <p><?= $total_subjects ?> Mapel</p>
    </div>
</div>

<?php if (!empty($alpa_today)): ?>
    <div class="card">
        <h2>Siswa Alpa Hari Ini (<?= date('d M Y') ?>)</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Jam</th>
                        <th>Guru</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alpa_today as $index => $record): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($record['nis']) ?></td>
                            <td><?= esc($record['student_name']) ?></td>
                            <td><?= esc($record['class_name']) ?></td>
                            <td><?= esc($record['subject_name']) ?></td>
                            <td><?= esc($record['lesson_hour'] ?? '-') ?></td>
                            <td><?= esc($record['teacher_name']) ?></td>
                            <td><?= esc($record['note'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <h2>Siswa Alpa Hari Ini</h2>
        <p style="text-align: center; padding: 2rem; color: var(--text-secondary);">Tidak ada siswa yang alpa hari ini.</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
