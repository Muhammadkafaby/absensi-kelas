# 🐳 Docker Setup - Sistem Absensi Kelas

Dokumentasi lengkap untuk menjalankan aplikasi Sistem Absensi Kelas menggunakan Docker.

## 📋 Prasyarat

Pastikan sudah terinstall:
- **Docker** (v20.10 atau lebih baru)
- **Docker Compose** (v2.0 atau lebih baru)

### Cek Instalasi Docker

```bash
docker --version
docker-compose --version
```

---

## 🚀 Quick Start

### 1. Clone Repository

```bash
git clone <repository-url>
cd absensi-kelas
```

### 2. Setup Environment

Copy file environment template:

```bash
cp .env.docker .env
```

Edit file `.env` sesuai kebutuhan (opsional):

```bash
# Database credentials
DB_DATABASE=absensi_kelas
DB_USERNAME=absensi_user
DB_PASSWORD=secret
DB_ROOT_PASSWORD=secret
```

### 3. Generate Encryption Key

```bash
# Jika belum ada composer di host, skip dulu (akan di-generate di container)
# Atau gunakan:
docker-compose run --rm app php spark key:generate
```

### 4. Build dan Start Container

```bash
docker-compose up -d --build
```

Tunggu hingga semua container running (sekitar 1-2 menit pertama kali).

### 5. Import Database

Jika sudah punya dump database:

```bash
# Copy dump file ke dalam container
docker cp database_dump.sql absensi_db:/tmp/

# Import ke MySQL
docker exec -i absensi_db mysql -uroot -psecret absensi_kelas < /path/to/database_dump.sql

# Atau import dari dalam container
docker exec -it absensi_db bash
mysql -uroot -psecret absensi_kelas < /tmp/database_dump.sql
exit
```

Atau jalankan migration CodeIgniter (jika tersedia):

```bash
docker-compose exec app php spark migrate
docker-compose exec app php spark db:seed DatabaseSeeder
```

### 6. Akses Aplikasi

Setelah container running:

- **Aplikasi Web**: http://localhost:8080
- **PhpMyAdmin**: http://localhost:8081
  - Server: `db`
  - Username: `root` atau `absensi_user`
  - Password: `secret`

---

## 📦 Container Services

### 1. **app** - PHP 8.2 + Nginx
- Port: `8080:80`
- Web server dengan PHP-FPM
- CodeIgniter 4 application

### 2. **db** - MySQL 8.0
- Port: `3306:3306`
- Database server
- Data persisten di Docker volume `mysql_data`

### 3. **phpmyadmin** - Database Management
- Port: `8081:80`
- Web interface untuk manage database

---

## 🛠️ Perintah Docker Compose

### Start Services

```bash
# Start semua container (foreground)
docker-compose up

# Start semua container (background/detached)
docker-compose up -d

# Start specific service
docker-compose up -d app
```

### Stop Services

```bash
# Stop semua container (data tetap tersimpan)
docker-compose down

# Stop dan hapus volumes (HATI-HATI: data hilang!)
docker-compose down -v
```

### Rebuild Container

```bash
# Rebuild semua container
docker-compose up -d --build

# Rebuild specific service
docker-compose up -d --build app
```

### View Logs

```bash
# Lihat logs semua container
docker-compose logs

# Lihat logs specific container
docker-compose logs app
docker-compose logs db

# Follow logs (real-time)
docker-compose logs -f app
```

### Execute Commands

```bash
# Masuk ke container app
docker-compose exec app bash

# Jalankan PHP artisan/spark command
docker-compose exec app php spark list
docker-compose exec app php spark migrate

# Jalankan Composer
docker-compose exec app composer install
docker-compose exec app composer update
```

---

## 🗄️ Database Management

### Backup Database

```bash
# Backup ke file SQL
docker exec absensi_db mysqldump -uroot -psecret absensi_kelas > backup_$(date +%Y%m%d_%H%M%S).sql

# Atau gunakan script helper
chmod +x docker/scripts/backup-db.sh
./docker/scripts/backup-db.sh
```

### Restore Database

```bash
# Restore dari file SQL
docker exec -i absensi_db mysql -uroot -psecret absensi_kelas < backup.sql
```

### Connect to MySQL CLI

```bash
# Via docker exec
docker exec -it absensi_db mysql -uroot -psecret absensi_kelas

# Atau via mysql client di host (jika terinstall)
mysql -h127.0.0.1 -P3306 -uroot -psecret absensi_kelas
```

---

## 📁 Struktur Docker Files

```
absensi-kelas/
├── Dockerfile                      # Image definition untuk app
├── docker-compose.yml              # Orchestration semua services
├── .dockerignore                   # Files yang di-exclude dari image
├── .env.docker                     # Template environment variables
├── DOCKER.md                       # Dokumentasi ini
│
├── docker/
│   ├── nginx/
│   │   └── default.conf           # Nginx configuration
│   ├── php/
│   │   └── php.ini                # PHP configuration
│   ├── supervisor/
│   │   └── supervisord.conf       # Process manager config
│   └── mysql/
│       └── init/
│           └── 01-create-database.sql  # DB initialization
│
└── writable/                       # Application writable directory
    ├── cache/
    ├── logs/
    ├── session/
    └── uploads/
```

---

## 🔧 Troubleshooting

### Port Already in Use

Jika port 8080, 8081, atau 3306 sudah digunakan:

**Option 1:** Stop aplikasi yang menggunakan port tersebut

**Option 2:** Ubah port di `docker-compose.yml`:

