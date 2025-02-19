<?php
include '../database/koneksi.php';

$sql = "UPDATE notif_users SET read_status = 1 WHERE read_status = 0";
if ($connection->query($sql)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $connection->error]);
}
?>
