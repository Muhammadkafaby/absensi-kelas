<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Statistics Grid -->
<div class="stats-grid">
    <!-- Total Kelas Card -->
    <div class="metric-card">
        <div class="metric-icon">🏫</div>
        <div class="metric-label">Total Kelas</div>
        <div class="metric-value" data-count-up="<?= $total_classes ?>"><?= $total_classes ?></div>
        <div class="metric-trend positive">
            <span>▲</span>
            <span>Kelas Aktif</span>
        </div>
    </div>

    <!-- Total Siswa Card -->
    <div class="metric-card">
        <div class="metric-icon">👥</div>
        <div class="metric-label">Total Siswa Aktif</div>
        <div class="metric-value" data-count-up="<?= $total_students ?>"><?= $total_students ?></div>
        <div class="metric-trend positive">
            <span>▲</span>
            <span>Siswa Terdaftar</span>
        </div>
    </div>

    <!-- Total Guru Card -->
    <div class="metric-card">
        <div class="metric-icon">👨‍🏫</div>
        <div class="metric-label">Total Guru</div>
        <div class="metric-value" data-count-up="<?= $total_teachers ?>"><?= $total_teachers ?></div>
        <div class="metric-trend positive">
            <span>▲</span>
            <span>Guru Aktif</span>
        </div>
    </div>

    <!-- Total Mata Pelajaran Card -->
    <div class="metric-card">
        <div class="metric-icon">📖</div>
        <div class="metric-label">Mata Pelajaran</div>
        <div class="metric-value" data-count-up="<?= $total_subjects ?>"><?= $total_subjects ?></div>
        <div class="metric-trend positive">
            <span>▲</span>
            <span>Mapel Tersedia</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Pie Chart: Status Kehadiran -->
    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Distribusi Kehadiran</h3>
            <span class="enterprise-badge badge-info" style="font-size: 0.75rem;">30 Hari</span>
        </div>
        <div class="chart-wrapper">
            <canvas id="statusPieChart"></canvas>
        </div>
    </div>

    <!-- Line Chart: Trend Kehadiran -->
    <div class="chart-container">
        <div class="chart-header">
            <h3 class="chart-title">Trend Kehadiran</h3>
            <span class="enterprise-badge badge-success" style="font-size: 0.75rem;">7 Hari</span>
        </div>
        <div class="chart-wrapper">
            <canvas id="trendLineChart"></canvas>
        </div>
    </div>
</div>

<!-- Bar Chart: Kehadiran per Kelas -->
<?php if (!empty($chart_class_data)): ?>
<div class="chart-container mb-6">
    <div class="chart-header">
        <h3 class="chart-title">Persentase Kehadiran per Kelas</h3>
        <span class="enterprise-badge badge-neutral" style="font-size: 0.75rem;">30 Hari Terakhir</span>
    </div>
    <div class="chart-wrapper">
        <canvas id="classBarChart"></canvas>
    </div>
</div>
<?php endif; ?>

<!-- Siswa Alpa Hari Ini -->
<div class="enterprise-card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Siswa Alpa Hari Ini</h2>
            <p class="card-subtitle"><?= date('d F Y') ?></p>
        </div>
        <?php if (!empty($alpa_today)): ?>
            <span class="enterprise-badge badge-error"><?= count($alpa_today) ?> Siswa</span>
        <?php endif; ?>
    </div>

    <?php if (!empty($alpa_today)): ?>
        <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
            <div class="table-toolbar">
                <div class="table-search">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" class="table-search-input" placeholder="Cari siswa...">
                </div>
                <div class="table-actions">
                    <button class="btn-enterprise btn-secondary btn-sm">
                        <span>📥</span>
                        <span>Export</span>
                    </button>
                </div>
            </div>

            <table class="enterprise-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Jam Ke</th>
                        <th>Guru Pengajar</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alpa_today as $index => $record): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($record['nis']) ?></strong></td>
                            <td><?= esc($record['student_name']) ?></td>
                            <td>
                                <span class="enterprise-badge badge-info">
                                    <?= esc($record['class_name']) ?>
                                </span>
                            </td>
                            <td><?= esc($record['subject_name']) ?></td>
                            <td><?= esc($record['lesson_hour'] ?? '-') ?></td>
                            <td><?= esc($record['teacher_name']) ?></td>
                            <td>
                                <?php if (!empty($record['note'])): ?>
                                    <span style="color: var(--enterprise-text-secondary); font-size: 0.875rem;">
                                        <?= esc($record['note']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--enterprise-text-tertiary);">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">🎉</div>
            <h3 class="empty-state-title">Tidak Ada Siswa Alpa</h3>
            <p class="empty-state-message">
                Semua siswa hadir hari ini. Pertahankan kehadiran yang baik!
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions (Optional) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="enterprise-card" style="cursor: pointer;" onclick="window.location.href='<?= base_url('/attendance') ?>'">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; flex-shrink: 0;">
                ✓
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Input Absensi</h3>
                <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Catat kehadiran siswa</p>
            </div>
            <div style="color: var(--enterprise-text-tertiary); font-size: 1.5rem;">→</div>
        </div>
    </div>

    <div class="enterprise-card" style="cursor: pointer;" onclick="window.location.href='<?= base_url('/recap/admin') ?>'">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; flex-shrink: 0;">
                📊
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Lihat Rekap</h3>
                <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Laporan kehadiran lengkap</p>
            </div>
            <div style="color: var(--enterprise-text-tertiary); font-size: 1.5rem;">→</div>
        </div>
    </div>

    <div class="enterprise-card" style="cursor: pointer;" onclick="window.location.href='<?= base_url('/master') ?>'">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; flex-shrink: 0;">
                📚
            </div>
            <div style="flex: 1;">
                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Master Data</h3>
                <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Kelola data sistem</p>
            </div>
            <div style="color: var(--enterprise-text-tertiary); font-size: 1.5rem;">→</div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Chart colors matching enterprise theme
