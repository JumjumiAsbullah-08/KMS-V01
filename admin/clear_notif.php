<?php
session_start();
include '../database/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>'error','message'=>'User tidak login']);
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $notifId = intval($_GET['id']);
    
    // Periksa apakah sudah ada record di notif_read untuk notifikasi ini bagi user tersebut
    $check_sql = "SELECT id FROM notif_read 
                  WHERE notif_table = 'notif_users' 
                    AND notif_id = $notifId 
                    AND user_id = $user_id";
    $check_result = $connection->query($check_sql);
    
    if ($check_result && $check_result->num_rows > 0) {
        $sql = "UPDATE notif_read 
                SET deleted_status = 1, read_status = 1, read_at = NOW() 
                WHERE notif_table = 'notif_users' 
                  AND notif_id = $notifId 
                  AND user_id = $user_id";
        if ($connection->query($sql)) {
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error', 'message'=>$connection->error]);
        }
    } else {
        $sql = "INSERT INTO notif_read (notif_table, notif_id, user_id, read_status, deleted_status, read_at)
                VALUES ('notif_users', $notifId, $user_id, 1, 1, NOW())";
        if ($connection->query($sql)) {
            echo json_encode(['status'=>'success']);
        } else {
            echo json_encode(['status'=>'error', 'message'=>$connection->error]);
        }
    }
} else {
    echo json_encode(['status'=>'error','message'=>'ID notifikasi tidak ditemukan.']);
}
?>
