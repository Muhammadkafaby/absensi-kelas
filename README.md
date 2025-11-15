# Absensi Kelas - SMA NU Kaplongan

Sistem Absensi Kelas berbasis web untuk SMA NU Kaplongan yang dibangun menggunakan **CodeIgniter 4** dan **MySQL**.

## 🎯 Fitur Utama

### Untuk Admin / TU (Tata Usaha)
- ✅ Dashboard dengan ringkasan data
- ✅ Manajemen Master Data:
  - Kelas (CRUD)
  - Siswa (CRUD)
  - Guru (CRUD)
  - Mata Pelajaran (CRUD)
- ✅ Rekap Absensi Global (semua kelas, semua guru)
- ✅ Monitoring siswa yang alpa hari ini

### Untuk Guru
- ✅ Dashboard pribadi dengan mata pelajaran yang diampu
- ✅ Input Absensi:
  - Pilih kelas, tanggal, jam pelajaran, dan mata pelajaran
  - Tandai kehadiran siswa: H (Hadir), I (Izin), S (Sakit), A (Alpa), T (Terlambat)
  - Tambahkan catatan untuk setiap siswa
- ✅ Rekap Absensi:
  - Lihat riwayat absensi yang sudah diinput
  - Filter berdasarkan kelas, mata pelajaran, dan tanggal
  - Perhitungan persentase kehadiran

## 🛠️ Teknologi yang Digunakan

- **Backend**: CodeIgniter 4.6.3
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **PHP Version**: 8.0+

## 📋 Database Schema

### Tabel Utama

1. **users** - Data pengguna (admin dan guru)
2. **classes** - Data kelas (X-1, XI IPA 1, XII IPS 2, dll)
3. **students** - Data siswa dengan relasi ke kelas
4. **teachers** - Data guru
5. **subjects** - Data mata pelajaran dengan relasi ke guru pengampu
6. **attendance_sessions** - Sesi absensi (pertemuan)
7. **attendance_records** - Record kehadiran per siswa per sesi

### Relasi Database

```
users (role: admin/guru) -> teacher_id -> teachers
students -> class_id -> classes
subjects -> teacher_id -> teachers
attendance_sessions -> class_id, subject_id, teacher_id, created_by
attendance_records -> attendance_session_id, student_id
```

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd absensi-kelas
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Database

Buat database baru di MySQL:

```sql
CREATE DATABASE absensi_kelas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Copy file `.env` dan sesuaikan konfigurasi database:

```bash
cp env .env
```

Edit file `.env`:

```ini
database.default.hostname = localhost
database.default.database = absensi_kelas
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Jalankan Migrasi dan Seeder

```bash
# Jalankan migrasi untuk membuat tabel
php spark migrate

# Jalankan seeder untuk mengisi data sample
php spark db:seed DatabaseSeeder
```

### 5. Jalankan Development Server

```bash
php spark serve
```

Aplikasi akan berjalan di `http://localhost:8080`

## 👤 Akun Default

Setelah menjalankan seeder, gunakan akun berikut untuk login:

### Admin
- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Admin / TU

### Guru
- **Username**: `guru`
- **Password**: `guru123`
- **Role**: Guru (Mata pelajaran: Matematika)

### Guru Lainnya
- **Username**: `siti.nurjanah` | Password: `guru123`
- **Username**: `budi.santoso` | Password: `guru123`

## 📁 Struktur Direktori

```
absensi-kelas/
├── app/
│   ├── Controllers/           # Controllers (Auth, Dashboard, Master Data, Attendance, Recap)
│   ├── Models/                # Models untuk setiap tabel
│   ├── Views/                 # Views dengan layout system
│   │   ├── layouts/           # Base layout
│   │   ├── auth/              # Login page
│   │   ├── dashboard/         # Dashboard admin & guru
│   │   ├── attendance/        # Form input absensi
│   │   ├── recap/             # Halaman rekap
│   │   └── master_data/       # CRUD master data
│   ├── Database/
│   │   ├── Migrations/        # Database migrations
│   │   └── Seeds/             # Database seeders
│   ├── Filters/               # Auth & Role filters
│   └── Config/
│       ├── Routes.php         # Route configuration
│       └── Filters.php        # Filter configuration
├── public/
│   ├── assets/
│   │   ├── css/               # CSS files
│   │   └── js/                # JavaScript files
│   └── index.php
├── writable/                  # Cache, logs, session
├── .env                       # Environment configuration
└── README.md
```

