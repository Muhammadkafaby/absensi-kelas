# 🚀 VPS Deployment Guide - Sistem Absensi Kelas

Panduan lengkap untuk deploy aplikasi Absensi Kelas ke VPS menggunakan Docker.

---

## 📋 Prasyarat VPS

### Minimum Requirements:
- **RAM:** 1GB (recommended 2GB+)
- **Storage:** 10GB free space
- **OS:** Ubuntu 20.04 / 22.04 LTS (recommended)
- **CPU:** 1 vCore (2+ recommended)

### Software Requirements:
- Docker Engine 20.10+
- Docker Compose 2.0+
- Git

---

## 🔧 Step 1: Setup VPS & Install Docker

### 1.1 Connect to VPS

```bash
# SSH ke VPS
ssh root@your-vps-ip

# Atau dengan user non-root
ssh username@your-vps-ip
```

### 1.2 Update System

```bash
# Update package list
sudo apt update && sudo apt upgrade -y

# Install basic utilities
sudo apt install -y curl git wget nano
```

### 1.3 Install Docker

```bash
# Remove old Docker versions (if any)
sudo apt remove docker docker-engine docker.io containerd runc

# Install dependencies
sudo apt install -y \
    ca-certificates \
    curl \
    gnupg \
    lsb-release

# Add Docker GPG key
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# Add Docker repository
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker Engine
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Verify installation
docker --version
docker compose version
```

### 1.4 Configure Docker (Optional but Recommended)

```bash
# Add current user to docker group (no need sudo for docker commands)
sudo usermod -aG docker $USER

# Apply group changes (logout/login or run):
newgrp docker

# Enable Docker to start on boot
sudo systemctl enable docker
sudo systemctl start docker

# Verify Docker is running
sudo systemctl status docker
```

---

## 📦 Step 2: Clone & Setup Application

### 2.1 Clone Repository

```bash
# Navigate to web directory (or your preferred location)
cd /home

# Clone repository
git clone https://github.com/username/absensi-kelas.git
cd absensi-kelas

# Checkout specific branch if needed
git checkout claude/enterprise-ui-design-017rdFuhAhbUhxnvHUf46cDM
```

### 2.2 Setup Environment

```bash
# Copy environment template
cp .env.docker .env

# Edit environment file
nano .env
```

**Configure these important values:**

```env
# IMPORTANT: Change for production!
DB_ROOT_PASSWORD=YOUR_STRONG_ROOT_PASSWORD
DB_PASSWORD=YOUR_STRONG_USER_PASSWORD
DB_DATABASE=absensi_kelas
DB_USERNAME=absensi_user

# App settings
CI_ENVIRONMENT=production
app.baseURL='http://your-domain.com/'  # Or http://your-vps-ip:8080/

# Timezone
date.timezone=Asia/Jakarta
```

**Generate encryption key:**
```bash
# Will generate key after containers are running
# docker compose exec app php spark key:generate
```

### 2.3 Configure Ports (if needed)

If ports 8080, 8081, or 3306 are already in use:

```bash
nano docker-compose.yml

# Change ports:
services:
  app:
    ports:
      - "8080:80"  # Change 8080 to available port

  db:
    ports:
      - "3306:3306"  # Change if needed

  phpmyadmin:
    ports:
      - "8081:80"  # Change if needed
```

---

## 🚀 Step 3: Build & Deploy

### 3.1 Build Docker Images

```bash
# Build and start containers
docker compose up -d --build

# This will:
# - Build PHP application image
# - Download MySQL 8.0 image
# - Download PhpMyAdmin image
# - Create network and volumes
# - Start all services

# Wait for containers to be ready (30-60 seconds)
```

### 3.2 Verify Containers

```bash
# Check all containers are running
docker compose ps

# Should see:
# NAME                  STATUS
# absensi_app           Up
# absensi_db            Up
# absensi_phpmyadmin    Up

# Check logs if any issues
docker compose logs app
docker compose logs db
```

### 3.3 Generate Encryption Key

```bash
# Generate CodeIgniter encryption key
docker compose exec app php spark key:generate

# This will update .env file with encryption.key
```

---

## 💾 Step 4: Import Database

### Method 1: Via PhpMyAdmin (Easiest)

1. Open browser: `http://your-vps-ip:8081`
2. Login:
   - Server: `db`
   - Username: `root`
   - Password: (your DB_ROOT_PASSWORD from .env)
