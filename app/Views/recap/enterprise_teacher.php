<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                📊
            </div>
            <div>
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Rekap Absensi Saya</h1>
                <p style="color: var(--enterprise-text-secondary); margin: 0;">Laporan absensi kelas yang Anda ampu</p>
            </div>
        </div>
        <button onclick="exportPDF()" class="btn-enterprise btn-primary">
            <span>📥</span>
            <span>Export PDF</span>
        </button>
    </div>
</div>

<!-- Filter Section -->
<div class="enterprise-card mb-6">
    <div class="card-header">
        <h2 class="card-title">Filter Laporan</h2>
    </div>

    <form method="GET" class="form-row">
        <div class="enterprise-form-group">
            <label class="enterprise-label">Mata Pelajaran</label>
            <select name="subject_id" class="enterprise-select" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>" <?= ($filter_subject_id ?? '') == $subject['id'] ? 'selected' : '' ?>>
                            <?= esc($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Kelas</label>
            <select name="class_id" class="enterprise-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php if (!empty($classes)): ?>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= ($filter_class_id ?? '') == $class['id'] ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Dari Tanggal</label>
            <input type="date" name="start_date" value="<?= $start_date ?? date('Y-m-01') ?>" class="enterprise-input">
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Sampai Tanggal</label>
            <input type="date" name="end_date" value="<?= $end_date ?? date('Y-m-d') ?>" class="enterprise-input">
        </div>

        <div class="enterprise-form-group" style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn-enterprise btn-primary" style="width: 100%;">
                <span>🔍</span>
                <span>Filter</span>
            </button>
        </div>
    </form>
</div>

<!-- Summary Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📝</div>
        <div class="metric-label">Total Sesi</div>
        <div class="metric-value"><?= count(array_unique(array_column($records ?? [], 'session_id'))) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">✓</div>
        <div class="metric-label">Hadir</div>
        <div class="metric-value"><?= count(array_filter($records ?? [], fn($r) => $r['status'] === 'H')) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📋</div>
        <div class="metric-label">Izin/Sakit</div>
        <div class="metric-value"><?= count(array_filter($records ?? [], fn($r) => in_array($r['status'], ['I', 'S']))) ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">❌</div>
        <div class="metric-label">Alpa</div>
        <div class="metric-value"><?= count(array_filter($records ?? [], fn($r) => $r['status'] === 'A')) ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Detail Rekap Absensi</h2>
        <span class="enterprise-badge badge-neutral"><?= count($records ?? []) ?> Records</span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" class="table-search-input" placeholder="Cari siswa atau kelas...">
            </div>
        </div>

        <table class="enterprise-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($records)): ?>
                    <?php foreach ($records as $index => $record): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= date('d M Y', strtotime($record['date'])) ?></td>
                            <td><span class="enterprise-badge badge-info"><?= esc($record['class_name']) ?></span></td>
                            <td><?= esc($record['subject_name']) ?></td>
                            <td><code style="font-weight: 600;"><?= esc($record['nis']) ?></code></td>
                            <td><?= esc($record['student_name']) ?></td>
                            <td>
                                <?php
                                $statusMap = [
                                    'H' => ['✓ Hadir', 'badge-success'],
                                    'I' => ['📋 Izin', 'badge-info'],
                                    'S' => ['🏥 Sakit', 'badge-warning'],
                                    'A' => ['❌ Alpa', 'badge-error'],
                                    'T' => ['⏰ Terlambat', 'badge-warning']
                                ];
                                $status = $statusMap[$record['status']] ?? ['?', 'badge-neutral'];
                                ?>
                                <span class="enterprise-badge <?= $status[1] ?>"><?= $status[0] ?></span>
                            </td>
                            <td><?= esc($record['note'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📊</div>
                                <h3 class="empty-state-title">Belum Ada Data</h3>
                                <p class="empty-state-message">Belum ada data absensi untuk filter ini</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
function exportPDF() {
    if (window.Toast) Toast.info('Fitur export PDF akan segera tersedia');
}
</script>
<?= $this->endSection() ?>