```yaml
services:
  app:
    ports:
      - "8082:80"  # Ubah dari 8080 ke 8082

  db:
    ports:
      - "3307:3306"  # Ubah dari 3306 ke 3307

  phpmyadmin:
    ports:
      - "8083:80"  # Ubah dari 8081 ke 8083
```

### Container Keeps Restarting

Cek logs untuk error:

```bash
docker-compose logs app
docker-compose logs db
```

Common issues:
- Database belum siap saat app start → tunggu beberapa detik, restart dengan `docker-compose restart app`
- Permission issues → cek ownership writable folder
- Memory limit → increase di `docker-compose.yml` atau Docker Desktop settings

### Database Connection Error

1. Pastikan container `db` running:
   ```bash
   docker-compose ps
   ```

2. Test connection ke MySQL:
   ```bash
   docker exec -it absensi_db mysql -uroot -psecret -e "SHOW DATABASES;"
   ```

3. Cek environment variables di `.env`:
   ```
   database.default.hostname = db   # BUKAN localhost!
   database.default.database = absensi_kelas
   database.default.username = absensi_user
   database.default.password = secret
   ```

### Permission Denied on Writable Folders

```bash
# Di dalam container
docker-compose exec app chown -R www-data:www-data /var/www/html/writable
docker-compose exec app chmod -R 775 /var/www/html/writable
```

### Clear Cache

```bash
# Clear CodeIgniter cache
docker-compose exec app php spark cache:clear

# Restart containers
docker-compose restart
```

---

## 🔐 Production Deployment

### Security Checklist

1. **Update passwords** di `.env`:
   ```
   DB_ROOT_PASSWORD=<strong-password>
   DB_PASSWORD=<strong-password>
   ```

2. **Generate encryption key**:
   ```bash
   docker-compose exec app php spark key:generate
   ```

3. **Set environment** di `.env`:
   ```
   CI_ENVIRONMENT = production
   ```

4. **Disable PhpMyAdmin** di `docker-compose.yml` (comment atau remove):
   ```yaml
   # phpmyadmin:
   #   image: phpmyadmin/phpmyadmin
   #   ...
   ```

5. **Enable HTTPS** (reverse proxy dengan Let's Encrypt):
   - Gunakan Nginx Proxy Manager atau Traefik
   - Atau setup manual dengan certbot

6. **Backup reguler**:
   - Setup cron job untuk backup database
   - Backup volume `mysql_data`

### Performance Tuning

1. **PHP OPcache** sudah enabled di `docker/php/php.ini`

2. **MySQL Configuration** - edit `docker-compose.yml`:
   ```yaml
   db:
     command: >
       --default-authentication-plugin=mysql_native_password
       --max_connections=200
       --innodb_buffer_pool_size=256M
   ```

3. **Nginx Cache** - edit `docker/nginx/default.conf` untuk static assets

---

## 📊 Monitoring

### Resource Usage

```bash
# Lihat resource usage semua container
docker stats

# Specific container
docker stats absensi_app
```

### Disk Usage

```bash
# Docker disk usage
docker system df

# Volume size
docker volume ls
docker volume inspect mysql_data
```

---

## 🧹 Maintenance

### Clean Up

```bash
# Stop dan remove containers (data tetap aman di volume)
docker-compose down

# Remove unused images
docker image prune -a

# Remove unused volumes (HATI-HATI!)
docker volume prune

# Complete cleanup (HATI-HATI: semua data hilang!)
docker-compose down -v
docker system prune -a
```

### Update Application

```bash
# Pull latest code
git pull origin main

# Rebuild container
docker-compose up -d --build

# Run migrations if any
docker-compose exec app php spark migrate
```

---

## 📝 Helper Scripts

Create helper scripts di `docker/scripts/`:

### `docker/scripts/backup-db.sh`

```bash
#!/bin/bash
BACKUP_DIR="./backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/absensi_kelas_$TIMESTAMP.sql"

mkdir -p $BACKUP_DIR
docker exec absensi_db mysqldump -uroot -psecret absensi_kelas > $BACKUP_FILE
echo "Backup created: $BACKUP_FILE"
```

### `docker/scripts/restore-db.sh`

```bash
#!/bin/bash
if [ -z "$1" ]; then
    echo "Usage: ./restore-db.sh <backup_file.sql>"
    exit 1
fi

docker exec -i absensi_db mysql -uroot -psecret absensi_kelas < $1
echo "Database restored from: $1"
```

Make executable:
```bash
chmod +x docker/scripts/*.sh
```

---

## ❓ FAQ

**Q: Apakah data database hilang saat container dihapus?**
A: Tidak, data tersimpan di Docker volume `mysql_data` yang persisten.

**Q: Bagaimana cara mengakses dari perangkat lain di network?**
A: Ganti `localhost` dengan IP address host:
   - Cek IP: `ipconfig` (Windows) atau `ifconfig` (Linux/Mac)
   - Akses: `http://192.168.x.x:8080`

**Q: Apakah bisa running development dan production di satu server?**
A: Ya, gunakan port berbeda atau Docker network berbeda per environment.

**Q: Bagaimana cara update PHP/MySQL version?**
A: Edit `Dockerfile` (PHP) atau `docker-compose.yml` (MySQL), lalu rebuild.

---

## 📞 Support

Jika menemukan masalah:

1. Cek logs: `docker-compose logs`
2. Cek status: `docker-compose ps`
3. Restart: `docker-compose restart`
4. Rebuild: `docker-compose up -d --build`

---

## 📄 License

Sama dengan lisensi aplikasi utama.
