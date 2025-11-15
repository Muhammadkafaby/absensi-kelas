<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="dashboard-cards">
    <div class="dashboard-card" onclick="window.location.href='<?= base_url('/master/classes') ?>'">
        <h3>Data Kelas</h3>
        <p>Kelola data kelas</p>
    </div>
    <div class="dashboard-card" onclick="window.location.href='<?= base_url('/master/students') ?>'">
        <h3>Data Siswa</h3>
        <p>Kelola data siswa</p>
    </div>
    <div class="dashboard-card" onclick="window.location.href='<?= base_url('/master/teachers') ?>'">
        <h3>Data Guru</h3>
        <p>Kelola data guru</p>
    </div>
    <div class="dashboard-card" onclick="window.location.href='<?= base_url('/master/subjects') ?>'">
        <h3>Mata Pelajaran</h3>
        <p>Kelola mata pelajaran</p>
    </div>
</div>

<?= $this->endSection() ?>
