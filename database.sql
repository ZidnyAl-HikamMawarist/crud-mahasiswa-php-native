-- ===================================================
-- DATABASE & TABEL SKENARIO MAHASISWA & USER LOGIN
-- Jalankan query ini di phpMyAdmin / HeidiSQL
-- ===================================================

-- 1. Buat database baru jika belum ada
CREATE DATABASE IF NOT EXISTS db_akademik;
USE db_akademik;

-- 2. Buat tabel users untuk autentikasi login
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Akun Default: username = admin | password = admin123
INSERT IGNORE INTO users (id, username, password, nama) VALUES
(1, 'admin', MD5('admin123'), 'Administrator');

-- 3. Buat tabel mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Masukkan data awal (Dummy data untuk pengujian)
INSERT INTO mahasiswa (nim, nama, jurusan, jenis_kelamin, alamat) VALUES
('202601001', 'Ahmad Fauzi', 'Teknik Informatika', 'Laki-laki', 'Jl. Merdeka No. 45, Bandung'),
('202601002', 'Siti Rahmawati', 'Sistem Informasi', 'Perempuan', 'Jl. Sudirman No. 12, Jakarta'),
('202601003', 'Budi Pratama', 'Rekayasa Perangkat Lunak', 'Laki-laki', 'Jl. Diponegoro No. 88, Surabaya');
