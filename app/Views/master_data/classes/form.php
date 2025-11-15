<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2><?= isset($class) ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h2>

    <form action="<?= isset($class) ? base_url('/master/classes/update/' . $class['id']) : base_url('/master/classes/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Nama Kelas *</label>
            <input type="text" id="name" name="name"
                   value="<?= old('name', $class['name'] ?? '') ?>"
                   placeholder="Contoh: X-1, XI IPA 1, XII IPS 2"
                   required autofocus>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="level">Tingkat *</label>
                <select id="level" name="level" required>
                    <option value="">Pilih Tingkat</option>
                    <option value="X" <?= old('level', $class['level'] ?? '') == 'X' ? 'selected' : '' ?>>X (10)</option>
                    <option value="XI" <?= old('level', $class['level'] ?? '') == 'XI' ? 'selected' : '' ?>>XI (11)</option>
                    <option value="XII" <?= old('level', $class['level'] ?? '') == 'XII' ? 'selected' : '' ?>>XII (12)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="major">Jurusan (Opsional)</label>
                <select id="major" name="major">
                    <option value="">Tidak Ada / Umum</option>
                    <option value="IPA" <?= old('major', $class['major'] ?? '') == 'IPA' ? 'selected' : '' ?>>IPA</option>
                    <option value="IPS" <?= old('major', $class['major'] ?? '') == 'IPS' ? 'selected' : '' ?>>IPS</option>
                    <option value="Bahasa" <?= old('major', $class['major'] ?? '') == 'Bahasa' ? 'selected' : '' ?>>Bahasa</option>
                </select>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary">
                <?= isset($class) ? 'Update Kelas' : 'Simpan Kelas' ?>
            </button>
            <a href="<?= base_url('/master/classes') ?>" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
