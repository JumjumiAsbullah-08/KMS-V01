<?php
// sendMessage.php
session_start();
include '../../database/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$currentUserId   = $_SESSION['user_id'];
$currentUserRole = $_SESSION['role'];

$message = trim($_POST['message'] ?? '');
if ($message == '') {
    echo json_encode(['status' => 'error', 'message' => 'Pesan kosong']);
    exit;
}

// Ambil seluruh anggota grup (misalnya: admin, ASN, dan Staff)
$query  = "SELECT id, role FROM users WHERE role IN ('admin', 'ASN', 'Staff')";
$result = mysqli_query($connection, $query);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit;
}

// Insert pesan untuk setiap anggota
while ($row = mysqli_fetch_assoc($result)) {
    $recipientId = $row['id'];
    // Jika penerima adalah pengirim, tandai sebagai sudah dibaca
    $is_read = ($recipientId == $currentUserId) ? 1 : 0;
    
    $stmt = mysqli_prepare($connection, "INSERT INTO chats (sender_id, message, created_at, is_read, recipient_id) VALUES (?, ?, NOW(), ?, ?)");
    mysqli_stmt_bind_param($stmt, "isii", $currentUserId, $message, $is_read, $recipientId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// **Tambahan Logika:**
// Jika pesan masuk dibalas (artinya user mengirim pesan balasan),
// maka update seluruh pesan masuk (yang bukan dari dirinya sendiri) menjadi sudah dibaca.
// Dengan begitu, notifikasi untuk pesan masuk milik user yang membalas akan dikosongkan.
$updateQuery = "UPDATE chats SET is_read = 1 WHERE recipient_id = $currentUserId AND sender_id != $currentUserId";
mysqli_query($connection, $updateQuery);

echo json_encode(['status' => 'success']);
?>
