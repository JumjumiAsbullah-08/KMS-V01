<?php
session_start();
include '../../database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'];

// Reset jumlah notifikasi
$query = "UPDATE notifications SET count = 0 WHERE user_id = '$user_id'";
mysqli_query($connection, $query);
?>
