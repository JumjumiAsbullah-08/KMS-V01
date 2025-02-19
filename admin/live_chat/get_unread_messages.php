<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 🔹 Ambil jumlah pesan yang belum dibaca dan bukan dari user sendiri
$stmt = $connection->prepare("SELECT COUNT(*) AS unread_count FROM chats WHERE is_read = 0 AND sender_id != ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['status' => 'success', 'unread_count' => $row['unread_count']]);
?>
