<?php
include '../database/koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM notif_kategori WHERE id = $id";
    if ($connection->query($sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $connection->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID notifikasi tidak ditemukan.']);
}
?>
