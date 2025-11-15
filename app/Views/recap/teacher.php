<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2>Filter Rekap</h2>
    <form action="<?= base_url('/recap/teacher') ?>" method="GET">
        <div class="form-row">
            <div class="form-group">
                <label for="class_id">Kelas</label>
                <select id="class_id" name="class_id">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= (isset($filters['class_id']) && $filters['class_id'] == $class['id']) ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subject_id">Mata Pelajaran</label>
                <select id="subject_id" name="subject_id">
                    <option value="">Semua Mapel Saya</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>" <?= (isset($filters['subject_id']) && $filters['subject_id'] == $subject['id']) ? 'selected' : '' ?>>
                            <?= esc($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date_from">Dari Tanggal</label>
                <input type="date" id="date_from" name="date_from" value="<?= $filters['date_from'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label for="date_to">Sampai Tanggal</label>
                <input type="date" id="date_to" name="date_to" value="<?= $filters['date_to'] ?? '' ?>">
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary">Tampilkan Rekap</button>
            <a href="<?= base_url('/recap/teacher') ?>" class="btn-secondary">Reset</a>
        </div>
    </form>
</div>

<?php if (!empty($recap_data)): ?>
    <div class="card">
        <h2>Hasil Rekap Siswa</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>H</th>
                        <th>I</th>
                        <th>S</th>
                        <th>A</th>
                        <th>T</th>
                        <th>Total</th>
                        <th>%Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recap_data as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($row['nis']) ?></td>
                            <td><?= esc($row['student_name']) ?></td>
                            <td><?= esc($row['class_name']) ?></td>
                            <td style="background: rgba(16, 185, 129, 0.1);"><?= $row['hadir'] ?></td>
                            <td style="background: rgba(6, 182, 212, 0.1);"><?= $row['izin'] ?></td>
                            <td style="background: rgba(245, 158, 11, 0.1);"><?= $row['sakit'] ?></td>
                            <td style="background: rgba(239, 68, 68, 0.1);"><?= $row['alpa'] ?></td>
                            <td><?= $row['terlambat'] ?></td>
                            <td><strong><?= $row['total_pertemuan'] ?></strong></td>
                            <td>
                                <?php
                                $persen = ($row['total_pertemuan'] > 0) ? round(($row['hadir'] / $row['total_pertemuan']) * 100, 1) : 0;
                                $color = $persen >= 75 ? '#10b981' : ($persen >= 50 ? '#f59e0b' : '#ef4444');
                                ?>
                                <strong style="color: <?= $color ?>"><?= $persen ?>%</strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($sessions)): ?>
    <div class="card">
        <h2>Riwayat Sesi Absensi</h2>
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
                    <?php foreach ($sessions as $index => $session): ?>
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
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
