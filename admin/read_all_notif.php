<?php
session_start();
include '../database/koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode(['status'=>'error', 'message'=>'Session tidak lengkap']);
    exit;
}

$user_id         = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];

// Dapatkan semua id notifikasi dari notif_users yang bukan milik user tersebut
$sql = "SELECT id FROM notif_users WHERE created_by <> '$currentUsername'";
$result = $connection->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
         $notif_id = $row['id'];
         $check = "SELECT id FROM notif_read WHERE notif_table = 'notif_users' AND notif_id = $notif_id AND user_id = $user_id";
         $checkResult = $connection->query($check);
         if ($checkResult && $checkResult->num_rows == 0) {
             $insert = "INSERT INTO notif_read (notif_table, notif_id, user_id, read_status, read_at) VALUES ('notif_users', $notif_id, $user_id, 1, NOW())";
             $connection->query($insert);
         } else {
             $update = "UPDATE notif_read SET read_status = 1, read_at = NOW() WHERE notif_table = 'notif_users' AND notif_id = $notif_id AND user_id = $user_id";
             $connection->query($update);
         }
    }
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error', 'message'=>$connection->error]);
}
?>