## 🔐 Autentikasi & Autorisasi

### Authentication Filter
- Semua halaman kecuali login memerlukan autentikasi
- Session-based authentication dengan password hashing menggunakan `password_hash()`

### Role-Based Access Control

**Admin**:
- `/dashboard` - Dashboard admin
- `/master/*` - Semua halaman master data (CRUD)
- `/recap/admin` - Rekap absensi global

**Guru**:
- `/dashboard` - Dashboard guru
- `/attendance` - Input absensi
- `/recap/teacher` - Rekap absensi pribadi

## 📊 Cara Penggunaan

### Untuk Guru - Input Absensi

1. Login dengan akun guru
2. Klik menu "Input Absensi"
3. Pilih:
   - Kelas
   - Tanggal
   - Jam pelajaran (opsional)
   - Mata pelajaran
4. Sistem akan menampilkan daftar siswa di kelas tersebut
5. Pilih status kehadiran untuk setiap siswa:
   - **H** = Hadir
   - **I** = Izin
   - **S** = Sakit
   - **A** = Alpa
   - **T** = Terlambat
6. Tambahkan catatan jika diperlukan
7. Klik "Simpan Absensi"

**Shortcut**:
- Tombol "✓ Semua Hadir" - Menandai semua siswa hadir sekaligus
- Tombol "↺ Reset" - Reset semua pilihan

### Untuk Admin - Kelola Master Data

1. Login dengan akun admin
2. Klik menu "Master Data"
3. Pilih entitas yang ingin dikelola (Kelas, Siswa, Guru, Mata Pelajaran)
4. Gunakan tombol:
   - **Tambah** - Menambah data baru
   - **Edit** - Mengubah data
   - **Hapus** - Menghapus data

### Melihat Rekap

**Admin**:
- Dapat melihat rekap semua kelas dan semua guru
- Filter berdasarkan: kelas, mata pelajaran, rentang tanggal

**Guru**:
- Hanya dapat melihat rekap dari sesi absensi yang dibuat sendiri
- Filter berdasarkan: kelas, mata pelajaran (yang diampu), rentang tanggal

Rekap menampilkan:
- Jumlah H, I, S, A, T per siswa
- Total pertemuan
- Persentase kehadiran (dengan indikator warna: hijau ≥75%, kuning 50-74%, merah <50%)

## 🎨 Desain UI

- Menggunakan CSS Custom Properties untuk theming
- Responsive design (mobile-friendly)
- Gradient colors dengan Inter font family
- Smooth transitions dan hover effects
- Status badges dengan color coding

## 🔧 Konfigurasi

### Session
- Driver: FileHandler
- Expire: 7200 seconds (2 jam)
- Cookie name: `ci_session`

### CSRF Protection
- Enabled untuk form POST
- Token regeneration setiap request

### Database Connection
- Driver: MySQLi
- Charset: utf8mb4
- Collation: utf8mb4_general_ci

## 📝 Development Notes

### Menambah Migration Baru

```bash
php spark make:migration AddNewTable
```

### Menambah Seeder Baru

```bash
php spark make:seeder NewSeeder
```

### Rollback Migration

```bash
php spark migrate:rollback
```

## 🐛 Troubleshooting

### Error: "Database connection failed"
- Pastikan MySQL service berjalan
- Cek konfigurasi di `.env` (hostname, username, password)
- Pastikan database sudah dibuat

### Error: "CSRF token mismatch"
- Pastikan form memiliki `<?= csrf_field() ?>`
- Pastikan session writable directory memiliki permission yang benar

### Error: "Akses ditolak"
- Pastikan Anda login dengan role yang sesuai
- Admin tidak bisa mengakses halaman guru, dan sebaliknya

## 📄 License

Sistem ini dibuat untuk SMA NU Kaplongan.

## 👨‍💻 Developer

Developed with ❤️ for SMA NU Kaplongan

---

**Happy Coding! 🚀**
