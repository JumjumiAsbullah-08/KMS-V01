<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id']; // ID user yang sedang login

// Ambil jumlah pesan yang belum dibaca oleh user yang sedang login
$query = "SELECT COUNT(*) AS count FROM chats WHERE recipient_id = '$user_id' AND is_read = 0";
$result = mysqli_query($connection, $query);

if (!$result) {
    die(json_encode(["status" => "error", "message" => mysqli_error($connection)]));
}

$row = mysqli_fetch_assoc($result);
echo json_encode(["count" => $row ? $row['count'] : 0]);
?>
