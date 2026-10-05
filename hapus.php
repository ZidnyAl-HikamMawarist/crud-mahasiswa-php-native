<?php
// ==========================================================
// FILE: hapus.php
// FUNGSI: Menerima ID lewat URL, menjalankan query DELETE di MySQL,
//         dan mengembalikan pengguna ke halaman index.php
// ==========================================================

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// 1. Panggil koneksi ke database
require_once 'koneksi.php';

// 2. Periksa apakah parameter ID ada di URL (hapus.php?id=...)
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Amankan ID dari SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 3. Jalankan query DELETE
    $sql = "DELETE FROM mahasiswa WHERE id = '$id'";
    $hapus = mysqli_query($koneksi, $sql);

    // 4. Periksa apakah proses hapus berhasil
    if ($hapus) {
        // Jika berhasil, redirect ke index.php dengan notifikasi
        header("Location: index.php?pesan=berhasil_hapus");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error dari MySQL
        die("Gagal menghapus data: " . mysqli_error($koneksi));
    }
} else {
    // Jika file ini diakses langsung tanpa membawa ?id=..., kembalikan ke index.php
    header("Location: index.php");
    exit();
}
?>
