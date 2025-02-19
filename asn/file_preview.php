<?php
// file_preview.php
// session_start();

// include "../database/koneksi.php";

// // Lakukan pengecekan autentikasi jika diperlukan
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header('HTTP/1.1 403 Forbidden');
//     exit('Akses ditolak.');
// }

if (!isset($_GET['file'])) {
    header('HTTP/1.1 400 Bad Request');
    exit('Parameter file diperlukan.');
}

$file = basename($_GET['file']); // Hindari manipulasi path
$filepath = __DIR__ . '/../uploads/' . $file;

if (!file_exists($filepath)) {
    header('HTTP/1.1 404 Not Found');
    exit('File tidak ditemukan.');
}

// Sesuaikan header konten berdasarkan ekstensi file
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
switch ($ext) {
    case 'docx':
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        break;
    case 'xlsx':
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        break;
    default:
        header('Content-Type: application/octet-stream');
        break;
}

readfile($filepath);
?>
