<?php
// fetch_daily_data.php
include '../database/koneksi.php';
header('Content-Type: application/json');

// Ambil parameter bulan dan tahun dari GET; jika tidak disediakan, gunakan bulan dan tahun saat ini
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Tentukan jumlah hari di bulan tersebut
$lastDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Inisialisasi array untuk setiap hari (indeks 0 untuk hari 1, dst.)
$days = range(1, $lastDay);
$users = array_fill(0, $lastDay, 0);
$dokumen = array_fill(0, $lastDay, 0);
$categories = array_fill(0, $lastDay, 0);

// Query untuk Users per hari
$queryUsers = "SELECT DAY(created_at) AS day, COUNT(*) AS count FROM users WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DAY(created_at)";
if($stmt = $connection->prepare($queryUsers)){
  $stmt->bind_param("ii", $month, $year);
  $stmt->execute();
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()){
    $day = intval($row['day']);
    $users[$day - 1] = intval($row['count']);
  }
  $stmt->close();
}

// Query untuk Dokumen per hari
$queryDokumen = "SELECT DAY(created_at) AS day, COUNT(*) AS count FROM dokumen WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DAY(created_at)";
if($stmt = $connection->prepare($queryDokumen)){
  $stmt->bind_param("ii", $month, $year);
  $stmt->execute();
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()){
    $day = intval($row['day']);
    $dokumen[$day - 1] = intval($row['count']);
  }
  $stmt->close();
}

// Query untuk Categories per hari
$queryCategories = "SELECT DAY(created_at) AS day, COUNT(*) AS count FROM categories WHERE MONTH(created_at)=? AND YEAR(created_at)=? GROUP BY DAY(created_at)";
if($stmt = $connection->prepare($queryCategories)){
  $stmt->bind_param("ii", $month, $year);
  $stmt->execute();
  $result = $stmt->get_result();
  while($row = $result->fetch_assoc()){
    $day = intval($row['day']);
    $categories[$day - 1] = intval($row['count']);
  }
  $stmt->close();
}

$data = array(
  "days" => $days,
  "users" => $users,
  "dokumen" => $dokumen,
  "categories" => $categories
);

echo json_encode($data);
?>
