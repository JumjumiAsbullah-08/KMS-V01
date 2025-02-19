<?php
// Koneksi ke database
include '../database/koneksi.php';
session_start(); // Pastikan session sudah dimulai

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id_kategori = $_POST['name'];
    $description = $_POST['description'];
    $topik = $_POST['topik'];
    $fungsi = $_POST['fungsi'];
    $tanggal_periode = $_POST['tanggal_periode'];
    $access_level = $_POST['access_level'];
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        $response['status'] = 'error';
        $response['message'] = 'Anda harus login terlebih dahulu.';
        echo json_encode($response);
        exit;
    }

    $uploaded_by = $_SESSION['user_id']; // ID pengguna yang mengunggah

    // Cek apakah ada file yang diunggah
    if (!isset($_FILES['file_path']) || $_FILES['file_path']['error'] !== 0) {
        $response['status'] = 'error';
        $response['message'] = 'File belum dipilih atau terdapat kesalahan.';
        echo json_encode($response);
        exit;
    }

    // Informasi file
    $fileTmpPath = $_FILES['file_path']['tmp_name'];
    $fileName = $_FILES['file_path']['name'];
    $fileSize = $_FILES['file_path']['size'];
    $fileType = $_FILES['file_path']['type'];

    // Ambil nama kategori dari database
    $queryKategori = "SELECT name FROM categories WHERE id = ?";
    if ($stmtKategori = $connection->prepare($queryKategori)) {
        $stmtKategori->bind_param('i', $id_kategori);
        $stmtKategori->execute();
        $stmtKategori->bind_result($nama_kategori);
        $stmtKategori->fetch();
        $stmtKategori->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Gagal mengambil data kategori: ' . $connection->error;
        echo json_encode($response);
        exit;
    }

    // Bersihkan nama kategori untuk dijadikan bagian dari nama file
    $nama_kategori = str_replace(' ', '_', strtolower($nama_kategori));

    // Tentukan nama file baru
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = $nama_kategori . '_' . time() . '.' . $fileExtension;
    $uploadDir = '../uploads/';
    $filePath = $uploadDir . $newFileName;

    // Pastikan direktori upload ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Pindahkan file ke direktori tujuan
    if (!move_uploaded_file($fileTmpPath, $filePath)) {
        $response['status'] = 'error';
        $response['message'] = 'Gagal mengunggah file.';
        echo json_encode($response);
        exit;
    }

    // Simpan data dokumen ke database
    $query = "INSERT INTO dokumen (id_kategori, name, file_path, file_type, file_size, access_level, password, uploaded_by, created_at, updated_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param(
            'isssssss',
            $id_kategori,
            $newFileName,
            $filePath,
            $fileType,
            $fileSize,
            $access_level,
            $password,
            $uploaded_by
        );

        if ($stmt->execute()) {
            $created_by = $_SESSION['username'];

            // Simpan notifikasi ke dalam tabel notif_dokumen
            $sql_notify = "INSERT INTO notif_dokumen (name, access_level, created_by) VALUES (?, ?, ?)";
            if ($stmt_notify = $connection->prepare($sql_notify)) {
                $stmt_notify->bind_param("sss", $newFileName, $access_level, $created_by);
                $stmt_notify->execute();
                $stmt_notify->close();
            }

            $response['status'] = 'success';
            $response['message'] = 'Dokumen berhasil diunggah!';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Gagal menyimpan data: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Gagal menyiapkan query: ' . $connection->error;
    }
}

// Kirim response JSON
echo json_encode($response);
?>