3. Select database: `absensi_kelas`
4. Go to "Import" tab
5. Choose your SQL dump file
6. Click "Go"

### Method 2: Via Command Line

```bash
# If database dump is on your local machine
scp database_dump.sql root@your-vps-ip:/home/absensi-kelas/

# SSH to VPS and import
ssh root@your-vps-ip
cd /home/absensi-kelas
docker compose exec -T db mysql -uroot -pYOUR_ROOT_PASSWORD absensi_kelas < database_dump.sql
```

### Method 3: Via Restore Script

```bash
# Copy dump file to server
scp database_dump.sql root@your-vps-ip:/home/absensi-kelas/backups/

# SSH and restore
ssh root@your-vps-ip
cd /home/absensi-kelas
./docker/scripts/restore-db.sh backups/database_dump.sql
```

### Method 4: Via MySQL Client (if installed on VPS)

```bash
mysql -h127.0.0.1 -P3306 -uroot -pYOUR_ROOT_PASSWORD absensi_kelas < database_dump.sql
```

---

## 🌐 Step 5: Configure Domain & SSL (Production)

### Option A: Using Nginx Reverse Proxy (Recommended)

#### 5.1 Install Nginx on Host

```bash
sudo apt install -y nginx
```

#### 5.2 Create Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/absensi
```

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/absensi /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

#### 5.3 Install SSL with Let's Encrypt

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Follow prompts:
# - Enter email
# - Agree to terms
# - Choose redirect HTTP to HTTPS (recommended)

# Certbot will automatically:
# - Generate SSL certificate
# - Update Nginx config
# - Setup auto-renewal

# Test auto-renewal
sudo certbot renew --dry-run
```

#### 5.4 Update Application .env

```bash
nano .env

# Update base URL
app.baseURL='https://yourdomain.com/'
app.forceGlobalSecureRequests=true
```

```bash
# Restart container to apply changes
docker compose restart app
```

### Option B: Using Cloudflare (Alternative)

1. Point your domain to VPS IP in DNS
2. Enable Cloudflare proxy (orange cloud)
3. Set SSL/TLS to "Flexible" or "Full"
4. Access via https://yourdomain.com

---

## 🔐 Step 6: Security Hardening

### 6.1 Configure Firewall

```bash
# Install UFW if not installed
sudo apt install -y ufw

# Allow SSH (IMPORTANT: do this first!)
sudo ufw allow 22/tcp

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Optional: Allow Docker ports only from localhost
# (if using Nginx reverse proxy)
sudo ufw allow from 127.0.0.1 to any port 8080
sudo ufw allow from 127.0.0.1 to any port 8081

# Or allow from any IP (less secure)
# sudo ufw allow 8080/tcp
# sudo ufw allow 8081/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

### 6.2 Disable PhpMyAdmin (Production)

For production, disable PhpMyAdmin to reduce attack surface:

```bash
# Method 1: Stop container
docker compose stop phpmyadmin

# Method 2: Remove from docker-compose.yml
nano docker-compose.yml
# Comment out or remove phpmyadmin service

# Restart
docker compose up -d
```

### 6.3 Change Default Database Port

```bash
nano docker-compose.yml

# Change MySQL port to non-standard
db:
  ports:
    - "127.0.0.1:3306:3306"  # Only accessible from localhost

# Or use different port
    - "127.0.0.1:3307:3306"
```

### 6.4 Setup Fail2Ban (Optional but Recommended)

```bash
# Install Fail2Ban
sudo apt install -y fail2ban

# Copy default config
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local

# Edit configuration
sudo nano /etc/fail2ban/jail.local

# Enable SSH protection (find [sshd] section)
[sshd]
enabled = true
port = 22
maxretry = 3
bantime = 3600

# Start and enable
sudo systemctl start fail2ban
sudo systemctl enable fail2ban

# Check status
sudo fail2ban-client status
```

---

## 📊 Step 7: Monitoring & Maintenance

### 7.1 View Logs

```bash
# All container logs
docker compose logs -f

# Specific container
docker compose logs -f app
docker compose logs -f db

# Last 100 lines
docker compose logs --tail=100 app

# Application logs
docker compose exec app tail -f /var/www/html/writable/logs/log-*.log
```

### 7.2 Monitor Resources

```bash
# Container resource usage
docker stats

# Disk usage
df -h
docker system df

# Memory usage
free -h

