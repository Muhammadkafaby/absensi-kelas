<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<form action="<?= base_url('/attendance/admin/store') ?>" method="POST" id="attendanceForm">
    <?= csrf_field() ?>

    <!-- Step Indicator -->
    <div class="enterprise-card mb-6">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
            <div style="flex: 1; display: flex; align-items: center; gap: 1rem;">
                <div id="step1" class="step-indicator active" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; background: var(--enterprise-primary-50); border-radius: 12px; flex: 1;">
                    <div style="width: 32px; height: 32px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">1</div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.75rem; color: var(--enterprise-text-tertiary);">Step 1</div>
                        <div style="font-size: 0.875rem; font-weight: 600; color: var(--enterprise-text-primary);">Pilih Kelas & Detail</div>
                    </div>
                </div>
                <div style="width: 32px; height: 2px; background: var(--enterprise-border);"></div>
                <div id="step2" class="step-indicator" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; background: var(--enterprise-bg-secondary); border-radius: 12px; flex: 1; opacity: 0.6;">
                    <div style="width: 32px; height: 32px; background: var(--enterprise-neutral-300); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">2</div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.75rem; color: var(--enterprise-text-tertiary);">Step 2</div>
                        <div style="font-size: 0.875rem; font-weight: 600; color: var(--enterprise-text-primary);">Input Kehadiran</div>
                    </div>
                </div>
                <div style="width: 32px; height: 2px; background: var(--enterprise-border);"></div>
                <div id="step3" class="step-indicator" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; background: var(--enterprise-bg-secondary); border-radius: 12px; flex: 1; opacity: 0.6;">
                    <div style="width: 32px; height: 32px; background: var(--enterprise-neutral-300); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">3</div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.75rem; color: var(--enterprise-text-tertiary);">Step 3</div>
                        <div style="font-size: 0.875rem; font-weight: 600; color: var(--enterprise-text-primary);">Simpan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Controls -->
    <div class="enterprise-card mb-6">
        <div class="card-header">
            <div>
                <h2 class="card-title">📝 Detail Absensi (Admin)</h2>
                <p class="card-subtitle">Input absensi untuk kelas tertentu</p>
            </div>
            <span class="enterprise-badge badge-info">Required</span>
        </div>

        <div class="form-row">
            <div class="enterprise-form-group">
                <label for="classSelect" class="enterprise-label enterprise-label-required">Kelas</label>
                <select id="classSelect" name="class_id" class="enterprise-select" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>"><?= esc($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="enterprise-form-group">
                <label for="dateInput" class="enterprise-label enterprise-label-required">Tanggal</label>
                <input type="date" id="dateInput" name="date" value="<?= date('Y-m-d') ?>" class="enterprise-input" required>
            </div>

            <div class="enterprise-form-group">
                <label for="hourSelect" class="enterprise-label">Jam Pelajaran</label>
                <select id="hourSelect" name="lesson_hour" class="enterprise-select">
                    <option value="">Pilih Jam</option>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>">Jam ke-<?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="enterprise-form-group">
                <label for="subjectSelect" class="enterprise-label enterprise-label-required">Mata Pelajaran</label>
                <select id="subjectSelect" name="subject_id" class="enterprise-select" required>
                    <option value="">Pilih Mapel</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="enterprise-form-group">
                <label for="teacherSelect" class="enterprise-label enterprise-label-required">Guru Pengampu</label>
                <select id="teacherSelect" name="teacher_id" class="enterprise-select" required>
                    <option value="">Pilih Guru</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>"><?= esc($teacher['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="enterprise-card" id="attendanceSection" style="display: none;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Kehadiran Siswa</h2>
                <p class="card-subtitle" id="classInfo">-</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <span class="enterprise-badge badge-success" id="presentCount">Hadir: 0</span>
                <span class="enterprise-badge badge-error" id="absentCount">Alpa: 0</span>
                <span class="enterprise-badge badge-neutral" id="totalCount">Total: 0</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <button type="button" id="allPresentBtn" class="btn-enterprise btn-secondary btn-sm">
                <span>✓</span>
                <span>Semua Hadir</span>
            </button>
            <button type="button" id="resetBtn" class="btn-enterprise btn-ghost btn-sm">
                <span>↻</span>
                <span>Reset</span>
            </button>
            <button type="button" onclick="setAllStatus('I')" class="btn-enterprise btn-ghost btn-sm">
                <span>📋</span>
                <span>Semua Izin</span>
            </button>
            <button type="button" onclick="setAllStatus('S')" class="btn-enterprise btn-ghost btn-sm">
                <span>🏥</span>
                <span>Semua Sakit</span>
            </button>
        </div>

        <!-- Table -->
        <div class="enterprise-table-wrapper" style="border: none; background: transparent;">
            <div class="table-toolbar">
                <div class="table-search">
                    <span class="table-search-icon">🔍</span>
                    <input type="text" id="searchInput" class="table-search-input" placeholder="Cari nama atau NIS...">
                </div>
                <div class="table-actions">
                    <button type="button" class="btn-enterprise btn-secondary btn-sm" onclick="exportAttendance()">
                        <span>📥</span>
                        <span>Export</span>
                    </button>
                </div>
            </div>

            <table class="enterprise-table" id="attendanceTable">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="width: 120px;">NIS</th>
                        <th>Nama Siswa</th>
                        <th style="width: 200px;">Status</th>
                        <th style="width: 300px;">Catatan</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody">
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 3rem; color: var(--enterprise-text-secondary);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                            <div style="font-weight: 600; margin-bottom: 0.5rem;">Belum Ada Data</div>
                            <div style="font-size: 0.875rem;">Pilih kelas untuk memuat daftar siswa</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Submit Section -->
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--enterprise-border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 0.875rem; color: var(--enterprise-text-secondary);">
                    <span style="font-weight: 600; color: var(--enterprise-text-primary);" id="filledCount">0</span> dari <span id="totalStudents">0</span> siswa telah diisi
                </div>
                <div id="validationWarning" style="display: none; padding: 0.5rem 1rem; background: var(--enterprise-warning-50); color: var(--enterprise-warning-600); border-radius: 8px; font-size: 0.875rem;">
                    ⚠️ Ada siswa yang belum diisi
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="saveDraft()" class="btn-enterprise btn-secondary">
                    <span>💾</span>
                    <span>Simpan Draft</span>
                </button>
                <button type="submit" class="btn-enterprise btn-primary btn-lg" id="submitBtn">
                    <span>✓</span>
                    <span>SIMPAN ABSENSI</span>
                </button>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script src="<?= base_url('assets/js/attendance-teacher.js') ?>"></script>
