<?php
// Memulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menghapus semua data sesi
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: ../kms/index.php");
exit;
?>
