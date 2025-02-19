<?php
// fetch_data.php
include "../database/koneksi.php";
header('Content-Type: application/json');

// Ambil data Users
$users = array();
$query_users = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 50";
$result_users = mysqli_query($connection, $query_users);
if ($result_users) {
    while ($row = mysqli_fetch_assoc($result_users)) {
        $users[] = $row;
    }
} else {
    $users = array("error" => mysqli_error($connection));
}

// Ambil data Categories
$categories = array();
$query_categories = "SELECT id, name, description, topik, fungsi, tanggal_periode, created_at FROM categories ORDER BY created_at DESC LIMIT 50";
$result_categories = mysqli_query($connection, $query_categories);
if ($result_categories) {
    while ($row = mysqli_fetch_assoc($result_categories)) {
        $categories[] = $row;
    }
} else {
    $categories = array("error" => mysqli_error($connection));
}

// Ambil data Dokumen
$dokumen = array();
$query_dokumen = "SELECT id_dokumen, name, file_path, file_type, file_size, access_level, uploaded_by, created_at, updated_at FROM dokumen ORDER BY created_at DESC LIMIT 50";
$result_dokumen = mysqli_query($connection, $query_dokumen);
if ($result_dokumen) {
    while ($row = mysqli_fetch_assoc($result_dokumen)) {
        $dokumen[] = $row;
    }
} else {
    $dokumen = array("error" => mysqli_error($connection));
}

// Ambil data Chats
$chats = array();
$query_chats = "SELECT id, sender_id, recipient_id, message, is_read, created_at FROM chats ORDER BY created_at DESC LIMIT 50";
$result_chats = mysqli_query($connection, $query_chats);
if ($result_chats) {
    while ($row = mysqli_fetch_assoc($result_chats)) {
        $chats[] = $row;
    }
} else {
    $chats = array("error" => mysqli_error($connection));
}

// Gabungkan semua data ke dalam satu array
$data = array(
    "users" => $users,
    "categories" => $categories,
    "dokumen" => $dokumen,
    "chats" => $chats
);

echo json_encode($data);
?>
