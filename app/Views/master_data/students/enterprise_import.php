<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <a href="<?= base_url('/master/students') ?>" style="width: 48px; height: 48px; background: var(--enterprise-bg-secondary); border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--enterprise-text-primary); transition: all 0.2s;" onmouseover="this.style.background='var(--enterprise-bg-tertiary)'" onmouseout="this.style.background='var(--enterprise-bg-secondary)'">
            <span style="font-size: 1.25rem;">←</span>
        </a>
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            📥
        </div>
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Import Data Siswa</h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">Import siswa dari file Excel</p>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="enterprise-card mb-6">
    <div class="card-header">
        <h2 class="card-title">📋 Petunjuk Import</h2>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div style="padding: 1rem; background: var(--enterprise-info-50); border-radius: 12px; border-left: 4px solid var(--enterprise-info-500);">
            <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--enterprise-info-600);">Format Excel yang Didukung:</div>
            <ul style="margin: 0; padding-left: 1.5rem; color: var(--enterprise-text-secondary);">
                <li>File format: .xlsx atau .xls</li>
                <li>Kolom yang diperlukan: NIS, NISN (opsional), Nama, Kelas, Jenis Kelamin</li>
                <li>Baris pertama adalah header (akan diabaikan)</li>
                <li>Jenis Kelamin: gunakan L untuk Laki-laki, P untuk Perempuan</li>
            </ul>
        </div>

        <div style="padding: 1rem; background: var(--enterprise-warning-50); border-radius: 12px; border-left: 4px solid var(--enterprise-warning-500);">
            <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--enterprise-warning-600);">⚠️ Perhatian:</div>
            <ul style="margin: 0; padding-left: 1.5rem; color: var(--enterprise-text-secondary);">
                <li>Data dengan NIS yang sudah ada akan dilewati</li>
                <li>Nama kelas harus sudah terdaftar di sistem</li>
                <li>Pastikan format data sudah benar sebelum import</li>
            </ul>
        </div>

        <div style="padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 12px;">
            <div style="font-weight: 600; margin-bottom: 0.5rem;">Contoh Format Excel:</div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: var(--enterprise-bg-tertiary);">
                        <tr>
                            <th style="padding: 0.75rem; border: 1px solid var(--enterprise-border); text-align: left;">NIS</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--enterprise-border); text-align: left;">NISN</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--enterprise-border); text-align: left;">Nama</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--enterprise-border); text-align: left;">Kelas</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--enterprise-border); text-align: left;">JK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">12345</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">0012345678</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">Ahmad Fadli</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">X-1</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">L</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">12346</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">0012345679</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">Siti Nurhaliza</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">X-2</td>
                            <td style="padding: 0.75rem; border: 1px solid var(--enterprise-border);">P</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Upload Form -->
<div class="enterprise-card" style="max-width: 700px;">
    <div class="card-header">
        <h2 class="card-title">Upload File Excel</h2>
    </div>

    <form action="<?= base_url('/master/students/import/process') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?php if (session()->getFlashdata('import_errors')): ?>
            <div class="enterprise-alert alert-warning mb-4">
                <div style="font-weight: 600; margin-bottom: 0.5rem;">⚠️ Beberapa data gagal diimport:</div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.875rem;">
                        <?php foreach (session()->getFlashdata('import_errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- File Input -->
            <div class="enterprise-form-group">
                <label for="excel_file" class="enterprise-label enterprise-label-required">Pilih File Excel</label>
                <input type="file" id="excel_file" name="excel_file" class="enterprise-input" accept=".xlsx,.xls" required>
                <p class="form-helper-text">Format: .xlsx atau .xls (Max: 2MB)</p>
            </div>

            <!-- Download Template -->
            <div style="padding: 1rem; background: var(--enterprise-primary-50); border-radius: 12px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-primary-600);">Template Excel</div>
                    <div style="font-size: 0.875rem; color: var(--enterprise-text-secondary);">Download template untuk memudahkan import</div>
                </div>
                <a href="<?= base_url('/master/students/template') ?>" class="btn-enterprise btn-secondary btn-sm">
                    <span>📥</span>
                    <span>Download</span>
                </a>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--enterprise-border);">
                <a href="<?= base_url('/master/students') ?>" class="btn-enterprise btn-secondary">
                    <span>✕</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="btn-enterprise btn-primary">
                    <span>📤</span>
                    <span>Upload & Import</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
