<?php
include '../../database/koneksi.php';
session_start();

// 🔹 Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login']);
    exit;
}

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message'] ?? '');

if (empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong']);
    exit;
}

// 🔹 Simpan pesan ke database
$stmt = $connection->prepare("INSERT INTO chats (sender_id, message, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $user_id, $message);

if ($stmt->execute()) {
    $chat_id = $stmt->insert_id;
    $stmt->close();

    // 🔹 Ambil semua user kecuali pengirim
    $stmtUsers = $connection->prepare("SELECT id FROM users WHERE id != ?");
    $stmtUsers->bind_param("i", $user_id);
    $stmtUsers->execute();
    $result = $stmtUsers->get_result();

    // 🔹 Tandai pesan belum dibaca untuk semua user lain
    while ($row = $result->fetch_assoc()) {
        $receiver_id = $row['id'];
        $stmtRead = $connection->prepare("INSERT INTO chat_read_status (chat_id, user_id, is_read) VALUES (?, ?, 0)");
        $stmtRead->bind_param("ii", $chat_id, $receiver_id);
        $stmtRead->execute();
        $stmtRead->close();
    }

    echo json_encode(['status' => 'success', 'message' => 'Pesan terkirim']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan']);
}
?>
