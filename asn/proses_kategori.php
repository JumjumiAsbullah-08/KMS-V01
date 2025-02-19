<?php
session_start(); // Memulai session untuk menyimpan notifikasi

include '../database/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "Anda belum login."]);
    exit;
}

// Debug: Pastikan koneksi database aktif
if (!$connection) {
    echo json_encode(["status" => "error", "message" => "Gagal terhubung ke database: " . mysqli_connect_error()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = $_POST['name'] ?? null;
    $description = $_POST['description'] ?? null;
    $topik = $_POST['topik'] ?? null;
    $fungsi = $_POST['fungsi'] ?? null;
    $tanggal_periode = $_POST['tanggal_periode'] ?? null;

    // Validasi data form
    if (empty($name) || empty($description) || empty($topik) || empty($fungsi) || empty($tanggal_periode)) {
        echo json_encode(["status" => "error", "message" => "Semua data harus diisi."]);
        exit;
    }

    // Cek apakah name atau topik sudah ada di database
    $sql_check = "SELECT name, topik FROM categories WHERE name = ? OR topik = ?";
    $stmt_check = $connection->prepare($sql_check);

    if (!$stmt_check) {
        echo json_encode(["status" => "error", "message" => "Gagal mempersiapkan query: " . $connection->error]);
        exit;
    }

    $stmt_check->bind_param("ss", $name, $topik);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Jenis Dokumen atau Topik sudah digunakan."]);
    } else {
        // Jika name dan topik tidak ada, lanjutkan untuk menyimpan data
        $sql_insert = "INSERT INTO categories (name, description, topik, fungsi, tanggal_periode, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt_insert = $connection->prepare($sql_insert);

        if (!$stmt_insert) {
            echo json_encode(["status" => "error", "message" => "Gagal mempersiapkan query insert: " . $connection->error]);
            exit;
        }

        $stmt_insert->bind_param("sssss", $name, $description, $topik, $fungsi, $tanggal_periode);

        if ($stmt_insert->execute()) {
            // Ambil username dari session
            $created_by = $_SESSION['username'];

            // Menyimpan notifikasi ke dalam tabel notif_kategori
            $sql_notify = "INSERT INTO notif_kategori (name, topik, created_by) VALUES (?, ?, ?)";
            $stmt_notify = $connection->prepare($sql_notify);

            if (!$stmt_notify) {
                echo json_encode(["status" => "error", "message" => "Gagal mempersiapkan query notifikasi: " . $connection->error]);
                exit;
            }

            $stmt_notify->bind_param("sss", $name, $topik, $created_by);

            if ($stmt_notify->execute()) {
                echo json_encode(["status" => "success", "message" => "Data berhasil disimpan."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menyimpan notifikasi: " . $stmt_notify->error]);
            }

            $stmt_notify->close(); // Tutup statement notify
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan data: " . $stmt_insert->error]);
        }

        $stmt_insert->close(); // Tutup statement insert
    }

    $stmt_check->close(); // Tutup pemeriksaan name/topik
    $connection->close(); // Tutup koneksi
}
?>
