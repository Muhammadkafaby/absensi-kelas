// Admin recap functionality
document.addEventListener("DOMContentLoaded", function () {
  const user = auth.getUser();
  if (!user || user.role !== "admin") {
    window.location.href = "login.html";
    return;
  }

  // Generate navigation
  generateNavigation("admin");

  // Set default date to today
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("absentDateFilter").value = today;

  // Load initial data
  loadDailyRecap();

  // Date filter change
  document
    .getElementById("absentDateFilter")
    .addEventListener("change", loadDailyRecap);

  // Download buttons
  document
    .getElementById("downloadCsvBtn")
    .addEventListener("click", downloadCsv);
  document.getElementById("printPdfBtn").addEventListener("click", printPdf);
});

function generateNavigation(role) {
  const navEl = document.getElementById("nav");
  if (!navEl) return;

  let navItems = [];

  if (role === "admin") {
    navItems = [
      { text: "Dashboard", action: "dashboard", active: false },
      { text: "Rekap Harian", action: "recap-admin", active: false },
      { text: "Master Data", action: "master-data", active: false },
      { text: "Keluar", action: "logout", active: false },
    ];
  }

  // Set active based on current page
  const currentPage = getCurrentPage();
  navItems.forEach((item) => {
    if (item.action === currentPage) {
      item.active = true;
    }
  });

  const navHtml = `
        <ul>
            ${navItems
              .map(
                (item) => `
                <li>
                    <a href="#" 
                       class="${item.active ? "active" : ""}" 
                       data-action="${item.action}">
                        ${item.text}
                    </a>
                </li>
            `
              )
              .join("")}
        </ul>
    `;

  navEl.innerHTML = navHtml;

  // Add click handlers
  navEl.addEventListener("click", function (e) {
    if (e.target.tagName === "A") {
      e.preventDefault();
      const action = e.target.dataset.action;
      if (action === "logout") {
        auth.logout();
      } else {
        navigateToPage(action);
      }
    }
  });
}

function getCurrentPage() {
  const path = window.location.pathname;
  if (path.includes("recap-admin")) return "recap-admin";
  if (path.includes("master-data")) return "master-data";
  return "dashboard";
}

function navigateToPage(action) {
  const pageMap = {
    dashboard: "dashboard.html",
    "recap-admin": "recap-admin.html",
    "master-data": "master-data.html",
  };

  if (pageMap[action]) {
    window.location.href = pageMap[action];
  }
}

async function loadDailyRecap() {
  const date = document.getElementById("absentDateFilter").value;

  try {
    // Mock data - replace with actual API calls
    const absentData = await loadAbsentStudents(date);
    const classRecapData = await loadClassRecap(date);

    renderAbsentTable(absentData);
    renderClassRecapTable(classRecapData);
    updateMetrics(absentData, classRecapData);
  } catch (error) {
    console.error("Error loading daily recap:", error);
    auth.showNotification("Gagal memuat data rekap harian", "error");
  }
}

async function loadAbsentStudents(date) {
  // Mock API call - replace with actual endpoint
  // GET …/exec?mode=alfa_harian&tanggal=DD/MM/YYYY&token=JWT_TOKEN

  return new Promise((resolve) => {
    setTimeout(() => {
      const mockData = generateMockAbsentStudents(date);
      resolve(mockData);
    }, 500);
  });
}

async function loadClassRecap(date) {
  // Mock API call - replace with actual endpoint
  // GET …/exec?mode=rekap_kelas_harian&tanggal=DD/MM/YYYY&token=JWT_TOKEN

  return new Promise((resolve) => {
    setTimeout(() => {
      const mockData = generateMockClassRecap(date);
      resolve(mockData);
    }, 500);
  });
}

function generateMockAbsentStudents(date) {
  const absentStudents = [];
  const count = Math.floor(Math.random() * 10) + 5; // 5-15 absent students

  const classes = [
    "X-1",
    "X-2",
    "X-3",
    "X-4",
    "XI-1",
    "XI-2",
    "XI-3",
    "XI-4",
    "XII-1",
    "XII-2",
    "XII-3",
    "XII-4",
  ];
  const subjects = [
    "Matematika",
    "Bahasa Indonesia",
    "Bahasa Inggris",
    "Fisika",
    "Kimia",
    "Biologi",
    "Sejarah",
    "Geografi",
  ];
  const teachers = [
    "Pak Ahmad",
    "Bu Siti",
    "Pak Budi",
    "Bu Rina",
    "Pak Dedi",
    "Bu Maya",
  ];

  for (let i = 1; i <= count; i++) {
    const className = classes[Math.floor(Math.random() * classes.length)];
    absentStudents.push({
      no: i,
      nis: `2024${className.replace("-", "")}${String(
        Math.floor(Math.random() * 40) + 1
      ).padStart(2, "0")}`,
      nama: `Siswa ${className} ${i}`,
      kelas: className,
      mapel: subjects[Math.floor(Math.random() * subjects.length)],
      jam: Math.floor(Math.random() * 10) + 1,
      guru: teachers[Math.floor(Math.random() * teachers.length)],
      catatan: Math.random() > 0.5 ? "Sakit" : "",
    });
  }

  return absentStudents;
}

