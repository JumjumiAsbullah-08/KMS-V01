<?php
include '../database/koneksi.php'; // Pastikan file koneksi database dimasukkan

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_dokumen'])) {
    $id_dokumen = intval($_POST['id_dokumen']); // Sanitasi input

    // Ambil data dari database
    $query = "SELECT name FROM dokumen WHERE id_dokumen = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id_dokumen);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();

    // Jika nama ditemukan, kirim response JSON
    if (!empty($name)) {
        echo json_encode(["success" => true, "name" => $name]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
