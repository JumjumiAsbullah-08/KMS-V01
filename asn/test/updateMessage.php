<?php
// updateMessage.php
session_start();
include '../../database/koneksi.php'; // sesuaikan path koneksi dengan struktur proyek Anda

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$currentUserId = $_SESSION['user_id'];
$action        = $_POST['action'] ?? '';
$chat_id       = intval($_POST['chat_id'] ?? 0);

if ($chat_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Chat ID tidak valid']);
    exit;
}

// Ambil data pesan: sender_id, created_at, dan isi pesan asli (untuk membedakan pesan secara global)
$query = "SELECT sender_id, created_at, message FROM chats WHERE id = ?";
$stmt  = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $chat_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $sender_id, $created_at, $original_message);
if (!mysqli_stmt_fetch($stmt)) {
    echo json_encode(['status' => 'error', 'message' => 'Pesan tidak ditemukan.']);
    exit;
}
mysqli_stmt_close($stmt);

// Hanya pengirim yang boleh mengubah pesannya
if ($sender_id != $currentUserId) {
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengubah pesan ini.']);
    exit;
}

if ($action === 'delete') {
    $deletedText = "🚫 Pesan dihapus";
    // Update semua record dengan sender_id dan created_at yang sama
    $updateQuery = "UPDATE chats SET message = ? WHERE sender_id = ? AND created_at = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sis", $deletedText, $currentUserId, $created_at);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan dihapus.', 'deletedText' => $deletedText]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pesan.']);
    }
    mysqli_stmt_close($stmt);

} elseif ($action === 'edit') {
    $newMessage = trim($_POST['new_message'] ?? '');
    if ($newMessage === '') {
        echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong.']);
        exit;
    }
    // Update semua record dengan sender_id dan created_at yang sama
    $updateQuery = "UPDATE chats SET message = ? WHERE sender_id = ? AND created_at = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sis", $newMessage, $currentUserId, $created_at);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil diubah.', 'newMessage' => $newMessage]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengedit pesan.']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
}
?>
