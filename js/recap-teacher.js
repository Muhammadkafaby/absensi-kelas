// Teacher recap functionality
document.addEventListener("DOMContentLoaded", function () {
  const user = auth.getUser();
  if (!user || user.role !== "guru") {
    window.location.href = "login.html";
    return;
  }

  // Generate navigation
  generateNavigation("guru");

  // Set default date range (last 30 days)
  const today = new Date();
  const thirtyDaysAgo = new Date();
  thirtyDaysAgo.setDate(today.getDate() - 30);

  document.getElementById("dateRangeStart").value = thirtyDaysAgo
    .toISOString()
    .split("T")[0];
  document.getElementById("dateRangeEnd").value = today
    .toISOString()
    .split("T")[0];

  // Load initial data
  loadRecapData();

  // Filter button
  document.getElementById("filterBtn").addEventListener("click", loadRecapData);

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

  if (role === "guru") {
    navItems = [
      { text: "Dashboard", action: "dashboard", active: false },
      { text: "Absensi", action: "attendance-teacher", active: false },
      { text: "Rekap Saya", action: "recap-teacher", active: false },
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
  if (path.includes("attendance-teacher")) return "attendance-teacher";
  if (path.includes("recap-teacher")) return "recap-teacher";
  return "dashboard";
}

function navigateToPage(action) {
  const pageMap = {
    dashboard: "dashboard.html",
    "attendance-teacher": "attendance-teacher.html",
    "recap-teacher": "recap-teacher.html",
  };

  if (pageMap[action]) {
    window.location.href = pageMap[action];
  }
}

async function loadRecapData() {
  const dateStart = document.getElementById("dateRangeStart").value;
  const dateEnd = document.getElementById("dateRangeEnd").value;
  const classFilter = document.getElementById("classFilter").value;
  const subjectFilter = document.getElementById("subjectFilter").value;

  try {
    // Mock data - replace with actual API call
    const mockData = generateMockRecapData(
      dateStart,
      dateEnd,
      classFilter,
      subjectFilter
    );
    renderRecapTable(mockData.sessions);
    updateMetrics(mockData.metrics);
  } catch (error) {
    console.error("Error loading recap data:", error);
    auth.showNotification("Gagal memuat data rekap", "error");
  }
}

function generateMockRecapData(dateStart, dateEnd, classFilter, subjectFilter) {
  const sessions = [];
  const metrics = {
    totalSessions: 0,
    avgAttendance: 0,
    totalAbsent: 0,
    totalPermit: 0,
    totalSick: 0,
  };

  // Generate mock sessions for the last 30 days
  const startDate = new Date(dateStart);
  const endDate = new Date(dateEnd);
  const user = auth.getUser();

  let totalAttendancePercent = 0;
  let sessionCount = 0;

  for (let d = new Date(startDate); d <= endDate; d.setDate(d.getDate() + 1)) {
    // Skip weekends
    if (d.getDay() === 0 || d.getDay() === 6) continue;

    // Random number of sessions per day (1-3)
    const sessionsPerDay = Math.floor(Math.random() * 3) + 1;

    for (let session = 1; session <= sessionsPerDay; session++) {
      const className =
        classFilter ||
        ["X-1", "X-2", "XI-1", "XI-2"][Math.floor(Math.random() * 4)];
      const subject = subjectFilter || user.mapel || "Matematika";

      const h = Math.floor(Math.random() * 10) + 25; // 25-35 present
      const a = Math.floor(Math.random() * 5); // 0-4 absent
      const s = Math.floor(Math.random() * 3); // 0-2 sick
      const i = Math.floor(Math.random() * 3); // 0-2 permit
      const total = h + a + s + i;
      const percent = Math.round((h / total) * 100);

      sessions.push({
        tanggal: d.toISOString().split("T")[0],
        jam: session,
        kelas: className,
        mapel: subject,
        h: h,
        a: a,
        s: s,
        i: i,
        percent: percent,
      });

      metrics.totalSessions++;
      metrics.totalAbsent += a;
      metrics.totalPermit += i;
      metrics.totalSick += s;
      totalAttendancePercent += percent;
      sessionCount++;
    }
  }

  metrics.avgAttendance =
    sessionCount > 0 ? Math.round(totalAttendancePercent / sessionCount) : 0;

  return { sessions, metrics };
}

function renderRecapTable(sessions) {
  const tbody = document.getElementById("recapTableBody");

  const rowsHtml = sessions
    .map(
      (session) => `
        <tr>
            <td>${formatDate(session.tanggal)}</td>
            <td>${session.jam}</td>
            <td>${session.kelas}</td>
            <td>${session.mapel}</td>
            <td class="status-H">${session.h}</td>
            <td class="status-A">${session.a}</td>
            <td class="status-S">${session.s}</td>
            <td class="status-I">${session.i}</td>
            <td>${session.percent}%</td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;
}

function updateMetrics(metrics) {
  document.getElementById("totalSessions").textContent = metrics.totalSessions;
  document.getElementById(
    "avgAttendance"
  ).textContent = `${metrics.avgAttendance}%`;
  document.getElementById("totalAbsent").textContent = metrics.totalAbsent;
  document.getElementById("totalPermit").textContent = metrics.totalPermit;
  document.getElementById("totalSick").textContent = metrics.totalSick;
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
}

function downloadCsv() {
  const sessions = getTableData();
  if (sessions.length === 0) {
    auth.showNotification("Tidak ada data untuk diunduh", "error");
    return;
  }

  const csvContent = [
    ["Tanggal", "Jam", "Kelas", "Mapel", "H", "A", "S", "I", "% Hadir"],
    ...sessions.map((session) => [
      session.tanggal,
      session.jam,
      session.kelas,
      session.mapel,
      session.h,
      session.a,
      session.s,
      session.i,
      session.percent,
    ]),
  ]
    .map((row) => row.join(","))
    .join("\n");

  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `rekap-absensi-${new Date().toISOString().split("T")[0]}.csv`;
  link.click();
}

function printPdf() {
  // Simple print functionality - in real app, use a PDF library
  window.print();
}

function getTableData() {
  const rows = document.querySelectorAll("#recapTableBody tr");
  const data = [];

  rows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    data.push({
      tanggal: cells[0].textContent,
      jam: cells[1].textContent,
      kelas: cells[2].textContent,
      mapel: cells[3].textContent,
      h: parseInt(cells[4].textContent),
      a: parseInt(cells[5].textContent),
      s: parseInt(cells[6].textContent),
      i: parseInt(cells[7].textContent),
      percent: parseInt(cells[8].textContent.replace("%", "")),
    });
  });

  return data;
}
