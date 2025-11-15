<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <h2 class="gradient-text"><?= isset($student) ? '✏️ Edit Data Siswa' : '➕ Tambah Siswa Baru' ?></h2>

    <form action="<?= isset($student) ? base_url('/master/students/update/' . $student['id']) : base_url('/master/students/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="nis">NIS *</label>
                <input type="text" id="nis" name="nis"
                       value="<?= old('nis', $student['nis'] ?? '') ?>"
                       placeholder="Nomor Induk Siswa"
                       required>
            </div>

            <div class="form-group">
                <label for="nisn">NISN</label>
                <input type="text" id="nisn" name="nisn"
                       value="<?= old('nisn', $student['nisn'] ?? '') ?>"
                       placeholder="Nomor Induk Siswa Nasional">
            </div>
        </div>

        <div class="form-group">
            <label for="name">Nama Lengkap *</label>
            <input type="text" id="name" name="name"
                   value="<?= old('name', $student['name'] ?? '') ?>"
                   placeholder="Nama lengkap siswa"
                   required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="class_id">Kelas *</label>
                <select id="class_id" name="class_id" required>
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>"
                                <?= old('class_id', $student['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>>
                            <?= esc($class['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="gender">Jenis Kelamin *</label>
                <select id="gender" name="gender" required>
                    <option value="">Pilih</option>
                    <option value="L" <?= old('gender', $student['gender'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= old('gender', $student['gender'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" <?= old('status', $student['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= old('status', $student['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary ripple">
                <?= isset($student) ? 'Update Data' : 'Simpan Siswa' ?>
            </button>
            <a href="<?= base_url('/master/students') ?>" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
