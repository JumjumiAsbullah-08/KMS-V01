<?php
include('../database/koneksi.php'); // Pastikan koneksi database sudah disiapkan

if (isset($_POST['password']) && isset($_POST['action']) && isset($_POST['id_dokumen'])) {
    $inputPassword = $_POST['password'];
    $action = $_POST['action'];
    $id_dokumen = $_POST['id_dokumen'];

    // Ambil password dari database berdasarkan id_dokumen
    $query = "SELECT password FROM dokumen WHERE id_dokumen = ?";
    $stmt = $connection->prepare($query);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed.']);
        exit;
    }
    $stmt->bind_param("i", $id_dokumen);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $correctPassword = $row['password']; // Password yang ada di database

        // Validasi password
        if ($inputPassword === $correctPassword) {
            echo json_encode(['status' => 'success', 'message' => 'Password benar.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Password salah!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dokumen tidak ditemukan!']);
    }

    exit;
}

// Jika request tidak valid
echo json_encode(['status' => 'error', 'message' => 'Request tidak valid.']);
?>