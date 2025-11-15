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

    <form method="GET" id="filterForm" class="form-row">
        <div class="enterprise-form-group">
            <label class="enterprise-label">Mata Pelajaran</label>
            <select name="subject_id" class="enterprise-select" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                <?php if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>" <?= ($filters['subject_id'] ?? '') == $subject['id'] ? 'selected' : '' ?>>
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
                        <option value="<?= $class['id'] ?>" <?= ($filters['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Dari Tanggal</label>
            <input type="date" name="date_from" value="<?= $filters['date_from'] ?? date('Y-m-01') ?>" class="enterprise-input">
        </div>

        <div class="enterprise-form-group">
            <label class="enterprise-label">Sampai Tanggal</label>
            <input type="date" name="date_to" value="<?= $filters['date_to'] ?? date('Y-m-d') ?>" class="enterprise-input">
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
<?php
// Group records by session_id
$sessions = [];
foreach ($records ?? [] as $record) {
    $sessionId = $record['session_id'] ?? $record['attendance_session_id'];
    if (!isset($sessions[$sessionId])) {
        $sessions[$sessionId] = [
            'id' => $sessionId,
            'date' => $record['date'],
            'class_name' => $record['class_name'],
            'subject_name' => $record['subject_name'],
            'lesson_hour' => $record['lesson_hour'] ?? '-',
            'count' => 0
        ];
    }
    $sessions[$sessionId]['count']++;
}
?>

<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">📝</div>
        <div class="metric-label">Total Sesi</div>
        <div class="metric-value"><?= count($sessions) ?></div>
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

<!-- Sessions Table -->
<div class="enterprise-card">
    <div class="card-header">
        <h2 class="card-title">Sesi Absensi</h2>
        <span class="enterprise-badge badge-neutral"><?= count($sessions) ?> Sesi</span>
    </div>

    <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
        <div class="table-toolbar">
            <div class="table-search">
                <span class="table-search-icon">🔍</span>
                <input type="text" id="sessionSearch" class="table-search-input" placeholder="Cari sesi...">
            </div>
        </div>

        <table class="enterprise-table" id="sessionsTable">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Tanggal</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th style="width: 120px;">Jam Ke</th>
                    <th style="width: 120px;">Jumlah Siswa</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sessions)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td><strong><?= $no++ ?></strong></td>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 600;">
                                        <?= date('d M Y', strtotime($session['date'])) ?>
                                    </span>
                                    <span style="font-size: 0.75rem; color: var(--enterprise-text-tertiary);">
                                        <?= date('l', strtotime($session['date'])) ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="enterprise-badge badge-info">
                                    <?= esc($session['class_name']) ?>
                                </span>
                            </td>
                            <td><?= esc($session['subject_name']) ?></td>
                            <td>
                                <span class="enterprise-badge badge-neutral">
                                    Jam <?= esc($session['lesson_hour']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="enterprise-badge badge-success">
                                    <?= $session['count'] ?> siswa
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?= base_url('/attendance/edit/' . $session['id']) ?>"
                                       class="btn-enterprise btn-secondary btn-sm"
                                       title="Edit Absensi">
                                        <span>✏️</span>
                                        <span>Edit</span>
                                    </a>
                                    <button onclick="confirmDelete(<?= $session['id'] ?>, '<?= esc($session['date']) ?>', '<?= esc($session['class_name']) ?>', '<?= esc($session['subject_name']) ?>')"
                                            class="btn-enterprise btn-danger btn-sm"
                                            title="Hapus Absensi">
                                        <span>🗑️</span>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 3rem;">
                            <div class="empty-state" style="padding: 1rem;">
                                <div class="empty-state-icon">📊</div>
                                <h3 class="empty-state-title">Belum Ada Data</h3>
                                <p class="empty-state-message">Belum ada sesi absensi untuk filter ini</p>
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
    // Get current filter values
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);

    // Build query string
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }

    // Add teacher_id from session (will be added by controller)
    const exportUrl = '<?= base_url('/recap/teacher/export-excel') ?>' + (params.toString() ? '?' + params.toString() : '');

    // Open in new window for download
    window.location.href = exportUrl;
}

// Session search functionality
document.getElementById('sessionSearch').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#sessionsTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
});

// Confirm delete with detailed info
function confirmDelete(sessionId, date, className, subjectName) {
    const formattedDate = new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    const message = `Apakah Anda yakin ingin menghapus data absensi ini?\n\n` +
                   `📅 Tanggal: ${formattedDate}\n` +
                   `📚 Kelas: ${className}\n` +
                   `📖 Mata Pelajaran: ${subjectName}\n\n` +
                   `Data yang dihapus tidak dapat dikembalikan!`;

    if (confirm(message)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('/attendance/delete/') ?>' + sessionId;

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>
