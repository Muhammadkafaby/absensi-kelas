# 🔧 Panduan Install Dependencies

## ⚠️ Error yang Muncul

Jika Anda melihat error berikut:
```
Class "PhpOffice\PhpSpreadsheet\Spreadsheet" not found
```

Ini berarti **vendor dependencies belum ter-install**.

---

## ✅ Solusi: Install Dependencies dengan Composer

### Step 1: Pastikan Composer Terinstall

Cek apakah composer sudah terinstall:
```bash
composer --version
```

Jika belum, install composer dari: https://getcomposer.com/download/

### Step 2: Masuk ke Direktori Project

```bash
cd /home/user/absensi-kelas
```

### Step 3: Install Dependencies

```bash
composer install
```

**Atau jika ada masalah:**

```bash
composer update
```

**Atau install manual dependencies:**

```bash
composer require codeigniter4/framework:^4.4
composer require phpoffice/phpspreadsheet:^1.29
```

### Step 4: Verifikasi Instalasi

Setelah selesai, pastikan folder `vendor` sudah ada:

```bash
ls -la vendor/
```

---

## 📦 Dependencies yang Dibutuhkan

File `composer.json` sudah include:

- **CodeIgniter 4** (Framework)
- **PhpSpreadsheet** (Export Excel)
- PHPUnit (Testing)
- Faker (Dummy data)

---

## 🚀 Setelah Install

Semua fitur berikut akan berfungsi:

✅ Export siswa to Excel
✅ Export rekap absensi to Excel
✅ Import siswa from Excel
✅ Semua CRUD operations
✅ Dashboard & Reports
✅ Activity Logs

---

## 🆘 Troubleshooting

### Error: "composer: command not found"

Install composer terlebih dahulu:

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install composer
```

**Windows:**
Download dari: https://getcomposer.com/Composer-Setup.exe

**macOS:**
```bash
brew install composer
```

### Error: Memory limit exceeded

Tambahkan memory limit:
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### Error: Extension mbstring missing

Install PHP extensions:
```bash
sudo apt install php-mbstring php-xml php-zip php-gd
```

---

## 📝 Notes

- Vendor folder **TIDAK** di-commit ke git (ada di `.gitignore`)
- Setiap kali clone project baru, **harus run `composer install`**
- File `composer.lock` memastikan semua dapat versi library yang sama

---

**Last Updated:** 2025-01-15
**Status:** ✅ composer.json sudah lengkap, tinggal install
