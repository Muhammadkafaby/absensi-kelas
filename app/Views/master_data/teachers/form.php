<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2><?= isset($teacher) ? 'Edit Data Guru' : 'Tambah Guru Baru' ?></h2>

    <form action="<?= isset($teacher) ? base_url('/master/teachers/update/' . $teacher['id']) : base_url('/master/teachers/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Nama Lengkap *</label>
            <input type="text" id="name" name="name"
                   value="<?= old('name', $teacher['name'] ?? '') ?>"
                   placeholder="Nama lengkap guru"
                   required autofocus>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" id="nip" name="nip"
                       value="<?= old('nip', $teacher['nip'] ?? '') ?>"
                       placeholder="Nomor Induk Pegawai">
            </div>

            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input type="text" id="phone" name="phone"
                       value="<?= old('phone', $teacher['phone'] ?? '') ?>"
                       placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary">
                <?= isset($teacher) ? 'Update Data' : 'Simpan Guru' ?>
            </button>
            <a href="<?= base_url('/master/teachers') ?>" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
