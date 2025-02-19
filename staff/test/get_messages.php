<?php
session_start();
include '../../database/koneksi.php';

$user_id = $_SESSION['user_id']; // User yang login

$sql = "SELECT * FROM chats WHERE JSON_CONTAINS(recipient_id, ?) OR sender_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("si", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$chats = [];
while ($row = $result->fetch_assoc()) {
    $row['recipient_id'] = json_decode($row['recipient_id']); // Decode JSON ke array
    $chats[] = $row;
}

echo json_encode($chats);
?>
