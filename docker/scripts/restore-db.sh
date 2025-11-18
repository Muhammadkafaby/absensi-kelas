#!/bin/bash
# Restore Database Script for Absensi Kelas Docker

CONTAINER_NAME="absensi_db"
DB_NAME="absensi_kelas"
DB_USER="root"
DB_PASSWORD="secret"

# Check if backup file is provided
if [ -z "$1" ]; then
    echo "❌ Error: No backup file specified!"
    echo ""
    echo "Usage: $0 <backup_file.sql>"
    echo ""
    echo "Available backups:"
    ls -lh ./backups/*.sql 2>/dev/null || echo "  No backups found in ./backups/"
    exit 1
fi

BACKUP_FILE=$1

# Check if backup file exists
if [ ! -f "$BACKUP_FILE" ]; then
    echo "❌ Error: Backup file '$BACKUP_FILE' not found!"
    exit 1
fi

# Check if container is running
if [ ! "$(docker ps -q -f name=$CONTAINER_NAME)" ]; then
    echo "❌ Error: Container '$CONTAINER_NAME' is not running!"
    echo "Start it with: docker-compose up -d"
    exit 1
fi

# Confirm restore
echo "⚠️  WARNING: This will replace the current database!"
echo "📁 Backup file: $BACKUP_FILE"
echo "🗄️  Database: $DB_NAME"
echo ""
read -p "Are you sure you want to continue? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "❌ Restore cancelled."
    exit 0
fi

# Perform restore
echo "🔄 Restoring database..."
docker exec -i $CONTAINER_NAME mysql -u$DB_USER -p$DB_PASSWORD $DB_NAME < $BACKUP_FILE

# Check if restore was successful
if [ $? -eq 0 ]; then
    echo "✅ Database restored successfully!"
    echo "📁 From: $BACKUP_FILE"
else
    echo "❌ Restore failed!"
    exit 1
fi
