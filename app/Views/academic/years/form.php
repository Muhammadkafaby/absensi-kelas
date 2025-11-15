<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <h2 class="gradient-text mb-2">
        <?= isset($year) ? '✏️ Edit Tahun Ajaran' : '➕ Tambah Tahun Ajaran' ?>
    </h2>

    <form action="<?= isset($year) ? base_url('/academic/years/update/' . $year['id']) : base_url('/academic/years/store') ?>"
          method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">
                Nama Tahun Ajaran <span class="text-danger">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= old('name', $year['name'] ?? '') ?>"
                   placeholder="Contoh: 2024/2025"
                   required>
            <small class="text-secondary">Format: YYYY/YYYY</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date">
                    Tanggal Mulai <span class="text-danger">*</span>
                </label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       value="<?= old('start_date', $year['start_date'] ?? '') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="end_date">
                    Tanggal Selesai <span class="text-danger">*</span>
                </label>
                <input type="date"
                       id="end_date"
                       name="end_date"
                       value="<?= old('end_date', $year['end_date'] ?? '') ?>"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label class="flex" style="align-items: center; cursor: pointer;">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       <?= old('is_active', $year['is_active'] ?? 0) ? 'checked' : '' ?>
                       style="margin-right: 0.5rem;">
                <span>Aktifkan tahun ajaran ini</span>
            </label>
            <small class="text-secondary">
                Hanya satu tahun ajaran yang dapat aktif pada satu waktu
            </small>
        </div>

        <div class="button-group mt-2">
            <button type="submit" class="btn-primary ripple">
                <?= isset($year) ? 'Update' : 'Simpan' ?>
            </button>
            <a href="<?= base_url('/academic/years') ?>" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
