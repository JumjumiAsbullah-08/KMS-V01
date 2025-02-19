<?php
include '../../database/koneksi.php';
session_start();

header('Content-Type: application/json'); // Pastikan output adalah JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Debug: Cek koneksi database
if (!$connection) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()]);
    exit;
}

// Tandai hanya pesan yang dikirim kepada user saat ini sebagai dibaca
$query = "UPDATE chats SET is_read = 1 WHERE is_read = 0 AND receiver_id = ?";
$stmt = $connection->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query error: ' . $connection->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$success = $stmt->execute();

if (!$success) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status pesan: ' . $stmt->error]);
    exit;
}

echo json_encode(['status' => 'success']);
?>
