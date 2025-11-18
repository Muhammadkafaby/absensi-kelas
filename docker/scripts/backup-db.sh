#!/bin/bash
# Backup Database Script for Absensi Kelas Docker

BACKUP_DIR="./backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/absensi_kelas_$TIMESTAMP.sql"
CONTAINER_NAME="absensi_db"
DB_NAME="absensi_kelas"
DB_USER="root"
DB_PASSWORD="secret"

# Create backup directory if not exists
mkdir -p $BACKUP_DIR

# Check if container is running
if [ ! "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
    echo "❌ Error: Container '$CONTAINER_NAME' is not running!"
    echo "Start it with: docker-compose up -d"
    exit 1
fi

# Perform backup
echo "🔄 Creating database backup..."
docker exec $CONTAINER_NAME mysqldump -u$DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_FILE

# Check if backup was successful
if [ $? -eq 0 ]; then
    FILESIZE=$(stat -f%z "$BACKUP_FILE" 2>/dev/null || stat -c%s "$BACKUP_FILE" 2>/dev/null)
    echo "✅ Backup created successfully!"
    echo "📁 File: $BACKUP_FILE"
    echo "📊 Size: $(numfmt --to=iec-i --suffix=B $FILESIZE 2>/dev/null || echo $FILESIZE bytes)"
else
    echo "❌ Backup failed!"
    exit 1
fi

# Keep only last 10 backups
echo "🧹 Cleaning old backups (keeping last 10)..."
ls -t $BACKUP_DIR/absensi_kelas_*.sql | tail -n +11 | xargs -r rm
echo "✨ Done!"