const enterpriseColors = {
    H: '#10b981',      // Hadir - Primary Green
    I: '#06b6d4',      // Izin - Cyan
    S: '#f59e0b',      // Sakit - Amber
    A: '#ef4444',      // Alpa - Red
    T: '#8b5cf6'       // Terlambat - Purple
};

// Enhanced chart configuration
const chartDefaults = {
    plugins: {
        legend: {
            labels: {
                font: {
                    family: 'Inter',
                    size: 13,
                    weight: '500'
                },
                padding: 20,
                usePointStyle: true,
                pointStyle: 'circle'
            }
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleFont: {
                family: 'Inter',
                size: 14,
                weight: '600'
            },
            bodyFont: {
                family: 'Inter',
                size: 13
            },
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            cornerRadius: 8
        }
    }
};

// 1. Pie Chart - Distribusi Kehadiran
const statusData = <?= json_encode($chart_status_data) ?>;
new Chart(document.getElementById('statusPieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpa', 'Terlambat'],
        datasets: [{
            data: [statusData.H, statusData.I, statusData.S, statusData.A, statusData.T],
            backgroundColor: [
                enterpriseColors.H,
                enterpriseColors.I,
                enterpriseColors.S,
                enterpriseColors.A,
                enterpriseColors.T
            ],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 8
        }]
    },
    options: {
        ...chartDefaults,
        responsive: true,
        maintainAspectRatio: true,
        cutout: '65%',
        plugins: {
            ...chartDefaults.plugins,
            legend: {
                ...chartDefaults.plugins.legend,
                position: 'bottom'
            },
            tooltip: {
                ...chartDefaults.plugins.tooltip,
                callbacks: {
                    label: function(ctx) {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = ((ctx.parsed / total) * 100).toFixed(1);
                        return ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID') + ' (' + pct + '%)';
                    }
                }
            }
        }
    }
});

// 2. Line Chart - Trend Kehadiran
const trendData = <?= json_encode($chart_trend_data) ?>;
new Chart(document.getElementById('trendLineChart'), {
    type: 'line',
    data: {
        labels: trendData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('id-ID', {day:'2-digit', month:'short'});
        }),
        datasets: [{
            label: 'Persentase Kehadiran',
            data: trendData.map(d => d.total > 0 ? ((d.hadir / d.total) * 100).toFixed(1) : 0),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: '#10b981',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointHoverBackgroundColor: '#10b981',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 3
        }]
    },
    options: {
        ...chartDefaults,
        responsive: true,
        maintainAspectRatio: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            ...chartDefaults.plugins,
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: v => v + '%',
                    font: {
                        family: 'Inter',
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    font: {
                        family: 'Inter',
                        size: 12
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});

// 3. Bar Chart - Kehadiran per Kelas
<?php if (!empty($chart_class_data)): ?>
const classData = <?= json_encode($chart_class_data) ?>;
new Chart(document.getElementById('classBarChart'), {
    type: 'bar',
    data: {
        labels: classData.map(d => d.class_name),
        datasets: [{
            label: 'Persentase Kehadiran',
            data: classData.map(d => d.total > 0 ? ((d.hadir / d.total) * 100).toFixed(1) : 0),
            backgroundColor: function(ctx) {
                const v = ctx.parsed.y;
                if (v >= 90) return enterpriseColors.H;
                if (v >= 75) return enterpriseColors.I;
                if (v >= 60) return enterpriseColors.S;
                return enterpriseColors.A;
            },
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        ...chartDefaults,
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            ...chartDefaults.plugins,
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: v => v + '%',
                    font: {
                        family: 'Inter',
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    font: {
                        family: 'Inter',
                        size: 12
                    }
                },
                grid: {
                    display: false
                }
            }
        }
    }
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
