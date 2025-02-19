<?php
// notification_actions.php
session_start();
include '../database/koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'Session tidak lengkap']);
    exit;
}

$user_id         = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'read_all') {
    $tables = ['notif_users', 'notif_categories', 'notif_dokumen'];
    foreach ($tables as $table) {
        $sql = "SELECT id FROM $table WHERE created_by <> '$currentUsername'";
        $result = $connection->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notif_id = intval($row['id']);
                $insert_sql = "INSERT INTO notif_read (notif_table, notif_id, user_id, read_status, deleted_status, read_at)
                    VALUES ('$table', $notif_id, $user_id, 1, 0, NOW())
                    ON DUPLICATE KEY UPDATE read_status = 1, read_at = NOW()";
                $connection->query($insert_sql);
            }
        }
    }
    echo json_encode(['status' => 'success']);
    exit;
} elseif ($action === 'clear') {
    if (!isset($_GET['notif_id']) || !isset($_GET['notif_table'])) {
        echo json_encode(['status' => 'error', 'message' => 'Parameter notif_id dan notif_table diperlukan']);
        exit;
    }
    
    $notif_id = intval($_GET['notif_id']);
    $notif_table = strtolower(trim($_GET['notif_table']));
    
    $allowed = ['notif_users', 'notif_categories', 'notif_dokumen'];
    if (!in_array($notif_table, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'notif_table tidak valid. Diterima: ' . $notif_table]);
        exit;
    }
    
    $insert_sql = "INSERT INTO notif_read (notif_table, notif_id, user_id, read_status, deleted_status, read_at)
                   VALUES ('$notif_table', $notif_id, $user_id, 1, 1, NOW())
                   ON DUPLICATE KEY UPDATE deleted_status = 1, read_status = 1, read_at = NOW()";
    if ($connection->query($insert_sql)) {
        echo json_encode(['status' => 'success']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => $connection->error]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Action tidak valid']);
    exit;
}
?>