# Process list
top
htop  # if installed
```

### 7.3 Database Backup

**Manual Backup:**
```bash
cd /home/absensi-kelas
./docker/scripts/backup-db.sh
```

**Automated Backup with Cron:**
```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * cd /home/absensi-kelas && ./docker/scripts/backup-db.sh >> /var/log/backup.log 2>&1

# Weekly cleanup (keep last 30 backups)
0 3 * * 0 find /home/absensi-kelas/backups -name "*.sql" -mtime +30 -delete
```

### 7.4 Application Updates

```bash
# SSH to VPS
ssh root@your-vps-ip
cd /home/absensi-kelas

# Backup database first!
./docker/scripts/backup-db.sh

# Pull latest code
git pull origin main

# Rebuild containers
docker compose down
docker compose up -d --build

# Run migrations if any
docker compose exec app php spark migrate

# Clear cache
docker compose exec app php spark cache:clear
```

---

## 🔄 Step 8: Automatic Container Restart

Ensure containers restart after server reboot:

```bash
# Check restart policy
docker compose ps

# Should show "unless-stopped" for restart policy

# Or manually update
nano docker-compose.yml

services:
  app:
    restart: unless-stopped  # This ensures auto-restart
  db:
    restart: unless-stopped
  phpmyadmin:
    restart: unless-stopped
```

---

## 🐛 Troubleshooting

### Container Won't Start

```bash
# Check logs
docker compose logs app
docker compose logs db

# Common issues:
# 1. Port already in use → change ports in docker-compose.yml
# 2. Permission issues → check writable folder permissions
# 3. Memory issues → increase VPS RAM or add swap
```

### Database Connection Error

```bash
# Ensure database is running
docker compose ps

# Test connection
docker compose exec db mysql -uroot -pYOUR_PASSWORD -e "SHOW DATABASES;"

# Check .env settings
cat .env | grep database

# Should be:
# database.default.hostname = db  (NOT localhost!)
```

### Cannot Access Application

```bash
# Check if containers are running
docker compose ps

# Check firewall
sudo ufw status

# Check Nginx (if using)
sudo systemctl status nginx
sudo nginx -t

# Check application logs
docker compose logs app

# Test locally on VPS
curl http://localhost:8080
```

### High Memory Usage

```bash
# Check usage
docker stats

# Add swap space (if RAM < 2GB)
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile

# Make permanent
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

### Disk Space Full

```bash
# Check disk usage
df -h

# Clean Docker
docker system prune -a
docker volume prune

# Clean old backups
find /home/absensi-kelas/backups -name "*.sql" -mtime +30 -delete

# Clean logs
sudo journalctl --vacuum-time=7d
```

---

## 📝 Maintenance Checklist

### Daily:
- [ ] Check application is accessible
- [ ] Monitor error logs

### Weekly:
- [ ] Check disk space
- [ ] Review application logs
- [ ] Verify backups are running

### Monthly:
- [ ] Update system packages: `sudo apt update && sudo apt upgrade`
- [ ] Update Docker images: `docker compose pull && docker compose up -d`
- [ ] Review security logs
- [ ] Test backup restore process

---

## 🎯 Quick Commands Reference

```bash
# Start all services
docker compose up -d

# Stop all services
docker compose down

# Restart services
docker compose restart

# View logs
docker compose logs -f app

# Execute command in container
docker compose exec app bash
docker compose exec app php spark list

# Backup database
./docker/scripts/backup-db.sh

# Restore database
./docker/scripts/restore-db.sh backups/backup.sql

# Update application
git pull && docker compose up -d --build

# Clear cache
docker compose exec app php spark cache:clear
```

---

## 🔗 Access Points

After deployment, your application will be accessible at:

- **Without Domain:**
  - Application: `http://VPS-IP:8080`
  - PhpMyAdmin: `http://VPS-IP:8081`

- **With Domain (no SSL):**
  - Application: `http://yourdomain.com`
  - PhpMyAdmin: `http://yourdomain.com:8081`

- **With Domain + SSL:**
  - Application: `https://yourdomain.com`
  - PhpMyAdmin: Access via SSH tunnel (more secure)

---

## 📞 Support

If you encounter issues:

1. Check logs: `docker compose logs`
2. Check this troubleshooting guide
3. Verify all steps were followed
4. Check system resources (RAM, disk, CPU)

---

## ✅ Deployment Complete!

Your application is now live on VPS! 🎉

Next steps:
1. Test all features
2. Setup regular backups
3. Monitor performance
4. Configure monitoring alerts (optional)
