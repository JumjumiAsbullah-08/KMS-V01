<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 🔹 Hitung jumlah pesan belum dibaca
$stmt = $connection->prepare("SELECT COUNT(*) as unread_count FROM chat_read_status WHERE user_id = ? AND is_read = 0");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['status' => 'success', 'unread_count' => $row['unread_count']]);
?>
