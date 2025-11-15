<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <h2 class="gradient-text"><?= isset($subject) ? '✏️ Edit Mata Pelajaran' : '➕ Tambah Mata Pelajaran Baru' ?></h2>

    <form action="<?= isset($subject) ? base_url('/master/subjects/update/' . $subject['id']) : base_url('/master/subjects/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-row">
            <div class="form-group">
                <label for="code">Kode Mapel *</label>
                <input type="text" id="code" name="code"
                       value="<?= old('code', $subject['code'] ?? '') ?>"
                       placeholder="Contoh: MTK, BIN, BING"
                       required autofocus>
            </div>

            <div class="form-group">
                <label for="name">Nama Mata Pelajaran *</label>
                <input type="text" id="name" name="name"
                       value="<?= old('name', $subject['name'] ?? '') ?>"
                       placeholder="Contoh: Matematika"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label for="teacher_id">Guru Pengampu</label>
            <select id="teacher_id" name="teacher_id">
                <option value="">Belum Ditentukan</option>
                <?php foreach ($teachers as $teacher): ?>
                    <option value="<?= $teacher['id'] ?>"
                            <?= old('teacher_id', $subject['teacher_id'] ?? '') == $teacher['id'] ? 'selected' : '' ?>>
                        <?= esc($teacher['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary ripple">
                <?= isset($subject) ? 'Update Mapel' : 'Simpan Mapel' ?>
            </button>
            <a href="<?= base_url('/master/subjects') ?>" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
