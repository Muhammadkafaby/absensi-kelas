<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <h2 style="margin-bottom: 1.5rem;">Import Data Siswa dari Excel</h2>

    <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem;">
        <h3 style="margin-bottom: 0.5rem; color: #1e40af;">📋 Instruksi Import</h3>
        <ol style="margin: 0.5rem 0; padding-left: 1.5rem; color: #1e3a8a;">
            <li>Download template Excel terlebih dahulu</li>
            <li>Isi data siswa sesuai format template (NIS, NISN, Nama, Kelas, JK)</li>
            <li>Kelas harus sesuai dengan kelas yang ada di sistem (contoh: X-1, XI IPA 1, XII IPS 2)</li>
            <li>Jenis kelamin harus diisi dengan <strong>L</strong> (Laki-laki) atau <strong>P</strong> (Perempuan)</li>
            <li>NIS tidak boleh duplikat dengan data yang sudah ada</li>
            <li>Upload file Excel yang sudah diisi</li>
        </ol>
    </div>

    <div style="margin-bottom: 2rem;">
        <a href="<?= base_url('/master/students/template') ?>" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            📄 Download Template Excel
        </a>
    </div>

    <?php if (session()->getFlashdata('import_errors')): ?>
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.5rem;">
            <h3 style="margin-bottom: 0.5rem; color: #991b1b;">⚠️ Error Import</h3>
            <ul style="margin: 0.5rem 0; padding-left: 1.5rem; color: #7f1d1d;">
                <?php foreach (session()->getFlashdata('import_errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/master/students/import/process') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="excel_file">
                Pilih File Excel <span style="color: red;">*</span>
            </label>
            <input type="file"
                   id="excel_file"
                   name="excel_file"
                   accept=".xlsx,.xls"
                   required
                   style="padding: 0.75rem; border: 2px dashed var(--border-color); border-radius: 0.5rem; width: 100%; cursor: pointer;">
            <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                Format yang didukung: .xlsx, .xls (maksimal 5MB)
            </small>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn-primary" style="flex: 0 0 auto;">
                📤 Upload & Import
            </button>
            <a href="<?= base_url('/master/students') ?>" class="btn-secondary" style="flex: 0 0 auto;">
                ← Kembali
            </a>
        </div>
    </form>

    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
        <h3 style="margin-bottom: 1rem;">💡 Tips Import</h3>
        <ul style="color: var(--text-secondary); padding-left: 1.5rem;">
            <li>Pastikan tidak ada baris kosong di tengah-tengah data</li>
            <li>NISN bersifat opsional, boleh dikosongkan</li>
            <li>Sistem akan mengabaikan baris yang tidak valid dan melanjutkan import baris berikutnya</li>
            <li>Setelah import, Anda akan mendapatkan laporan berapa siswa yang berhasil dan gagal diimport</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
