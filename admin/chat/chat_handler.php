<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$sender_id = $_SESSION['user_id'];
$recipient_id = $_POST['recipient_id'];
$message = mysqli_real_escape_string($connection, $_POST['message']);
$timestamp = date('Y-m-d H:i:s');

// Simpan pesan baru
$query = "INSERT INTO chats (sender_id, recipient_id, message, created_at, is_read) VALUES ('$sender_id', '$recipient_id', '$message', '$timestamp', 0)";
mysqli_query($connection, $query);

// Jika pesan dikirim oleh **recipient sebelumnya**, tandai sebagai terbaca
$updateQuery = "UPDATE chats SET is_read = 1 WHERE sender_id = '$recipient_id' AND recipient_id = '$sender_id'";
mysqli_query($connection, $updateQuery);

echo json_encode(["status" => "success"]);
?>
