<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT 
        chats.id, 
        chats.sender_id, 
        users.username, 
        users.role, 
        chats.message, 
        chats.created_at, 
        chats.is_read
    FROM chats 
    JOIN users ON chats.sender_id = users.id 
    ORDER BY chats.created_at ASC";

$result = $connection->query($query);
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>