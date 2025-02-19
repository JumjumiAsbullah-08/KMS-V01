<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id']; // User yang membaca chat

$query = "UPDATE chats SET is_read = 1 WHERE recipient_id = '$user_id' AND is_read = 0";
mysqli_query($connection, $query);

echo json_encode(["status" => "success"]);
?>
