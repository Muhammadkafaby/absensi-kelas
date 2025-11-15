// Teacher attendance input functionality
document.addEventListener("DOMContentLoaded", function () {
  const user = auth.getUser();
  if (!user || user.role !== "guru") {
    window.location.href = "login.html";
    return;
  }

  // Generate navigation
  generateNavigation("guru");

  // Set default subject from user profile
  const subjectInput = document.getElementById("subjectInput");
  if (subjectInput && user.mapel) {
    subjectInput.value = user.mapel;
  }

  // Set default date to today
  const dateInput = document.getElementById("dateInput");
  if (dateInput) {
    const today = new Date().toISOString().split("T")[0];
    dateInput.value = today;
  }

  // Load students when class is selected
  document
    .getElementById("classSelect")
    .addEventListener("change", loadStudents);

  // Search functionality
  document
    .getElementById("searchInput")
    .addEventListener("input", filterStudents);

  // Quick actions
  document
    .getElementById("allPresentBtn")
    .addEventListener("click", setAllPresent);
  document
    .getElementById("resetBtn")
    .addEventListener("click", resetAttendance);

  // Save attendance
  document.getElementById("saveBtn").addEventListener("click", saveAttendance);

  // Load initial data if class is pre-selected
  const urlParams = new URLSearchParams(window.location.search);
  const preselectedClass = urlParams.get("class");
  if (preselectedClass) {
    document.getElementById("classSelect").value = preselectedClass;
    loadStudents();
  }
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

async function loadStudents() {
  const classSelect = document.getElementById("classSelect");
  const selectedClass = classSelect.value;

  if (!selectedClass) {
    document.getElementById("attendanceTableBody").innerHTML = "";
    return;
  }

  try {
    // Mock data - replace with actual API call
    const mockStudents = generateMockStudents(selectedClass);
    renderStudentsTable(mockStudents);
  } catch (error) {
    console.error("Error loading students:", error);
    auth.showNotification("Gagal memuat data siswa", "error");
  }
}

function generateMockStudents(className) {
  const students = [];
  const count = Math.floor(Math.random() * 10) + 25; // 25-35 students

  for (let i = 1; i <= count; i++) {
    students.push({
      nis: `2024${className.replace("-", "")}${String(i).padStart(2, "0")}`,
      nama: `Siswa ${className} ${i}`,
      kelas: className,
      status: "H", // Default to present
      catatan: "",
    });
  }

  return students;
}

function renderStudentsTable(students) {
  const tbody = document.getElementById("attendanceTableBody");

  const rowsHtml = students
    .map(
      (student, index) => `
        <tr data-nis="${student.nis}">
            <td>${index + 1}</td>
            <td>${student.nis}</td>
            <td>${student.nama}</td>
            <td>
                <div class="attendance-status">
                    <div class="status-option ${
                      student.status === "H" ? "selected" : ""
                    }" data-status="H">H</div>
                    <div class="status-option ${
                      student.status === "A" ? "selected" : ""
                    }" data-status="A">A</div>
                    <div class="status-option ${
                      student.status === "S" ? "selected" : ""
                    }" data-status="S">S</div>
                    <div class="status-option ${
                      student.status === "I" ? "selected" : ""
                    }" data-status="I">I</div>
                </div>
            </td>
            <td><input type="text" placeholder="Catatan" value="${
              student.catatan
            }" class="note-input"></td>
        </tr>
    `
    )
    .join("");

  tbody.innerHTML = rowsHtml;

  // Add event listeners for status selection
  tbody.addEventListener("click", function (e) {
    if (e.target.classList.contains("status-option")) {
      const statusOption = e.target;
      const row = statusOption.closest("tr");
      const statusOptions = row.querySelectorAll(".status-option");

      // Remove selected class from all options in this row
      statusOptions.forEach((option) => option.classList.remove("selected"));

      // Add selected class to clicked option
      statusOption.classList.add("selected");
    }
  });
}

function filterStudents() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  const rows = document.querySelectorAll("#attendanceTableBody tr");

  rows.forEach((row) => {
    const nis = row.cells[1].textContent.toLowerCase();
    const nama = row.cells[2].textContent.toLowerCase();

    if (nis.includes(searchTerm) || nama.includes(searchTerm)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}

function setAllPresent() {
  const statusOptions = document.querySelectorAll(
    '#attendanceTableBody .status-option[data-status="H"]'
  );
  const allStatusOptions = document.querySelectorAll(
    "#attendanceTableBody .status-option"
  );

  // Remove selected from all
  allStatusOptions.forEach((option) => option.classList.remove("selected"));

  // Set H as selected
  statusOptions.forEach((option) => option.classList.add("selected"));
}

function resetAttendance() {
  const allStatusOptions = document.querySelectorAll(
    "#attendanceTableBody .status-option"
  );
  const noteInputs = document.querySelectorAll(".note-input");

  // Remove selected from all status options
  allStatusOptions.forEach((option) => option.classList.remove("selected"));

  // Clear notes
  noteInputs.forEach((input) => (input.value = ""));
}

async function saveAttendance() {
  const classSelect = document.getElementById("classSelect");
  const dateInput = document.getElementById("dateInput");
  const hourSelect = document.getElementById("hourSelect");
  const subjectInput = document.getElementById("subjectInput");

  // Validation
  if (
    !classSelect.value ||
    !dateInput.value ||
    !hourSelect.value ||
    !subjectInput.value
  ) {
    auth.showNotification("Harap lengkapi semua field kontrol", "error");
    return;
  }

  const attendanceData = {
    kelas: classSelect.value,
    tanggal: dateInput.value,
    jam: hourSelect.value,
    mapel: subjectInput.value,
    siswa: [],
  };

  // Collect attendance data
  const rows = document.querySelectorAll("#attendanceTableBody tr");
  rows.forEach((row) => {
    const nis = row.dataset.nis;
    const selectedStatus = row.querySelector(".status-option.selected");
    const status = selectedStatus ? selectedStatus.dataset.status : "H";
    const catatan = row.querySelector(".note-input").value;

    attendanceData.siswa.push({
      nis: nis,
      status: status,
      catatan: catatan,
    });
  });

  try {
    // Mock API call - replace with actual endpoint
    console.log("Saving attendance:", attendanceData);

    // Simulate API delay
    await new Promise((resolve) => setTimeout(resolve, 1000));

    auth.showNotification(
      `Berhasil disimpan (${attendanceData.siswa.length} entri)`,
      "success"
    );

    // Reset form or redirect
    // window.location.href = 'dashboard.html';
  } catch (error) {
    console.error("Error saving attendance:", error);
    auth.showNotification("Gagal menyimpan absensi", "error");
  }
}
