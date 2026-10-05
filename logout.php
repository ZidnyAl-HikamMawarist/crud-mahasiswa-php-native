<?php
// ==========================================================
// FILE: logout.php
// FUNGSI: Menghapus sesi login dan kembali ke login.php
// ==========================================================

session_start();
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