function generateMockClassRecap(date) {
  const classes = [
    "X-1",
    "X-2",
    "X-3",
    "X-4",
    "XI-1",
    "XI-2",
    "XI-3",
    "XI-4",
    "XII-1",
    "XII-2",
    "XII-3",
    "XII-4",
  ];
  const classRecap = [];

  classes.forEach((className) => {
    const totalStudents = Math.floor(Math.random() * 10) + 25; // 25-35 students
    const h = Math.floor(Math.random() * 5) + totalStudents - 10; // Most present
    const a = Math.floor(Math.random() * 5); // 0-4 absent
    const s = Math.floor(Math.random() * 3); // 0-2 sick
    const i = Math.floor(Math.random() * 3); // 0-2 permit
    const total = h + a + s + i;
    const percent = Math.round((h / total) * 100);

    classRecap.push({
      kelas: className,
      h: h,
      a: a,
      s: s,
      i: i,
      percent: percent,
    });
  });

  return classRecap;
}

function renderAbsentTable(absentStudents) {
  const tbody = document.getElementById("absentTableBody");

  const rowsHtml = absentStudents
    .map(
      (student) => `
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
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function renderClassRecapTable(classRecap) {
  const tbody = document.getElementById("classRecapTableBody");

  const rowsHtml = classRecap
    .map(
      (recap) => `
        <tr>
            <td>${recap.kelas}</td>
            <td class="status-H">${recap.h}</td>
            <td class="status-A">${recap.a}</td>
            <td class="status-S">${recap.s}</td>
            <td class="status-I">${recap.i}</td>
            <td>${recap.percent}%</td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function updateMetrics(absentStudents, classRecap) {
  // Calculate totals from class recap
  let totalPresent = 0;
  let totalAbsent = 0;
  let totalPermit = 0;
  let totalSick = 0;
  let totalStudents = 0;

  classRecap.forEach((recap) => {
    totalPresent += recap.h;
    totalAbsent += recap.a;
    totalPermit += recap.i;
    totalSick += recap.s;
    totalStudents += recap.h + recap.a + recap.s + recap.i;
  });

  const attendancePercent =
    totalStudents > 0 ? Math.round((totalPresent / totalStudents) * 100) : 0;

  document.getElementById("totalPresent").textContent = totalPresent;
  document.getElementById("totalAbsent").textContent = totalAbsent;
  document.getElementById("totalPermit").textContent = totalPermit;
  document.getElementById("totalSick").textContent = totalSick;
  document.getElementById(
    "attendancePercent"
  ).textContent = `${attendancePercent}%`;
}

function downloadCsv() {
  const absentData = getAbsentTableData();
  const classRecapData = getClassRecapTableData();

  if (absentData.length === 0 && classRecapData.length === 0) {
    auth.showNotification("Tidak ada data untuk diunduh", "error");
    return;
  }

  let csvContent = "Alfa Hari Ini\n";
  csvContent +=
    ["No", "NIS", "Nama", "Kelas", "Mapel", "Jam", "Guru", "Catatan"].join(
      ","
    ) + "\n";
  csvContent += absentData
    .map((row) =>
      [
        row.no,
        row.nis,
        row.nama,
        row.kelas,
        row.mapel,
        row.jam,
        row.guru,
        row.catatan,
      ].join(",")
    )
    .join("\n");

  csvContent += "\n\nRekap per Kelas\n";
  csvContent += ["Kelas", "H", "A", "S", "I", "% Hadir"].join(",") + "\n";
  csvContent += classRecapData
    .map((row) =>
      [row.kelas, row.h, row.a, row.s, row.i, row.percent].join(",")
    )
    .join("\n");

  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `rekap-harian-${new Date().toISOString().split("T")[0]}.csv`;
  link.click();
}

function printPdf() {
  // Simple print functionality - in real app, use a PDF library
  window.print();
}

function getAbsentTableData() {
  const rows = document.querySelectorAll("#absentTableBody tr");
  const data = [];

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    data.push({
      no: cells[0].textContent,
      nis: cells[1].textContent,
      nama: cells[2].textContent,
      kelas: cells[3].textContent,
      mapel: cells[4].textContent,
      jam: cells[5].textContent,
      guru: cells[6].textContent,
      catatan: cells[7].textContent,
    });
  });

  return data;
}

function getClassRecapTableData() {
  const rows = document.querySelectorAll("#classRecapTableBody tr");
  const data = [];

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    data.push({
      kelas: cells[0].textContent,
      h: cells[1].textContent,
      a: cells[2].textContent,
      s: cells[3].textContent,
      i: cells[4].textContent,
      percent: cells[5].textContent.replace("%", ""),
    });
  });

  return data;
}
