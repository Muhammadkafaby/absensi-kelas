<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            📊
        </div>
        <div style="flex: 1;">
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Rekap Absensi</h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">Laporan dan rekap kehadiran siswa secara keseluruhan</p>
        </div>
        <button onclick="exportExcel()" class="btn-enterprise btn-primary">
            <span>📥</span>
            <span>Export Excel</span>
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
            <label class="enterprise-label">Periode</label>
            <select name="period" class="enterprise-select" onchange="this.form.submit()">
                <option value="today" <?= ($period ?? 'today') == 'today' ? 'selected' : '' ?>>Hari Ini</option>
                <option value="week" <?= ($period ?? '') == 'week' ? 'selected' : '' ?>>Minggu Ini</option>
                <option value="month" <?= ($period ?? '') == 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                <option value="custom" <?= ($period ?? '') == 'custom' ? 'selected' : '' ?>>Custom</option>
            </select>
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Dari Tanggal</label>
            <input type="date" name="start_date" value="<?= $start_date ?? date('Y-m-d') ?>" class="enterprise-input">
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Sampai Tanggal</label>
            <input type="date" name="end_date" value="<?= $end_date ?? date('Y-m-d') ?>" class="enterprise-input">
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Kelas</label>
            <select name="class_id" class="enterprise-select">
                <option value="">Semua Kelas</option>
                <?php if (!empty($classes)): ?>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= ($class_id ?? '') == $class['id'] ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
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
        <div class="metric-icon">✓</div>
        <div class="metric-label">Total Hadir</div>
        <div class="metric-value"><?= $summary['H'] ?? 0 ?></div>
        <div class="metric-trend positive">
            <span><?= number_format(($summary['H'] ?? 0) / max(($summary['total'] ?? 1), 1) * 100, 1) ?>%</span>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📋</div>
        <div class="metric-label">Izin</div>
        <div class="metric-value"><?= $summary['I'] ?? 0 ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">🏥</div>
        <div class="metric-label">Sakit</div>
        <div class="metric-value"><?= $summary['S'] ?? 0 ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">❌</div>
        <div class="metric-label">Alpa</div>
        <div class="metric-value"><?= $summary['A'] ?? 0 ?></div>
    </div>
</div>

<!-- Data Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Detail Rekap Absensi</h2>
        <span class="enterprise-badge badge-neutral">
            <?= count($records ?? []) ?> Records
        </span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" class="table-search-input" placeholder="Cari siswa, kelas, atau guru...">
            </div>
        </div>

        <table class="enterprise-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Guru</th>
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
                            <td><strong><?= esc($record['nis']) ?></strong></td>
                            <td><?= esc($record['student_name']) ?></td>
                            <td><span class="enterprise-badge badge-info"><?= esc($record['class_name']) ?></span></td>
                            <td><?= esc($record['subject_name']) ?></td>
                            <td><?= esc($record['teacher_name']) ?></td>
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
                        <td colspan="9" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📊</div>
                                <h3 class="empty-state-title">Tidak Ada Data</h3>
                                <p class="empty-state-message">Belum ada data absensi untuk periode ini</p>
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
function exportExcel() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '<?= base_url('/recap/export-excel') ?>';

    // Copy all current filter params
    const params = new URLSearchParams(window.location.search);
    params.forEach((value, key) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);

    if (window.Toast) Toast.success('Mengunduh file Excel...');
}
</script>
<?= $this->endSection() ?>
