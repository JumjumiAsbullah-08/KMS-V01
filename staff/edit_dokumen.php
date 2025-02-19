<?php
include '../database/koneksi.php';
session_start();

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id_dokumen']) || empty($_POST['id_dokumen'])) {
        $response['status'] = 'error';
        $response['message'] = 'ID dokumen tidak ditemukan.';
        echo json_encode($response);
        exit;
    }

    $id_dokumen = $_POST['id_dokumen'];
    $id_kategori = $_POST['id_kategori'];
    $name = $_POST['name'];
    $access_level = $_POST['access_level'];
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    // Ambil nama kategori berdasarkan id_kategori
    $queryKategori = "SELECT name FROM categories WHERE id = ?";
    if ($stmtKategori = $connection->prepare($queryKategori)) {
        $stmtKategori->bind_param("i", $id_kategori);
        $stmtKategori->execute();
        $stmtKategori->bind_result($kategoriName);
        $stmtKategori->fetch();
        $stmtKategori->close();
    }

    $kategoriFileName = !empty($kategoriName) ? str_replace(" ", "_", strtolower($kategoriName)) : "dokumen";

    // Ambil file lama dari database
    $queryGetOldFile = "SELECT name, file_path FROM dokumen WHERE id_dokumen = ?";
    if ($stmtOldFile = $connection->prepare($queryGetOldFile)) {
        $stmtOldFile->bind_param("i", $id_dokumen);
        $stmtOldFile->execute();
        $stmtOldFile->bind_result($oldFileName, $oldFilePath);
        $stmtOldFile->fetch();
        $stmtOldFile->close();
    }

    // Jika ada file baru yang diunggah
    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == 0) {
        $fileTmpPath = $_FILES['file_path']['tmp_name'];
        $fileName = $_FILES['file_path']['name'];
        $fileSize = $_FILES['file_path']['size'];
        $fileType = $_FILES['file_path']['type'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xlsx'];

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            $response['file_status'] = 'Gagal upload: Format file tidak diizinkan.';
            echo json_encode($response);
            exit;
        }

        $uploadDir = '../uploads/';
        $newFileName = $kategoriFileName . "_" . time() . "." . $fileExtension;
        $filePath = $uploadDir . $newFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Hapus file lama jika ada
        if (!empty($oldFilePath) && file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        // Pindahkan file baru ke folder uploads
        if (move_uploaded_file($fileTmpPath, $filePath)) {
            // Update file_path & dokumen_name di database nanti
            $queryUpdateFile = "UPDATE dokumen SET name = ?, file_path = ?, file_type = ?, file_size = ? WHERE id_dokumen = ?";
            if ($stmtFile = $connection->prepare($queryUpdateFile)) {
                $stmtFile->bind_param("sssii", $newFileName, $filePath, $fileType, $fileSize, $id_dokumen);
                $stmtFile->execute();
                $stmtFile->close();
            }
        }
    } else {
        // Jika tidak ada file baru yang diunggah,
        // kita ingin mengubah nama file sesuai kategori baru.
        // Pastikan file lama ada.
        if (!empty($oldFilePath) && file_exists($oldFilePath)) {
            // Dapatkan ekstensi file lama
            $oldExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
            // Buat nama file baru
            $newFileName = $kategoriFileName . "_" . time() . "." . $oldExtension;
            $uploadDir = '../uploads/';
            $newFilePath = $uploadDir . $newFileName;
            
            // Lakukan rename file di server
            if (rename($oldFilePath, $newFilePath)) {
                $filePath = $newFilePath;
            } else {
                // Jika gagal, tetap gunakan file lama
                $filePath = $oldFilePath;
                $newFileName = $oldFileName;
            }
        } else {
            $filePath = $oldFilePath;
            $newFileName = $oldFileName;
        }
    }

    // Query UPDATE data dokumen (nama file, kategori, access_level, password)
    $query = "UPDATE dokumen SET 
                id_kategori = ?, 
                name = ?, 
                access_level = ?, 
                password = ?, 
                updated_at = NOW()
            WHERE id_dokumen = ?";
    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param("isssi", $id_kategori, $newFileName, $access_level, $password, $id_dokumen);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Dokumen berhasil diperbarui.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Gagal memperbarui dokumen.';
        }
        $stmt->close();
    }

} else {
    $response['status'] = 'error';
    $response['message'] = 'Request tidak valid.';
}

// Kirim respons JSON
echo json_encode($response);
?>