<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="dashboard-cards fade-in">
    <div class="dashboard-card glass-card hover-lift fade-in-delay-1 micro-interact" onclick="window.location.href='<?= base_url('/master/classes') ?>'" style="cursor: pointer;">
        <h3 class="gradient-text">📚 Data Kelas</h3>
        <p>Kelola data kelas</p>
    </div>
    <div class="dashboard-card glass-card hover-lift fade-in-delay-2 micro-interact" onclick="window.location.href='<?= base_url('/master/students') ?>'" style="cursor: pointer;">
        <h3 class="gradient-text">👨‍🎓 Data Siswa</h3>
        <p>Kelola data siswa</p>
    </div>
    <div class="dashboard-card glass-card hover-lift fade-in-delay-3 micro-interact" onclick="window.location.href='<?= base_url('/master/teachers') ?>'" style="cursor: pointer;">
        <h3 class="gradient-text">👨‍🏫 Data Guru</h3>
        <p>Kelola data guru</p>
    </div>
    <div class="dashboard-card glass-card hover-lift micro-interact" onclick="window.location.href='<?= base_url('/master/subjects') ?>'" style="cursor: pointer;">
        <h3 class="gradient-text">📖 Mata Pelajaran</h3>
        <p>Kelola mata pelajaran</p>
    </div>
</div>

<?= $this->endSection() ?>