<script>
// Enhanced attendance functionality
let students = [];
let attendanceData = {};

// Load students when class is selected
document.getElementById('classSelect').addEventListener('change', async function() {
    const classId = this.value;
    if (!classId) {
        document.getElementById('attendanceSection').style.display = 'none';
        return;
    }

    // Show loading
    const tbody = document.getElementById('attendanceTableBody');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding: 3rem;"><div class="spinner"></div><div style="margin-top: 1rem;">Memuat data siswa...</div></td></tr>';
    document.getElementById('attendanceSection').style.display = 'block';

    try {
        const response = await fetch(`<?= base_url('/attendance/get-students/') ?>${classId}`);
        students = await response.json();

        renderStudentTable();
        updateStepIndicator(2);
        updateClassInfo();
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding: 3rem; color: var(--enterprise-error-500);">❌ Gagal memuat data siswa</td></tr>';
    }
});

function renderStudentTable() {
    const tbody = document.getElementById('attendanceTableBody');
    if (students.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center" style="padding: 3rem;">Tidak ada siswa di kelas ini</td></tr>';
        return;
    }

    tbody.innerHTML = students.map((student, index) => `
        <tr>
            <td><strong>${index + 1}</strong></td>
            <td><strong>${student.nis}</strong></td>
            <td>${student.name}</td>
            <td>
                <select name="attendance[${student.id}]" class="enterprise-select" onchange="updateCounts()" required style="font-size: 0.875rem; padding: 0.5rem;">
                    <option value="">Pilih Status</option>
                    <option value="H" selected>✓ Hadir</option>
                    <option value="I">📋 Izin</option>
                    <option value="S">🏥 Sakit</option>
                    <option value="A">❌ Alpa</option>
                    <option value="T">⏰ Terlambat</option>
                </select>
            </td>
            <td>
                <input type="text" name="note[${student.id}]" class="enterprise-input" placeholder="Catatan (opsional)" style="font-size: 0.875rem; padding: 0.5rem;">
            </td>
        </tr>
    `).join('');

    updateCounts();
    document.getElementById('totalStudents').textContent = students.length;
}

