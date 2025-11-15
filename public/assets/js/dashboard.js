// Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    const user = auth.getUser();
    if (!user) return;

    // Generate navigation based on role
    generateNavigation(user.role);

    // Generate dashboard cards based on role
    generateDashboardCards(user.role);

    // Add click handlers for dashboard cards
    document.addEventListener('click', function(e) {
        if (e.target.closest('.dashboard-card')) {
            const card = e.target.closest('.dashboard-card');
            const action = card.dataset.action;
            navigateToPage(action);
        }
    });
});

// Import functions from recap-admin.js
async function loadAbsentStudents(date) {
    // Mock API call - replace with actual endpoint
    // GET …/exec?mode=alfa_harian&tanggal=DD/MM/YYYY&token=JWT_TOKEN

    return new Promise(resolve => {
        setTimeout(() => {
            const mockData = generateMockAbsentStudents(date);
            resolve(mockData);
        }, 500);
    });
}

function generateMockAbsentStudents(date) {
    const absentStudents = [];
    const count = Math.floor(Math.random() * 10) + 5; // 5-15 absent students

    const classes = ['X-1', 'X-2', 'X-3', 'X-4', 'XI-1', 'XI-2', 'XI-3', 'XI-4', 'XII-1', 'XII-2', 'XII-3', 'XII-4'];
    const subjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Biologi', 'Sejarah', 'Geografi'];
    const teachers = ['Pak Ahmad', 'Bu Siti', 'Pak Budi', 'Bu Rina', 'Pak Dedi', 'Bu Maya'];

    for (let i = 1; i <= count; i++) {
        const className = classes[Math.floor(Math.random() * classes.length)];
        absentStudents.push({
            no: i,
            nis: `2024${className.replace('-', '')}${String(Math.floor(Math.random() * 40) + 1).padStart(2, '0')}`,
            nama: `Siswa ${className} ${i}`,
            kelas: className,
            mapel: subjects[Math.floor(Math.random() * subjects.length)],
            jam: Math.floor(Math.random() * 10) + 1,
            guru: teachers[Math.floor(Math.random() * teachers.length)],
            catatan: Math.random() > 0.5 ? 'Sakit' : ''
        });
    }

    return absentStudents;
}

function generateNavigation(role) {
    const navEl = document.getElementById('nav');
    if (!navEl) return;

    let navItems = [];

    if (role === 'guru') {
        navItems = [
            { text: 'Absensi', action: 'attendance-teacher', active: false },
            { text: 'Rekap Saya', action: 'recap-teacher', active: false },
            { text: 'Keluar', action: 'logout', active: false }
        ];
    } else if (role === 'admin') {
        navItems = [
            { text: 'Rekap Harian', action: 'recap-admin', active: false },
            { text: 'Master Data', action: 'master-data', active: false },
            { text: 'Keluar', action: 'logout', active: false }
        ];
    }

    // Set active based on current page
    const currentPage = getCurrentPage();
    navItems.forEach(item => {
        if (item.action === currentPage) {
            item.active = true;
        }
    });

    const navHtml = `
        <ul>
            ${navItems.map(item => `
                <li>
                    <a href="#" 
                       class="${item.active ? 'active' : ''}" 
                       data-action="${item.action}">
                        ${item.text}
                    </a>
                </li>
            `).join('')}
        </ul>
    `;

    navEl.innerHTML = navHtml;

    // Add click handlers
    navEl.addEventListener('click', function(e) {
        if (e.target.tagName === 'A') {
            e.preventDefault();
            const action = e.target.dataset.action;
            if (action === 'logout') {
                auth.logout();
            } else {
                navigateToPage(action);
            }
        }
    });
}

function generateDashboardCards(role) {
    const cardsEl = document.getElementById('dashboardCards');
    if (!cardsEl) return;

    let cards = [];

    if (role === 'guru') {
        cards = [
            {
                title: 'Input Absensi',
                description: 'Masukkan data kehadiran siswa',
                action: 'attendance-teacher',
                icon: '📝'
            },
            {
                title: 'Rekap Saya',
                description: 'Lihat rekapitulasi absensi Anda',
                action: 'recap-teacher',
                icon: '📊'
            }
        ];
    } else if (role === 'admin') {
        cards = [
            {
                title: 'Rekap Harian',
                description: 'Lihat rekap absensi hari ini',
                action: 'recap-admin',
                icon: '📈'
            },
            {
                title: 'Master Data',
                description: 'Kelola data siswa, guru, dan kelas',
                action: 'master-data',
                icon: '⚙️'
            }
        ];

        // Show absent students section for admin
        const adminAbsentSection = document.getElementById('adminAbsentSection');
        if (adminAbsentSection) {
            adminAbsentSection.style.display = 'block';
            loadAbsentStudentsForDashboard();
        }
    }

    const cardsHtml = cards.map(card => `
        <div class="dashboard-card" data-action="${card.action}">
            <div style="font-size: 2rem; margin-bottom: 1rem;">${card.icon}</div>
            <h3>${card.title}</h3>
            <p>${card.description}</p>
        </div>
    `).join('');

    cardsEl.innerHTML = cardsHtml;
}

function navigateToPage(action) {
    const pageMap = {
        'attendance-teacher': 'attendance-teacher.html',
        'recap-teacher': 'recap-teacher.html',
        'recap-admin': 'recap-admin.html',
        'master-data': 'master-data.html'
    };

    if (pageMap[action]) {
        window.location.href = pageMap[action];
    }
}

function getCurrentPage() {
    const path = window.location.pathname;
    if (path.includes('attendance-teacher')) return 'attendance-teacher';
    if (path.includes('recap-teacher')) return 'recap-teacher';
    if (path.includes('recap-admin')) return 'recap-admin';
    if (path.includes('master-data')) return 'master-data';
    return 'dashboard';
}

async function loadAbsentStudentsForDashboard() {
    const today = new Date().toISOString().split('T')[0];

    try {
        // Use the same function from recap-admin.js
        const absentData = await loadAbsentStudents(today);
        renderDashboardAbsentTable(absentData);
    } catch (error) {
        console.error('Error loading absent students for dashboard:', error);
        auth.showNotification('Gagal memuat data siswa alfa', 'error');
    }
}

function renderDashboardAbsentTable(absentStudents) {
    const tbody = document.getElementById('dashboardAbsentTableBody');

    const rowsHtml = absentStudents.map(student => `
        <tr>
            <td>${student.no}</td>
            <td>${student.nis}</td>
            <td>${student.nama}</td>
            <td>${student.kelas}</td>
            <td>${student.mapel}</td>
            <td>${student.jam}</td>
            <td>${student.guru}</td>
            <td>${student.catatan}</td>
        </tr>
    `).join('');

    tbody.innerHTML = rowsHtml;
}