<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card glass-card fade-in">
    <h2 class="gradient-text mb-2">📥 Import Data Siswa dari Excel</h2>

    <div class="alert alert-info mb-2">
        <h3 class="mb-1">📋 Instruksi Import</h3>
        <ol class="pl-2">
            <li>Download template Excel terlebih dahulu</li>
            <li>Isi data siswa sesuai format template (NIS, NISN, Nama, Kelas, JK)</li>
            <li>Kelas harus sesuai dengan kelas yang ada di sistem (contoh: X-1, XI IPA 1, XII IPS 2)</li>
            <li>Jenis kelamin harus diisi dengan <strong>L</strong> (Laki-laki) atau <strong>P</strong> (Perempuan)</li>
            <li>NIS tidak boleh duplikat dengan data yang sudah ada</li>
            <li>Upload file Excel yang sudah diisi</li>
        </ol>
    </div>

    <div class="mb-2">
        <a href="<?= base_url('/master/students/template') ?>" class="btn-secondary micro-interact">
            📄 Download Template Excel
        </a>
    </div>

    <?php if (session()->getFlashdata('import_errors')): ?>
        <div class="alert alert-danger mb-2">
            <h3 class="mb-1">⚠️ Error Import</h3>
            <ul class="pl-2">
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
                Pilih File Excel <span class="text-danger">*</span>
            </label>
            <input type="file"
                   id="excel_file"
                   name="excel_file"
                   accept=".xlsx,.xls"
                   required
                   class="file-input">
            <small class="text-secondary">
                Format yang didukung: .xlsx, .xls (maksimal 5MB)
            </small>
        </div>

        <div class="button-group mt-2">
            <button type="submit" class="btn-primary ripple">
                📤 Upload & Import
            </button>
            <a href="<?= base_url('/master/students') ?>" class="btn-secondary">
                ← Kembali
            </a>
        </div>
    </form>

    <div class="mt-2 pt-2" style="border-top: 1px solid var(--border-color);">
        <h3 class="mb-1">💡 Tips Import</h3>
        <ul class="text-secondary pl-2">
            <li>Pastikan tidak ada baris kosong di tengah-tengah data</li>
            <li>NISN bersifat opsional, boleh dikosongkan</li>
            <li>Sistem akan mengabaikan baris yang tidak valid dan melanjutkan import baris berikutnya</li>
            <li>Setelah import, Anda akan mendapatkan laporan berapa siswa yang berhasil dan gagal diimport</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>
