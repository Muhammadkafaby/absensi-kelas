<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Mata Pelajaran yang Diampu</h2>
    <?php if (!empty($subjects)): ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($subjects as $subject): ?>
                <li style="padding: 0.75rem; margin-bottom: 0.5rem; background: var(--bg-secondary); border-radius: 0.5rem;">
                    <strong><?= esc($subject['name']) ?></strong> (<?= esc($subject['code']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color: var(--text-secondary);">Belum ada mata pelajaran yang diampu.</p>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Riwayat Absensi Terakhir</h2>
    <?php if (!empty($recent_sessions)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_sessions as $index => $session): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= date('d M Y', strtotime($session['date'])) ?></td>
                            <td><?= esc($session['class_name']) ?></td>
                            <td><?= esc($session['subject_name']) ?></td>
                            <td><?= esc($session['lesson_hour'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; padding: 2rem; color: var(--text-secondary);">Belum ada riwayat absensi.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
