<?= $this->extend('layouts/enterprise') ?>

<?= $this->section('content') ?>

<!-- Welcome Section -->
<div class="enterprise-card mb-6">
    <div style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
            📚
        </div>
        <div style="flex: 1;">
            <h1 style="font-size: 1.875rem; font-weight: 700; margin-bottom: 0.5rem;">Master Data</h1>
            <p style="color: var(--enterprise-text-secondary); margin: 0;">Kelola data kelas, siswa, guru, dan mata pelajaran</p>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid mb-6">
    <div class="metric-card">
        <div class="metric-icon">🏫</div>
        <div class="metric-label">Total Kelas</div>
        <div class="metric-value" data-count-up="<?= $total_classes ?? 0 ?>"><?= $total_classes ?? 0 ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">👥</div>
        <div class="metric-label">Total Siswa</div>
        <div class="metric-value" data-count-up="<?= $total_students ?? 0 ?>"><?= $total_students ?? 0 ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">👨‍🏫</div>
        <div class="metric-label">Total Guru</div>
        <div class="metric-value" data-count-up="<?= $total_teachers ?? 0 ?>"><?= $total_teachers ?? 0 ?></div>
    </div>

    <div class="metric-card">
        <div class="metric-icon">📖</div>
        <div class="metric-label">Mata Pelajaran</div>
        <div class="metric-value" data-count-up="<?= $total_subjects ?? 0 ?>"><?= $total_subjects ?? 0 ?></div>
    </div>
</div>

<!-- Master Data Menu Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
    <!-- Kelas -->
    <a href="<?= base_url('/master/classes') ?>" style="text-decoration: none;">
        <div class="enterprise-card" style="height: 100%; cursor: pointer; transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-primary-xl)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; flex-shrink: 0;">
                    🏫
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Kelas</h3>
                    <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Kelola data kelas</p>
                </div>
            </div>
            <div style="padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--enterprise-text-secondary);">Total kelas</span>
                    <span style="font-size: 1.5rem; font-weight: 700; color: var(--enterprise-primary-600);"><?= $total_classes ?? 0 ?></span>
                </div>
            </div>
        </div>
    </a>

    <!-- Siswa -->
    <a href="<?= base_url('/master/students') ?>" style="text-decoration: none;">
        <div class="enterprise-card" style="height: 100%; cursor: pointer; transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-primary-xl)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; flex-shrink: 0;">
                    👥
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Siswa</h3>
                    <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Kelola data siswa</p>
                </div>
            </div>
            <div style="padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--enterprise-text-secondary);">Siswa aktif</span>
                    <span style="font-size: 1.5rem; font-weight: 700; color: #3b82f6;"><?= $total_students ?? 0 ?></span>
                </div>
            </div>
        </div>
    </a>

    <!-- Guru -->
    <a href="<?= base_url('/master/teachers') ?>" style="text-decoration: none;">
        <div class="enterprise-card" style="height: 100%; cursor: pointer; transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-primary-xl)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; flex-shrink: 0;">
                    👨‍🏫
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Guru</h3>
                    <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Kelola data guru</p>
                </div>
            </div>
            <div style="padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--enterprise-text-secondary);">Total guru</span>
                    <span style="font-size: 1.5rem; font-weight: 700; color: #f59e0b;"><?= $total_teachers ?? 0 ?></span>
                </div>
            </div>
        </div>
    </a>

    <!-- Mata Pelajaran -->
    <a href="<?= base_url('/master/subjects') ?>" style="text-decoration: none;">
        <div class="enterprise-card" style="height: 100%; cursor: pointer; transition: all 0.3s ease;"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-primary-xl)'"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 56px; height: 56px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: white; flex-shrink: 0;">
                    📖
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--enterprise-text-primary);">Mata Pelajaran</h3>
                    <p style="font-size: 0.875rem; color: var(--enterprise-text-tertiary); margin: 0;">Kelola mapel</p>
                </div>
            </div>
            <div style="padding: 1rem; background: var(--enterprise-bg-secondary); border-radius: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--enterprise-text-secondary);">Total mapel</span>
                    <span style="font-size: 1.5rem; font-weight: 700; color: #8b5cf6;"><?= $total_subjects ?? 0 ?></span>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Quick Actions -->
<div class="enterprise-card" style="margin-top: 2rem;">
    <div class="card-header">
        <h2 class="card-title">Aksi Cepat</h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
        <button onclick="window.location.href='<?= base_url('/master/classes/create') ?>'" class="btn-enterprise btn-secondary" style="justify-content: flex-start;">
            <span>➕</span>
            <span>Tambah Kelas Baru</span>
        </button>

        <button onclick="window.location.href='<?= base_url('/master/students/create') ?>'" class="btn-enterprise btn-secondary" style="justify-content: flex-start;">
            <span>➕</span>
            <span>Tambah Siswa Baru</span>
        </button>

        <button onclick="window.location.href='<?= base_url('/master/teachers/create') ?>'" class="btn-enterprise btn-secondary" style="justify-content: flex-start;">
            <span>➕</span>
            <span>Tambah Guru Baru</span>
        </button>

        <button onclick="window.location.href='<?= base_url('/master/subjects/create') ?>'" class="btn-enterprise btn-secondary" style="justify-content: flex-start;">
            <span>➕</span>
            <span>Tambah Mapel Baru</span>
        </button>
    </div>
</div>

<?= $this->endSection() ?>
