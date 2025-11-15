<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/master/subjects') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary);">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            <?= isset($subject) ? '✏️' : '➕' ?>
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= isset($subject) ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' ?>
            </h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">
                <?= isset($subject) ? 'Perbarui data mapel' : 'Lengkapi data mapel di bawah' ?>
            </p>
        </div>
    </div>
</div>

<div class="enterprise-card" style="max-width: 900px;">
    <form action="<?= isset($subject) ? base_url('/master/subjects/update/' . $subject['id']) : base_url('/master/subjects/store') ?>" method="POST">
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
            <div class="form-row">
                <div class="enterprise-form-group">
                    <label for="code" class="enterprise-label enterprise-label-required">Kode Mapel</label>
                    <input type="text" id="code" name="code" class="enterprise-input"
                           value="<?= old('code', $subject['code'] ?? '') ?>"
                           placeholder="Contoh: MTK, FIS, BIO"
                           required autofocus>
                </div>
                <div class="enterprise-form-group">
                    <label for="name" class="enterprise-label enterprise-label-required">Nama Mata Pelajaran</label>
                    <input type="text" id="name" name="name" class="enterprise-input"
                           value="<?= old('name', $subject['name'] ?? '') ?>"
                           placeholder="Contoh: Matematika, Fisika"
                           required>
                </div>
            </div>
            <div class="enterprise-form-group">
                <label for="teacher_id" class="enterprise-label">Guru Pengampu</label>
                <select id="teacher_id" name="teacher_id" class="enterprise-select">
                    <option value="">Pilih Guru</option>
                    <?php if (!empty($teachers)): ?>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>" <?= old('teacher_id', $subject['teacher_id'] ?? '') == $teacher['id'] ? 'selected' : '' ?>>
                                <?= esc($teacher['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/master/subjects') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span><span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span><?= isset($subject) ? '💾' : '✓' ?></span>
                    <span><?= isset($subject) ? 'Update Mapel' : 'Simpan Mapel' ?></span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