function updateCounts() {
    const selects = document.querySelectorAll('select[name^="attendance"]');
    let counts = { H: 0, I: 0, S: 0, A: 0, T: 0, total: 0, filled: 0 };

    selects.forEach(select => {
        const val = select.value;
        if (val) {
            counts[val] = (counts[val] || 0) + 1;
            counts.filled++;
        }
        counts.total++;
    });

    document.getElementById('presentCount').textContent = `Hadir: ${counts.H}`;
    document.getElementById('absentCount').textContent = `Alpa: ${counts.A}`;
    document.getElementById('totalCount').textContent = `Total: ${counts.total}`;
    document.getElementById('filledCount').textContent = counts.filled;

    // Validation warning
    const warning = document.getElementById('validationWarning');
    if (counts.filled < counts.total) {
        warning.style.display = 'block';
    } else {
        warning.style.display = 'none';
        updateStepIndicator(3);
    }
}

function updateStepIndicator(step) {
    for (let i = 1; i <= 3; i++) {
        const indicator = document.getElementById(`step${i}`);
        if (i <= step) {
            indicator.style.background = 'var(--enterprise-primary-50)';
            indicator.style.opacity = '1';
            indicator.querySelector('div:first-child').style.background = 'var(--gradient-primary)';
        } else {
            indicator.style.background = 'var(--enterprise-bg-secondary)';
            indicator.style.opacity = '0.6';
            indicator.querySelector('div:first-child').style.background = 'var(--enterprise-neutral-300)';
        }
    }
}

function updateClassInfo() {
    const classSelect = document.getElementById('classSelect');
    const dateInput = document.getElementById('dateInput');
    const hourSelect = document.getElementById('hourSelect');
    const subjectSelect = document.getElementById('subjectSelect');

    const className = classSelect.options[classSelect.selectedIndex]?.text || '-';
    const date = dateInput.value ? new Date(dateInput.value).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
    const hour = hourSelect.value ? `Jam ke-${hourSelect.value}` : 'Semua jam';
    const subject = subjectSelect.options[subjectSelect.selectedIndex]?.text || '-';

    document.getElementById('classInfo').textContent = `${className} • ${subject} • ${date} • ${hour}`;
}

// Quick actions
document.getElementById('allPresentBtn').addEventListener('click', function() {
    document.querySelectorAll('select[name^="attendance"]').forEach(select => {
        select.value = 'H';
    });
    updateCounts();
    if (window.Toast) Toast.success('Semua siswa ditandai hadir');
});

document.getElementById('resetBtn').addEventListener('click', function() {
    if (confirm('Reset semua status kehadiran?')) {
        document.querySelectorAll('select[name^="attendance"]').forEach(select => {
            select.value = 'H';
        });
        document.querySelectorAll('input[name^="note"]').forEach(input => {
            input.value = '';
        });
        updateCounts();
        if (window.Toast) Toast.info('Status direset');
    }
});

function setAllStatus(status) {
    document.querySelectorAll('select[name^="attendance"]').forEach(select => {
        select.value = status;
    });
    updateCounts();
    const statusNames = { I: 'Izin', S: 'Sakit', A: 'Alpa', T: 'Terlambat' };
    if (window.Toast) Toast.info(`Semua siswa ditandai ${statusNames[status]}`);
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('#attendanceTableBody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
});

// Update class info on field changes
['classSelect', 'dateInput', 'hourSelect', 'subjectSelect'].forEach(id => {
    document.getElementById(id).addEventListener('change', updateClassInfo);
});

// Form submission
document.getElementById('attendanceForm').addEventListener('submit', function(e) {
    const selects = document.querySelectorAll('select[name^="attendance"]');
    let allFilled = true;

    selects.forEach(select => {
        if (!select.value) allFilled = false;
    });

    if (!allFilled) {
        e.preventDefault();
        if (window.Toast) Toast.warning('Mohon lengkapi status kehadiran semua siswa');
        return false;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span>⏳</span><span>Menyimpan...</span>';
});

// Save draft (localStorage)
function saveDraft() {
    const formData = new FormData(document.getElementById('attendanceForm'));
    const draft = Object.fromEntries(formData);
    localStorage.setItem('attendance_draft', JSON.stringify(draft));
    if (window.Toast) Toast.success('Draft tersimpan');
}

// Load draft on page load
window.addEventListener('DOMContentLoaded', function() {
    const draft = localStorage.getItem('attendance_draft');
    if (draft) {
        const data = JSON.parse(draft);
        // You can implement auto-fill from draft here if needed
    }
});

// Export functionality
function exportAttendance() {
    if (window.Toast) Toast.info('Fitur export akan segera tersedia');
}
</script>
<?= $this->endSection() ?>
