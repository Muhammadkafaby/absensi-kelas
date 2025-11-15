<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Profil Saya</h2>

    <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--border-radius-lg); margin-bottom: 2rem;">
        <div style="display: grid; gap: 1rem;">
            <div>
                <strong style="color: var(--text-secondary); font-size: 0.875rem;">Username</strong>
                <p style="margin: 0.25rem 0 0 0; font-size: 1.125rem;"><?= esc($user['username']) ?></p>
            </div>
            <div>
                <strong style="color: var(--text-secondary); font-size: 0.875rem;">Role</strong>
                <p style="margin: 0.25rem 0 0 0;">
                    <span class="role-badge"><?= strtoupper(esc($user['role'])) ?></span>
                </p>
            </div>
            <?php if ($user['role'] == 'guru' && !empty($user['teacher_name'])): ?>
                <div>
                    <strong style="color: var(--text-secondary); font-size: 0.875rem;">NIP</strong>
                    <p style="margin: 0.25rem 0 0 0;"><?= esc($user['nip'] ?? '-') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?= base_url('/user/profile/update') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Nama Lengkap *</label>
            <input type="text" id="name" name="name"
                   value="<?= old('name', $user['name']) ?>"
                   required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= old('email', $user['email'] ?? '') ?>"
                   placeholder="email@example.com">
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="<?= base_url('/user/password/change') ?>" class="btn-secondary">Ganti Password</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
