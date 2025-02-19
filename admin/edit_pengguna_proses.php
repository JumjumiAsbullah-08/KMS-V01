<?php
include '../database/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validasi jika username atau email sudah ada (kecuali milik pengguna ini sendiri)
    $queryCheck = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
    $stmtCheck = $connection->prepare($queryCheck);
    $stmtCheck->bind_param("ssi", $username, $email, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username atau email sudah digunakan."]);
    } else {
        // Update data pengguna
        $queryUpdate = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
        $stmtUpdate = $connection->prepare($queryUpdate);
        $stmtUpdate->bind_param("sssi", $username, $email, $role, $id);

        if ($stmtUpdate->execute()) {
            echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui data."]);
        }

        $stmtUpdate->close();
    }

    $stmtCheck->close();
    $connection->close();
}
?>
