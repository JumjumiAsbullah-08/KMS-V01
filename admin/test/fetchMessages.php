<?php
// fetchMessages.php
session_start();
include '../../database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$currentUserId = $_SESSION['user_id'];

// Jika parameter markRead=1 ada, update pesan yang belum dibaca
if (isset($_GET['markRead']) && $_GET['markRead'] == 1) {
    $updateQuery = "UPDATE chats SET is_read = 1 WHERE recipient_id = $currentUserId AND is_read = 0";
    mysqli_query($connection, $updateQuery);
}

// Ambil pesan untuk user saat ini, hanya mengambil waktu dari created_at
$query  = "SELECT c.*, u.username, DATE_FORMAT(c.created_at, '%H:%i') as time_only
           FROM chats c 
           LEFT JOIN users u ON c.sender_id = u.id 
           WHERE c.recipient_id = $currentUserId 
           ORDER BY c.created_at ASC";
$result = mysqli_query($connection, $query);

$chats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $chats[] = $row;
}

echo json_encode($chats);
?>
