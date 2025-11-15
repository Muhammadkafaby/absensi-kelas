<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <?= isset($year) ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' ?>
    </h2>

    <form action="<?= isset($year) ? base_url('/academic/years/update/' . $year['id']) : base_url('/academic/years/store') ?>"
          method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">
                Nama Tahun Ajaran <span style="color: red;">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= old('name', $year['name'] ?? '') ?>"
                   placeholder="Contoh: 2024/2025"
                   required>
            <small style="color: var(--text-secondary);">Format: YYYY/YYYY</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date">
                    Tanggal Mulai <span style="color: red;">*</span>
                </label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       value="<?= old('start_date', $year['start_date'] ?? '') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="end_date">
                    Tanggal Selesai <span style="color: red;">*</span>
                </label>
                <input type="date"
                       id="end_date"
                       name="end_date"
                       value="<?= old('end_date', $year['end_date'] ?? '') ?>"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       <?= old('is_active', $year['is_active'] ?? 0) ? 'checked' : '' ?>
                       style="margin-right: 0.5rem;">
                <span>Aktifkan tahun ajaran ini</span>
            </label>
            <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                Hanya satu tahun ajaran yang dapat aktif pada satu waktu
            </small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn-primary">
                <?= isset($year) ? 'Update' : 'Simpan' ?>
            </button>
            <a href="<?= base_url('/academic/years') ?>" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
