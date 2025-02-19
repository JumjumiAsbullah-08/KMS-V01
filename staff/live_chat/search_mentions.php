<?php
include '../../database/koneksi.php';

$search = $_GET['query'] ?? '';

if (empty($search)) {
    echo json_encode([]);
    exit;
}

// Log input untuk debugging
// file_put_contents("log.txt", "Search query: $search\n", FILE_APPEND);

// Cari user
$stmtUser = $connection->prepare("SELECT username FROM users WHERE username LIKE CONCAT('%', ?, '%')");
$stmtUser->bind_param("s", $search);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$users = [];
while ($row = $resultUser->fetch_assoc()) {
    $users[] = ["type" => "user", "name" => $row['username']];
}

// Cari dokumen
$stmtDoc = $connection->prepare("SELECT name FROM dokumen WHERE name LIKE CONCAT('%', ?, '%')");
$stmtDoc->bind_param("s", $search);
$stmtDoc->execute();
$resultDoc = $stmtDoc->get_result();
$documents = [];
while ($row = $resultDoc->fetch_assoc()) {
    $documents[] = ["type" => "document", "name" => $row['name']];
}

$result = array_merge($users, $documents);

// Log hasil untuk debugging
file_put_contents("log.txt", "Result: " . json_encode($result) . "\n", FILE_APPEND);

echo json_encode($result);
?>
