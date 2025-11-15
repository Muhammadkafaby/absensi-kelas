<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/academic/semesters') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary);">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            <?= isset($semester) ? '✏️' : '➕' ?>
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">
                <?= isset($semester) ? 'Edit Semester' : 'Tambah Semester' ?>
            </h1>
        </div>
    </div>
</div>

<div class="enterprise-card" style="max-width: 800px;">
    <form action="<?= isset($semester) ? base_url('/academic/semesters/update/' . $semester['id']) : base_url('/academic/semesters/store') ?>" method="POST">
        <?= csrf_field() ?>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="form-row">
                <div class="enterprise-form-group">
                    <label for="academic_year_id" class="enterprise-label enterprise-label-required">Tahun Ajaran</label>
                    <select id="academic_year_id" name="academic_year_id" class="enterprise-select" required>
                        <option value="">Pilih Tahun Ajaran</option>
                        <?php if (!empty($years)): ?>
                            <?php foreach ($years as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= old('academic_year_id', $semester['academic_year_id'] ?? $selectedYearId ?? '') == $year['id'] ? 'selected' : '' ?>>
                                    <?= esc($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="enterprise-form-group">
                    <label for="name" class="enterprise-label enterprise-label-required">Nama Semester</label>
                    <select id="name" name="name" class="enterprise-select" required>
                        <option value="">Pilih</option>
                        <option value="Semester 1" <?= old('name', $semester['name'] ?? '') == 'Semester 1' ? 'selected' : '' ?>>Semester 1</option>
                        <option value="Semester 2" <?= old('name', $semester['name'] ?? '') == 'Semester 2' ? 'selected' : '' ?>>Semester 2</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="enterprise-form-group">
                    <label for="start_date" class="enterprise-label enterprise-label-required">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" class="enterprise-input"
                           value="<?= old('start_date', $semester['start_date'] ?? '') ?>" required>
                </div>
                <div class="enterprise-form-group">
                    <label for="end_date" class="enterprise-label enterprise-label-required">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" class="enterprise-input"
                           value="<?= old('end_date', $semester['end_date'] ?? '') ?>" required>
                </div>
            </div>
            <div class="enterprise-form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= old('is_active', $semester['is_active'] ?? false) ? 'checked' : '' ?> style="width: 20px; height: 20px;">
                    <span>Aktifkan semester ini</span>
                </label>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/academic/semesters') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span><span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span><?= isset($semester) ? '💾' : '✓' ?></span>
                    <span><?= isset($semester) ? 'Update' : 'Simpan' ?></span>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
