<?php
include '../database/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Query untuk menghapus pengguna berdasarkan ID
    $queryDelete = "DELETE FROM users WHERE id = ?";
    $stmt = $connection->prepare($queryDelete);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Pengguna berhasil dihapus."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus pengguna."]);
    }

    $stmt->close();
    $connection->close();
}
?>
