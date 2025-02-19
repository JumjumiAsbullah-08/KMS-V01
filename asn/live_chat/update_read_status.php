<?php
include '../../database/koneksi.php';
session_start();

// 🔹 Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];

// 🔹 Tandai semua pesan sebagai "dibaca" oleh user ini
$stmt = $connection->prepare("UPDATE chat_read_status SET is_read = 1 WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Pesan ditandai sebagai dibaca']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status baca']);
}
?>
