<?php
include '../database/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Query untuk menghapus pengguna berdasarkan ID
    $queryDelete = "DELETE FROM dokumen WHERE id_dokumen = ?";
    $stmt = $connection->prepare($queryDelete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Dokumen berhasil dihapus."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus Dokumen."]);
    }

    $stmt->close();
    $connection->close();
}
?>
