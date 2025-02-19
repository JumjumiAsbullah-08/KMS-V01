<?php
// getNotifications.php
session_start();
include '../../database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$currentUserId = $_SESSION['user_id'];
$query         = "SELECT COUNT(*) as cnt FROM chats WHERE recipient_id = $currentUserId AND is_read = 0";
$result        = mysqli_query($connection, $query);
$row           = mysqli_fetch_assoc($result);
echo json_encode(['count' => (int)$row['cnt']]);
?>
