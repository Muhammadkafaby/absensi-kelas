-- MySQL Initialization Script for Absensi Kelas
-- This script is automatically executed when the MySQL container starts for the first time

-- Ensure database exists
CREATE DATABASE IF NOT EXISTS `absensi_kelas` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `absensi_kelas`;

-- Note: The actual database schema should be imported separately
-- You can place your database dump here or import it manually after container is running

-- Example: Grant privileges (already handled by Docker environment variables)
-- GRANT ALL PRIVILEGES ON absensi_kelas.* TO 'absensi_user'@'%';
-- FLUSH PRIVILEGES;
