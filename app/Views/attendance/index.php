<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form action="<?= base_url('/attendance/store') ?>" method="POST" id="attendanceForm">
    <?= csrf_field() ?>

    <div class="card glass-card fade-in controls-card">
        <h2 class="gradient-text mb-2">📝 Input Absensi</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="classSelect">Kelas *</label>
                <select id="classSelect" name="class_id" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>"><?= esc($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="dateInput">Tanggal *</label>
                <input type="date" id="dateInput" name="date" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label for="hourSelect">Jam ke-</label>
                <select id="hourSelect" name="lesson_hour">
                    <option value="">Pilih Jam</option>
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subjectSelect">Mata Pelajaran *</label>
                <select id="subjectSelect" name="subject_id" required>
                    <option value="">Pilih Mapel</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>"><?= esc($subject['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="button-group">
            <button type="button" id="allPresentBtn" class="btn-secondary">✓ Semua Hadir</button>
            <button type="button" id="resetBtn" class="btn-secondary">↺ Reset</button>
        </div>
    </div>

    <div class="card glass-card">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Cari nama/NIS…">
        </div>

        <div class="table-container">
            <table id="attendanceTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kehadiran</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody">
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            Silakan pilih kelas terlebih dahulu
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary ripple">💾 SIMPAN ABSENSI</button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
// Load students when class is selected
document.getElementById('classSelect').addEventListener('change', function() {
    const classId = this.value;
    const tbody = document.getElementById('attendanceTableBody');

    if (!classId) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">Silakan pilih kelas terlebih dahulu</td></tr>';
        return;
    }

    // Fetch students
    fetch('<?= base_url('/attendance/students') ?>/' + classId)
        .then(response => response.json())
        .then(students => {
            if (students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">Tidak ada siswa di kelas ini</td></tr>';
                return;
            }

            let html = '';
            students.forEach((student, index) => {
                html += `
                    <tr data-name="${student.name.toLowerCase()}" data-nis="${student.nis}">
                        <td>${index + 1}</td>
                        <td>${student.nis}</td>
                        <td>${student.name}</td>
                        <td>
                            <div class="attendance-status">
                                <label class="status-option">
                                    <input type="radio" name="attendance[${student.id}]" value="H" required>
                                    <span>H</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="attendance[${student.id}]" value="I">
                                    <span>I</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="attendance[${student.id}]" value="S">
                                    <span>S</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="attendance[${student.id}]" value="A">
                                    <span>A</span>
                                </label>
                                <label class="status-option">
                                    <input type="radio" name="attendance[${student.id}]" value="T">
                                    <span>T</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="note[${student.id}]" placeholder="Catatan (opsional)" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 0.375rem;">
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

            // Add radio button styling
            addRadioStyling();
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem; color: red;">Gagal memuat data siswa</td></tr>';
        });
});

// Function to add styling to radio buttons
function addRadioStyling() {
    const labels = document.querySelectorAll('.status-option');
    labels.forEach(label => {
        const radio = label.querySelector('input[type="radio"]');
        const span = label.querySelector('span');

        radio.addEventListener('change', function() {
            // Remove selected class from all options in this row
            const row = this.closest('tr');
            row.querySelectorAll('.status-option').forEach(opt => {
                opt.classList.remove('selected');
            });

            // Add selected class to this option
            if (this.checked) {
                label.classList.add('selected');
            }
        });
    });
}

// All present button
document.getElementById('allPresentBtn').addEventListener('click', function() {
    const radios = document.querySelectorAll('input[type="radio"][value="H"]');
    radios.forEach(radio => {
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
    });
});

// Reset button
document.getElementById('resetBtn').addEventListener('click', function() {
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
    document.querySelectorAll('.status-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.value = '';
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#attendanceTableBody tr');

    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const nis = row.getAttribute('data-nis');

        if (name && nis) {
            if (name.includes(searchTerm) || nis.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
