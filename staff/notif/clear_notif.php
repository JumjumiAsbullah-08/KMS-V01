<?php
include '../../database/koneksi.php';

header('Content-Type: application/json'); // Pastikan response dalam format JSON

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah angka

    // Periksa apakah notifikasi dengan ID tersebut ada
    $check_sql = "SELECT * FROM notif_dokumen WHERE id = $id";
    $check_result = $connection->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Jika ada, hapus notifikasi
        $sql = "DELETE FROM notif_dokumen WHERE id = $id";
        if ($connection->query($sql)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $connection->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Notifikasi tidak ditemukan.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID notifikasi tidak ditemukan.']);
}
?>
