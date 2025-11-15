// Master data management functionality
document.addEventListener("DOMContentLoaded", function () {
  const user = auth.getUser();
  if (!user || user.role !== "admin") {
    window.location.href = "login.html";
    return;
  }

  // Generate navigation
  generateNavigation("admin");

  // Load initial data
  loadAllMasterData();

  // Import/Export buttons
  document
    .getElementById("importStudentsBtn")
    .addEventListener("click", () => importCsv("students"));
  document
    .getElementById("exportStudentsBtn")
    .addEventListener("click", () => exportCsv("students"));
  document
    .getElementById("importTeachersBtn")
    .addEventListener("click", () => importCsv("teachers"));
  document
    .getElementById("exportTeachersBtn")
    .addEventListener("click", () => exportCsv("teachers"));
  document.getElementById("syncBtn").addEventListener("click", syncData);
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

async function loadAllMasterData() {
  try {
    const [students, classes, teachers, subjects] = await Promise.all([
      loadStudents(),
      loadClasses(),
      loadTeachers(),
      loadSubjects(),
    ]);

    renderStudentsTable(students);
    renderClassesTable(classes);
    renderTeachersTable(teachers);
    renderSubjectsTable(subjects);
  } catch (error) {
    console.error("Error loading master data:", error);
    auth.showNotification("Gagal memuat data master", "error");
  }
}

async function loadStudents() {
  // Mock data - replace with actual API call
  return new Promise((resolve) => {
    setTimeout(() => {
      const mockStudents = generateMockStudents();
      resolve(mockStudents);
    }, 300);
  });
}

async function loadClasses() {
  // Mock data - classes are fixed
  return new Promise((resolve) => {
    setTimeout(() => {
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
      resolve(classes);
    }, 200);
  });
}

async function loadTeachers() {
  // Mock data - replace with actual API call
  return new Promise((resolve) => {
    setTimeout(() => {
      const mockTeachers = generateMockTeachers();
      resolve(mockTeachers);
    }, 300);
  });
}

async function loadSubjects() {
  // Mock data - replace with actual API call
  return new Promise((resolve) => {
    setTimeout(() => {
      const subjects = [
        "Matematika",
        "Bahasa Indonesia",
        "Bahasa Inggris",
        "Fisika",
        "Kimia",
        "Biologi",
        "Sejarah",
        "Geografi",
        "Ekonomi",
        "Sosiologi",
        "Bahasa Jawa",
        "PKN",
      ];
      resolve(subjects);
    }, 200);
  });
}

function generateMockStudents() {
  const students = [];
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

  classes.forEach((className) => {
    const count = Math.floor(Math.random() * 10) + 25; // 25-35 students per class
    for (let i = 1; i <= count; i++) {
      students.push({
        nis: `2024${className.replace("-", "")}${String(i).padStart(2, "0")}`,
        nama: `Siswa ${className} ${i}`,
        kelas: className,
      });
    }
  });

  return students;
}

function generateMockTeachers() {
  const teachers = [
    { nama: "Pak Ahmad", mapel: "Matematika", username: "ahmad", role: "guru" },
    {
      nama: "Bu Siti",
      mapel: "Bahasa Indonesia",
      username: "siti",
      role: "guru",
    },
    {
      nama: "Pak Budi",
      mapel: "Bahasa Inggris",
      username: "budi",
      role: "guru",
    },
    { nama: "Bu Rina", mapel: "Fisika", username: "rina", role: "guru" },
    { nama: "Pak Dedi", mapel: "Kimia", username: "dedi", role: "guru" },
    { nama: "Bu Maya", mapel: "Biologi", username: "maya", role: "guru" },
    { nama: "Pak Joko", mapel: "Sejarah", username: "joko", role: "guru" },
    { nama: "Bu Ani", mapel: "Geografi", username: "ani", role: "guru" },
    {
      nama: "Pak Baru",
      mapel: "Bahasa Inggris",
      username: "baru",
      role: "guru",
    },
    { nama: "Admin", mapel: "", username: "admin", role: "admin" },
  ];

  return teachers;
}

function renderStudentsTable(students) {
  const tbody = document.getElementById("studentsTableBody");

  const rowsHtml = students
    .map(
      (student) => `
        <tr>
            <td>${student.nis}</td>
            <td>${student.nama}</td>
            <td>${student.kelas}</td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function renderClassesTable(classes) {
  const tbody = document.getElementById("classesTableBody");

  const rowsHtml = classes
    .map(
      (className) => `
        <tr>
            <td>${className}</td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function renderTeachersTable(teachers) {
  const tbody = document.getElementById("teachersTableBody");

  const rowsHtml = teachers
    .map(
      (teacher) => `
        <tr>
            <td>${teacher.nama}</td>
            <td>${teacher.mapel}</td>
            <td>${teacher.username}</td>
            <td>${teacher.role}</td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function renderSubjectsTable(subjects) {
  const tbody = document.getElementById("subjectsTableBody");

  const rowsHtml = subjects
    .map(
      (subject) => `
        <tr>
            <td>${subject}</td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function importCsv(type) {
  // Create file input
  const input = document.createElement("input");
  input.type = "file";
  input.accept = ".csv";

  input.onchange = function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        const csv = event.target.result;
        processCsvImport(type, csv);
      };
      reader.readAsText(file);
    }
  };

  input.click();
}

function processCsvImport(type, csv) {
  const lines = csv.split("\n");
  const headers = lines[0].split(",").map((h) => h.trim());

  // Basic validation
  if (
    type === "students" &&
    (!headers.includes("nis") ||
      !headers.includes("nama") ||
      !headers.includes("kelas"))
  ) {
    auth.showNotification("Format CSV tidak valid untuk siswa", "error");
    return;
  }

  if (
    type === "teachers" &&
    (!headers.includes("nama") ||
      !headers.includes("mapel") ||
      !headers.includes("username"))
  ) {
    auth.showNotification("Format CSV tidak valid untuk guru", "error");
    return;
  }

  // Parse data (simplified)
  const data = lines
    .slice(1)
    .map((line) => {
      const values = line.split(",");
      const obj = {};
      headers.forEach((header, index) => {
        obj[header] = values[index]?.trim() || "";
      });
      return obj;
    })
    .filter((item) => item.nama || item.nis); // Filter empty rows

  auth.showNotification(
    `Berhasil mengimpor ${data.length} data ${type}`,
    "success"
  );

  // Reload data
  loadAllMasterData();
}

function exportCsv(type) {
  let data = [];
  let filename = "";

  switch (type) {
    case "students":
      data = getStudentsTableData();
      filename = "data-siswa.csv";
      break;
    case "teachers":
      data = getTeachersTableData();
      filename = "data-guru.csv";
      break;
    default:
      return;
  }

  if (data.length === 0) {
    auth.showNotification("Tidak ada data untuk diekspor", "error");
    return;
  }

  const headers = Object.keys(data[0]);
  const csvContent = [
    headers.join(","),
    ...data.map((row) => headers.map((header) => row[header]).join(",")),
  ].join("\n");

  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  link.click();
}

function getStudentsTableData() {
  const rows = document.querySelectorAll("#studentsTableBody tr");
  const data = [];

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    data.push({
      nis: cells[0].textContent,
      nama: cells[1].textContent,
      kelas: cells[2].textContent,
    });
  });

  return data;
}

function getTeachersTableData() {
  const rows = document.querySelectorAll("#teachersTableBody tr");
  const data = [];

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    data.push({
      nama: cells[0].textContent,
      mapel: cells[1].textContent,
      username: cells[2].textContent,
      role: cells[3].textContent,
    });
  });

  return data;
}

async function syncData() {
  try {
    // Mock sync operation
    auth.showNotification("Sedang menyinkronkan data...", "info");

    await new Promise((resolve) => setTimeout(resolve, 2000));

    auth.showNotification("Data berhasil disinkronkan", "success");

    // Reload data
    loadAllMasterData();
  } catch (error) {
    console.error("Error syncing data:", error);
    auth.showNotification("Gagal menyinkronkan data", "error");
  }
}
