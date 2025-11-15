<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">
        <?= isset($semester) ? 'Edit Semester' : 'Tambah Semester' ?>
    </h2>

    <form action="<?= isset($semester) ? base_url('/academic/semesters/update/' . $semester['id']) : base_url('/academic/semesters/store') ?>"
          method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="academic_year_id">
                Tahun Ajaran <span style="color: red;">*</span>
            </label>
            <select id="academic_year_id" name="academic_year_id" required>
                <option value="">Pilih Tahun Ajaran</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?= $year['id'] ?>"
                            <?= old('academic_year_id', $semester['academic_year_id'] ?? $selectedYearId ?? '') == $year['id'] ? 'selected' : '' ?>>
                        <?= esc($year['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name">
                Nama Semester <span style="color: red;">*</span>
            </label>
            <select id="name" name="name" required>
                <option value="">Pilih Semester</option>
                <option value="Ganjil" <?= old('name', $semester['name'] ?? '') == 'Ganjil' ? 'selected' : '' ?>>
                    Ganjil
                </option>
                <option value="Genap" <?= old('name', $semester['name'] ?? '') == 'Genap' ? 'selected' : '' ?>>
                    Genap
                </option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_date">
                    Tanggal Mulai <span style="color: red;">*</span>
                </label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       value="<?= old('start_date', $semester['start_date'] ?? '') ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="end_date">
                    Tanggal Selesai <span style="color: red;">*</span>
                </label>
                <input type="date"
                       id="end_date"
                       name="end_date"
                       value="<?= old('end_date', $semester['end_date'] ?? '') ?>"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox"
                       id="is_active"
                       name="is_active"
                       value="1"
                       <?= old('is_active', $semester['is_active'] ?? 0) ? 'checked' : '' ?>
                       style="margin-right: 0.5rem;">
                <span>Aktifkan semester ini</span>
            </label>
            <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                Hanya satu semester yang dapat aktif pada satu waktu
            </small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn-primary">
                <?= isset($semester) ? 'Update' : 'Simpan' ?>
            </button>
            <a href="<?= base_url('/academic/semesters' . (isset($semester) ? '?year_id=' . $semester['academic_year_id'] : '')) ?>" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
