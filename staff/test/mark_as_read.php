<?php
session_start();
include '../../database/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$chat_id = isset($_POST['chat_id']) ? (int) $_POST['chat_id'] : 0;

if ($chat_id > 0) {
    $updateQuery = "UPDATE chats SET is_read = 1 WHERE id = '$chat_id' AND recipient_id = '$user_id'";
    mysqli_query($connection, $updateQuery);
}

echo json_encode(["success" => true]);
?>
