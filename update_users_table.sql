-- Script untuk menambahkan kolom photo dan contact ke tabel users yang sudah ada
ALTER TABLE users ADD COLUMN photo VARCHAR(255) NULL AFTER unit_kerja_id;
ALTER TABLE users ADD COLUMN contact VARCHAR(50) NULL AFTER photo;
