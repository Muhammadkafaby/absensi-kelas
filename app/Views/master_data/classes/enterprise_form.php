<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/master/classes') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary); transition: all 0.2s;" onmouseover="this.style.background='var(--enterprise-bg-tertiary)'" onmouseout="this.style.background='var(--enterprise-bg-secondary)'">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            <?= isset($class) ? '✏️' : '➕' ?>
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= isset($class) ? 'Edit Kelas' : 'Tambah Kelas Baru' ?>
            </h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">
                <?= isset($class) ? 'Perbarui informasi kelas' : 'Lengkapi formulir di bawah ini' ?>
            </p>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="enterprise-card" style="max-width: 800px;">
    <form action="<?= isset($class) ? base_url('/master/classes/update/' . $class['id']) : base_url('/master/classes/store') ?>" method="POST">
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
            <!-- Nama Kelas -->
            <div class="enterprise-form-group">
                <label for="name" class="enterprise-label enterprise-label-required">Nama Kelas</label>
                <input type="text" id="name" name="name" class="enterprise-input"
                       value="<?= old('name', $class['name'] ?? '') ?>"
                       placeholder="Contoh: X-1, XI IPA 1, XII IPS 2"
                       required autofocus>
                <p class="form-helper-text">Format: Tingkat-Nomor atau Tingkat Jurusan Nomor</p>
            </div>

            <div class="form-row">
                <!-- Tingkat -->
                <div class="enterprise-form-group">
                    <label for="level" class="enterprise-label enterprise-label-required">Tingkat</label>
                    <select id="level" name="level" class="enterprise-select" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="X" <?= old('level', $class['level'] ?? '') == 'X' ? 'selected' : '' ?>>X (Kelas 10)</option>
                        <option value="XI" <?= old('level', $class['level'] ?? '') == 'XI' ? 'selected' : '' ?>>XI (Kelas 11)</option>
                        <option value="XII" <?= old('level', $class['level'] ?? '') == 'XII' ? 'selected' : '' ?>>XII (Kelas 12)</option>
                    </select>
                </div>

                <!-- Jurusan -->
                <div class="enterprise-form-group">
                    <label for="major" class="enterprise-label">Jurusan</label>
                    <select id="major" name="major" class="enterprise-select">
                        <option value="">Tidak Ada / Umum</option>
                        <option value="IPA" <?= old('major', $class['major'] ?? '') == 'IPA' ? 'selected' : '' ?>>IPA</option>
                        <option value="IPS" <?= old('major', $class['major'] ?? '') == 'IPS' ? 'selected' : '' ?>>IPS</option>
                        <option value="Bahasa" <?= old('major', $class['major'] ?? '') == 'Bahasa' ? 'selected' : '' ?>>Bahasa</option>
                    </select>
                    <p class="form-helper-text">Opsional - Kosongkan jika tidak ada penjurusan</p>
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/master/classes') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span><?= isset($class) ? '💾' : '✓' ?></span>
                    <span><?= isset($class) ? 'Update Kelas' : 'Simpan Kelas' ?></span>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
