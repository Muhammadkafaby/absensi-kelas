<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/academic/years') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary);">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            <?= isset($year) ? '✏️' : '➕' ?>
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= isset($year) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' ?>
            </h1>
        </div>
    </div>
</div>

<div class="enterprise-card" style="max-width: 800px;">
    <form action="<?= isset($year) ? base_url('/academic/years/update/' . $year['id']) : base_url('/academic/years/store') ?>" method="POST">
        <?= csrf_field() ?>

        <?php if (session()->has('errors')): ?>
            <div class="enterprise-alert alert-error mb-4">
                <div style="font-weight: 600; margin-bottom: 0.5rem;">⚠️ Terdapat kesalahan:</div>
                <ul style="margin: 0; padding-left: 1.5rem;">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="enterprise-form-group">
                <label for="name" class="enterprise-label enterprise-label-required">Nama Tahun Ajaran</label>
                <input type="text" id="name" name="name" class="enterprise-input"
                       value="<?= old('name', $year['name'] ?? '') ?>"
                       placeholder="Contoh: 2024/2025"
                       required autofocus>
            </div>
            <div class="form-row">
                <div class="enterprise-form-group">
                    <label for="start_date" class="enterprise-label enterprise-label-required">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="enterprise-input"
                           value="<?= old('start_date', $year['start_date'] ?? '') ?>" required>
                </div>
                <div class="enterprise-form-group">
                    <label for="end_date" class="enterprise-label enterprise-label-required">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" class="enterprise-input"
                           value="<?= old('end_date', $year['end_date'] ?? '') ?>" required>
                </div>
            </div>
            <div class="enterprise-form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= old('is_active', $year['is_active'] ?? false) ? 'checked' : '' ?> style="width: 20px; height: 20px;">
                    <span>Aktifkan tahun ajaran ini</span>
                </label>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/academic/years') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span><span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span><?= isset($year) ? '💾' : '✓' ?></span>
                    <span><?= isset($year) ? 'Update' : 'Simpan' ?></span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
