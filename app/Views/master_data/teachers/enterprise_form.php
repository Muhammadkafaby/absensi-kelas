<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/master/teachers') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary);">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            <?= isset($teacher) ? '✏️' : '➕' ?>
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= isset($teacher) ? 'Edit Guru' : 'Tambah Guru Baru' ?>
            </h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">
                <?= isset($teacher) ? 'Perbarui data guru' : 'Lengkapi data guru di bawah' ?>
            </p>
        </div>
    </div>
</div>

<div class="enterprise-card" style="max-width: 900px;">
    <form action="<?= isset($teacher) ? base_url('/master/teachers/update/' . $teacher['id']) : base_url('/master/teachers/store') ?>" method="POST">
        <?= csrf_field() ?>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="form-row">
                <div class="enterprise-form-group">
                    <label for="nip" class="enterprise-label">NIP</label>
                    <input type="text" id="nip" name="nip" class="enterprise-input"
                           value="<?= old('nip', $teacher['nip'] ?? '') ?>"
                           placeholder="Nomor Induk Pegawai">
                </div>
                <div class="enterprise-form-group">
                    <label for="name" class="enterprise-label enterprise-label-required">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="enterprise-input"
                           value="<?= old('name', $teacher['name'] ?? '') ?>"
                           placeholder="Nama lengkap guru"
                           required autofocus>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/master/teachers') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span><span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span><?= isset($teacher) ? '💾' : '✓' ?></span>
                    <span><?= isset($teacher) ? 'Update Guru' : 'Simpan Guru' ?></span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
