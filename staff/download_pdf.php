<?php
// download_pdf.php
require_once '../vendor/autoload.php';
use Dompdf\Dompdf;

include "../database/koneksi.php";

$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';
$start_date  = isset($_GET['start_date'])  ? $_GET['start_date']  : '';
$end_date    = isset($_GET['end_date'])    ? $_GET['end_date']    : '';

$data = array();
$html = "";

// Bangun konten HTML untuk laporan PDF
if ($report_type == 'users') {
    $query = "SELECT id, username, email, role, created_at FROM users";
    if ($start_date && $end_date) {
        $query .= " WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
    }
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    $html .= "<h1>Laporan Users</h1>";
    $html .= "<table border='1' style='width:100%;border-collapse:collapse;'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                </tr>";
    foreach ($data as $row) {
        $html .= "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['role']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
    }
    $html .= "</table>";
} elseif ($report_type == 'categories') {
    $query = "SELECT id, name, description, topik, fungsi, tanggal_periode, created_at FROM categories";
    if ($start_date && $end_date) {
        $query .= " WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
    }
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    $html .= "<h1>Laporan Kategori</h1>";
    $html .= "<table border='1' style='width:100%;border-collapse:collapse;'>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Topik</th>
                    <th>Fungsi</th>
                    <th>Tanggal Periode</th>
                    <th>Created At</th>
                </tr>";
    foreach ($data as $row) {
        $html .= "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['topik']}</td>
                    <td>{$row['fungsi']}</td>
                    <td>{$row['tanggal_periode']}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
    }
    $html .= "</table>";
} elseif ($report_type == 'dokumen') {
    $query = "SELECT d.id_dokumen, d.name, d.file_path, d.file_type, d.file_size, d.access_level, d.uploaded_by, 
                     d.created_at, d.updated_at, c.name AS category_name, c.topik, c.fungsi, c.tanggal_periode 
              FROM dokumen d 
              JOIN categories c ON d.id_kategori = c.id";
    if ($start_date && $end_date) {
        $query .= " WHERE DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'";
    }
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    $html .= "<h1>Laporan Dokumen</h1>";
    $html .= "<table border='1' style='width:100%;border-collapse:collapse;'>
                <tr>
                    <th>Name</th>
                    <th>File Path</th>
                    <th>File Size</th>
                    <th>Access Level</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Category Name</th>
                    <th>Topik</th>
                    <th>Fungsi</th>
                    <th>Tanggal Periode</th>
                </tr>";
    foreach ($data as $row) {
        $html .= "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['file_path']}</td>
                    <td>{$row['file_size']}</td>
                    <td>{$row['access_level']}</td>
                    <td>{$row['created_at']}</td>
                    <td>{$row['updated_at']}</td>
                    <td>{$row['category_name']}</td>
                    <td>{$row['topik']}</td>
                    <td>{$row['fungsi']}</td>
                    <td>{$row['tanggal_periode']}</td>
                  </tr>";
    }
    $html .= "</table>";
}

// Inisialisasi Dompdf dan generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Stream file PDF ke browser (Attachment = 1 artinya force download)
$dompdf->stream("laporan_$report_type.pdf", array("Attachment" => 1));
?>
