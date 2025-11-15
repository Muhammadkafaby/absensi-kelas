<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<div style="max-width: 900px;">
    <!-- Profile Header -->
    <div class="enterprise-card mb-6">
        <div style="display: flex; align-items: center; gap: 2rem;">
            <div style="width: 96px; height: 96px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 24px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2.5rem; font-weight: 700; flex-shrink: 0;">
                <?= strtoupper(substr(session()->get('name'), 0, 2)) ?>
            </div>
            <div style="flex: 1;">
                <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;"><?= esc(session()->get('name')) ?></h1>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                    <span class="enterprise-badge badge-success" style="text-transform: uppercase;">
                        <?= esc(session()->get('role')) ?>
                    </span>
                    <span style="color: var(--enterprise-text-secondary); font-size: 0.875rem;">
                        👤 <?= esc(session()->get('username')) ?>
                    </span>
                </div>
                <p style="color: var(--enterprise-text-tertiary); margin: 0;">
                    Kelola informasi profil dan keamanan akun Anda
                </p>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="enterprise-card mb-6">
        <div class="card-header">
            <h2 class="card-title">Informasi Profil</h2>
        </div>

        <form action="<?= base_url('/user/profile/update') ?>" method="POST">
            <?= csrf_field() ?>

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="enterprise-form-group">
                    <label class="enterprise-label">Username</label>
                    <input type="text" value="<?= esc(session()->get('username')) ?>" class="enterprise-input" disabled style="opacity: 0.6;">
                    <p class="form-helper-text">Username tidak dapat diubah</p>
                </div>

                <div class="enterprise-form-group">
                    <label class="enterprise-label enterprise-label-required">Nama Lengkap</label>
                    <input type="text" name="name" value="<?= esc(session()->get('name')) ?>" class="enterprise-input" required>
                </div>

                <div class="enterprise-form-group">
                    <label class="enterprise-label">Email</label>
                    <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="enterprise-input" placeholder="email@example.com">
                </div>

                <div style="padding-top: 1rem; border-top: 1px solid var(--enterprise-border);">
                    <button type="submit" class="btn-enterprise btn-primary">
                        <span>💾</span>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="enterprise-card mb-6">
        <div class="card-header">
            <h2 class="card-title">Ubah Password</h2>
            <span class="enterprise-badge badge-warning">Keamanan</span>
        </div>

        <form action="<?= base_url('/user/password/update') ?>" method="POST">
            <?= csrf_field() ?>

            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="enterprise-form-group">
                    <label class="enterprise-label enterprise-label-required">Password Lama</label>
                    <input type="password" name="old_password" class="enterprise-input" required>
                </div>

                <div class="enterprise-form-group">
                    <label class="enterprise-label enterprise-label-required">Password Baru</label>
                    <input type="password" name="new_password" class="enterprise-input" required minlength="6">
                    <p class="form-helper-text">Minimal 6 karakter</p>
                </div>

                <div class="enterprise-form-group">
                    <label class="enterprise-label enterprise-label-required">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="enterprise-input" required minlength="6">
                </div>

                <div style="padding-top: 1rem; border-top: 1px solid var(--enterprise-border);">
                    <button type="submit" class="btn-enterprise btn-primary">
                        <span>🔒</span>
                        <span>Ubah Password</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Account Info -->
    <div class="enterprise-card">
        <div class="card-header">
            <h2 class="card-title">Informasi Akun</h2>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <span style="color: var(--enterprise-text-secondary);">ID Pengguna</span>
                <strong><?= session()->get('user_id') ?></strong>
            </div>

            <div style="display: flex; justify-content: space-between; padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <span style="color: var(--enterprise-text-secondary);">Role</span>
                <strong><?= ucfirst(session()->get('role')) ?></strong>
            </div>

            <div style="display: flex; justify-content: space-between; padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <span style="color: var(--enterprise-text-secondary);">Status</span>
                <span class="enterprise-badge badge-success">
                    <span class="status-dot success"></span>
                    Aktif
                </span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
