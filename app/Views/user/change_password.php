<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Ganti Password</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem;">
        Untuk keamanan akun Anda, pastikan password baru minimal 8 karakter.
    </p>

    <form action="<?= base_url('/user/password/update') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="old_password">Password Lama *</label>
            <input type="password" id="old_password" name="old_password" required autofocus>
        </div>

        <div class="form-group">
            <label for="new_password">Password Baru *</label>
            <input type="password" id="new_password" name="new_password" required
                   minlength="8" placeholder="Minimal 8 karakter">
            <small style="color: var(--text-secondary); font-size: 0.875rem;">
                Gunakan kombinasi huruf, angka, dan simbol untuk password yang lebih kuat
            </small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password Baru *</label>
            <input type="password" id="confirm_password" name="confirm_password" required
                   minlength="8" placeholder="Ketik ulang password baru">
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary">Ubah Password</button>
            <a href="<?= base_url('/dashboard') ?>" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
// Password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = this.value;

    if (newPass !== confirmPass) {
        this.setCustomValidity('Password tidak sama');
    } else {
        this.setCustomValidity('');
    }
});

// Show password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[@$!%*#?&]+/)) strength++;

    const colors = ['#ef4444', '#f59e0b', '#10b981'];
    const labels = ['Lemah', 'Sedang', 'Kuat'];

    const strengthIndex = Math.min(Math.floor((strength - 1) / 2), 2);

    // You can add a visual indicator here if needed
});
</script>
<?= $this->endSection() ?>
