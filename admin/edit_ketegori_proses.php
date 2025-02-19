<?php
include '../database/koneksi.php';

// Debugging: Pastikan koneksi berhasil
if (!$connection) {
    echo json_encode(["status" => "error", "message" => "Gagal terhubung ke database: " . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Pastikan data POST diterima
    if (!isset($_POST['id'], $_POST['name'], $_POST['description'], $_POST['topik'], $_POST['fungsi'], $_POST['tanggal_periode'])) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
        exit;
    }

    // Ambil data dari form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $topik = $_POST['topik'];
    $fungsi = $_POST['fungsi'];
    $tanggal_periode = $_POST['tanggal_periode'];

    // Validasi jika name atau topik sudah ada (kecuali milik pengguna ini sendiri)
    $queryCheck = "SELECT id FROM categories WHERE (name = ? OR topik = ?) AND id != ?";
    $stmtCheck = $connection->prepare($queryCheck);
    
    if (!$stmtCheck) {
        echo json_encode(["status" => "error", "message" => "Gagal mempersiapkan query check: " . $connection->error]);
        exit;
    }

    $stmtCheck->bind_param("ssi", $name, $topik, $id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Jenis Dokumen atau Topik sudah digunakan."]);
    } else {
        // Update data kategori
        $queryUpdate = "UPDATE categories SET name = ?, topik = ?, description = ?, fungsi = ?, tanggal_periode = ? WHERE id = ?";
        $stmtUpdate = $connection->prepare($queryUpdate);

        if (!$stmtUpdate) {
            echo json_encode(["status" => "error", "message" => "Gagal mempersiapkan query update: " . $connection->error]);
            exit;
        }

        $stmtUpdate->bind_param("sssssi", $name, $topik, $description, $fungsi, $tanggal_periode, $id);

        if (!$stmtUpdate->execute()) {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui data: " . $stmtUpdate->error]);
            exit;
        }

        echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui."]);
    }

    $stmtCheck->close();
    $stmtUpdate->close();
    $connection->close();
}
?>
