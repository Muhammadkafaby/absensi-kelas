<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/master/students') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary); transition: all 0.2s;" onmouseover="this.style.background='var(--enterprise-bg-tertiary)'" onmouseout="this.style.background='var(--enterprise-bg-secondary)'">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            <?= isset($student) ? '✏️' : '➕' ?>
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= isset($student) ? 'Edit Siswa' : 'Tambah Siswa Baru' ?>
            </h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">
                <?= isset($student) ? 'Perbarui data siswa' : 'Lengkapi data siswa di bawah' ?>
            </p>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="enterprise-card" style="max-width: 900px;">
    <form action="<?= isset($student) ? base_url('/master/students/update/' . $student['id']) : base_url('/master/students/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="form-row">
                <!-- NIS -->
                <div class="enterprise-form-group">
                    <label for="nis" class="enterprise-label enterprise-label-required">NIS</label>
                    <input type="text" id="nis" name="nis" class="enterprise-input"
                           value="<?= old('nis', $student['nis'] ?? '') ?>"
                           placeholder="Nomor Induk Siswa"
                           required autofocus>
                </div>

                <!-- NISN -->
                <div class="enterprise-form-group">
                    <label for="nisn" class="enterprise-label">NISN</label>
                    <input type="text" id="nisn" name="nisn" class="enterprise-input"
                           value="<?= old('nisn', $student['nisn'] ?? '') ?>"
                           placeholder="Nomor Induk Siswa Nasional">
                    <p class="form-helper-text">Opsional</p>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="enterprise-form-group">
                <label for="name" class="enterprise-label enterprise-label-required">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="enterprise-input"
                       value="<?= old('name', $student['name'] ?? '') ?>"
                       placeholder="Nama lengkap siswa"
                       required>
            </div>

            <div class="form-row">
                <!-- Kelas -->
                <div class="enterprise-form-group">
                    <label for="class_id" class="enterprise-label enterprise-label-required">Kelas</label>
                    <select id="class_id" name="class_id" class="enterprise-select" required>
                        <option value="">Pilih Kelas</option>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= old('class_id', $student['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>>
                                    <?= esc($class['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Jenis Kelamin -->
                <div class="enterprise-form-group">
                    <label for="gender" class="enterprise-label enterprise-label-required">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="enterprise-select" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" <?= old('gender', $student['gender'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="P" <?= old('gender', $student['gender'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Status -->
            <div class="enterprise-form-group">
                <label for="status" class="enterprise-label enterprise-label-required">Status</label>
                <select id="status" name="status" class="enterprise-select" required>
                    <option value="active" <?= old('status', $student['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= old('status', $student['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
                <p class="form-helper-text">Siswa aktif akan muncul di daftar absensi</p>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/master/students') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span><?= isset($student) ? '💾' : '✓' ?></span>
                    <span><?= isset($student) ? 'Update Siswa' : 'Simpan Siswa' ?></span>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
