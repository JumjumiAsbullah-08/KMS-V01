<?php
include '../database/koneksi.php';

if (isset($_POST['id_kategori'])) {
    $id_kategori = $_POST['id_kategori'];

    $query = "SELECT topik, fungsi, deskripsi, tanggal_periode FROM categories WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode($result ? ["status" => "success"] + $result : ["status" => "error"]);
}
?>
