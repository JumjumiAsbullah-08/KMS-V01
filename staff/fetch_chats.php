<?php
// fetch_chats.php
include "../database/koneksi.php";

// Ambil data chats (misal 50 data terbaru)
$query = "SELECT * FROM chats ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);

$chats = array();
while ($row = mysqli_fetch_assoc($result)) {
    $chats[] = $row;
}

header('Content-Type: application/json');
echo json_encode($chats);
?>
