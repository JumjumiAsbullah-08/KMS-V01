<?php
include '../../database/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id']; // ID user yang login
$contact_id = isset($_GET['contact_id']) ? $_GET['contact_id'] : null; // ID lawan chat

if (!$contact_id) {
    echo json_encode(["status" => "error", "message" => "Contact ID required"]);
    exit;
}

// Ambil pesan antara user dan contact_id
$query = "SELECT * FROM chats WHERE (sender_id = '$user_id' AND recipient_id = '$contact_id') 
          OR (sender_id = '$contact_id' AND recipient_id = '$user_id') ORDER BY created_at ASC";

$result = mysqli_query($connection, $query);
$messages = [];

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

// Hanya update `is_read = 1` jika **pengguna mengirim pesan**
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateQuery = "UPDATE chats SET is_read = 1 WHERE recipient_id = '$user_id' AND sender_id = '$contact_id'";
    mysqli_query($connection, $updateQuery);
}

echo json_encode($messages);
?>
