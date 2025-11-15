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

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Pie Chart: Status Kehadiran -->
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Distribusi Kehadiran (30 Hari Terakhir)</h3>
        <canvas id="statusPieChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Line Chart: Trend Kehadiran -->
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Trend Kehadiran (7 Hari Terakhir)</h3>
        <canvas id="trendLineChart" style="max-height: 300px;"></canvas>
    </div>
</div>

<!-- Bar Chart: Kehadiran per Kelas -->
<?php if (!empty($chart_class_data)): ?>
<div class="card">
    <h3 style="margin-bottom: 1rem;">Persentase Kehadiran per Kelas (30 Hari Terakhir)</h3>
    <canvas id="classBarChart" style="max-height: 400px;"></canvas>
</div>
<?php endif; ?>

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
        <p style="text-align: center; padding: 2rem; color: var(--text-secondary);">Tidak ada siswa yang alpa hari ini. 🎉</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Chart colors
const colors = {
    H: '#10b981',
    I: '#06b6d4',
    S: '#f59e0b',
    A: '#ef4444',
    T: '#8b5cf6'
};

// 1. Pie Chart
const statusData = <?= json_encode($chart_status_data) ?>;
new Chart(document.getElementById('statusPieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Terlambat'],
        datasets: [{
            data: [statusData.H, statusData.I, statusData.S, statusData.A, statusData.T],
            backgroundColor: [colors.H, colors.I, colors.S, colors.A, colors.T],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15 } },
            tooltip: {
                callbacks: {
                    label: function(ctx) {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = ((ctx.parsed / total) * 100).toFixed(1);
                        return ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                    }
                }
            }
        }
    }
});

// 2. Line Chart
const trendData = <?= json_encode($chart_trend_data) ?>;
new Chart(document.getElementById('trendLineChart'), {
    type: 'line',
    data: {
        labels: trendData.map(d => new Date(d.date).toLocaleDateString('id-ID', {day:'2-digit', month:'short'})),
        datasets: [{
            label: '% Kehadiran',
            data: trendData.map(d => d.total > 0 ? ((d.hadir / d.total) * 100).toFixed(1) : 0),
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
        }
    }
});

// 3. Bar Chart
<?php if (!empty($chart_class_data)): ?>
const classData = <?= json_encode($chart_class_data) ?>;
new Chart(document.getElementById('classBarChart'), {
    type: 'bar',
    data: {
        labels: classData.map(d => d.class_name),
        datasets: [{
            label: '% Kehadiran',
            data: classData.map(d => d.total > 0 ? ((d.hadir / d.total) * 100).toFixed(1) : 0),
            backgroundColor: function(ctx) {
                const v = ctx.parsed.y;
                return v >= 75 ? colors.H : v >= 50 ? colors.S : colors.A;
            }
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } }
        }
    }
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
