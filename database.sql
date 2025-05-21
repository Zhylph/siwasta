-- Membuat database
CREATE DATABASE IF NOT EXISTS signon_db;

-- Menggunakan database
USE signon_db;

-- Membuat tabel unit_kerja
CREATE TABLE IF NOT EXISTS unit_kerja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE,
    deskripsi TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuat tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    unit_kerja_id INT NULL,
    photo VARCHAR(255) NULL,
    contact VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (unit_kerja_id) REFERENCES unit_kerja(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuat tabel login_attempts
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45) NOT NULL,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Membuat tabel simrs_usage
CREATE TABLE IF NOT EXISTS simrs_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    unit_kerja_id INT NULL,
    ip_address VARCHAR(45) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NULL,
    status ENUM('active', 'closed') DEFAULT 'active',
    notes TEXT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (unit_kerja_id) REFERENCES unit_kerja(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Menambahkan data awal untuk unit kerja
INSERT INTO unit_kerja (nama, deskripsi) VALUES
('Kasir', 'Bagian yang menangani pembayaran'),
('Loket', 'Bagian yang menangani pendaftaran pasien'),
('Farmasi', 'Bagian yang menangani obat-obatan'),
('Rawat Inap', 'Bagian yang menangani pasien rawat inap'),
('Rawat Jalan', 'Bagian yang menangani pasien rawat jalan'),
('Laboratorium', 'Bagian yang menangani pemeriksaan laboratorium'),
('Radiologi', 'Bagian yang menangani pemeriksaan radiologi'),
('IGD', 'Instalasi Gawat Darurat'),
('Admin', 'Administrator Sistem');
