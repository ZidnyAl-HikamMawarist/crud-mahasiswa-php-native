<?php
// ==========================================================
// FILE: koneksi.php
// FUNGSI: Menghubungkan script PHP dengan database MySQL
// ==========================================================

// 1. Konfigurasi kredensial database (Sesuaikan dengan Laragon / XAMPP)
$host = "localhost";    // Nama host server database
$user = "root";         // Username default MySQL di Laragon/XAMPP
$pass = "";             // Password default MySQL (biasanya kosong di lokal)
$db   = "db_akademik";   // Nama database yang kita tuju

// 2. Membuka koneksi menggunakan fungsi bawaan PHP: mysqli_connect()
$koneksi = mysqli_connect($host, $user, $pass, $db);

// 3. Cek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    // Jika koneksi gagal, hentikan eksekusi script dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Catatan: Jika tidak ada error, variabel $koneksi siap digunakan di file lain
// dengan cara: require_once 'koneksi.php'; atau include 'koneksi.php';
?>
