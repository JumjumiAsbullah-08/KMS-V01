<?php
// fetch_notifications.php
session_start();
include '../database/koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    echo json_encode([]);
    exit;
}

$user_id         = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];

/**
 * Fungsi helper untuk membangun query notifikasi dengan LEFT JOIN ke tabel notif_read.
 * Menambahkan field source agar JSON response menyertakan "source".
 */
function getNotificationsQuery($table, $fields, $source, $user_id, $currentUsername) {
    return "SELECT n.id, $fields, n.created_by, n.created_at,
            IF(r.read_status IS NULL, 0, r.read_status) AS read_status,
            '$source' AS source
            FROM $table n
            LEFT JOIN notif_read r ON r.notif_table = '$table'
                                    AND r.notif_id = n.id
                                    AND r.user_id = $user_id
            WHERE n.created_by <> '$currentUsername'
              AND (r.deleted_status IS NULL OR r.deleted_status = 0)
            ORDER BY n.created_at DESC";
}

$query1 = getNotificationsQuery("notif_users", "username, role", "user", $user_id, $currentUsername);
$query2 = getNotificationsQuery("notif_categories", "name, topik", "kategori", $user_id, $currentUsername);
$query3 = getNotificationsQuery("notif_dokumen", "name, access_level", "dokumen", $user_id, $currentUsername);

$notifUsers = [];
$result1 = $connection->query($query1);
if ($result1) {
    while ($row = $result1->fetch_assoc()) {
        $notifUsers[] = $row;
    }
}

$notifKategori = [];
$result2 = $connection->query($query2);
if ($result2) {
    while ($row = $result2->fetch_assoc()) {
        $notifKategori[] = $row;
    }
}

$notifDokumen = [];
$result3 = $connection->query($query3);
if ($result3) {
    while ($row = $result3->fetch_assoc()) {
        $notifDokumen[] = $row;
    }
}

$allNotifications = array_merge($notifUsers, $notifKategori, $notifDokumen);
usort($allNotifications, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

header('Content-Type: application/json');
echo json_encode($allNotifications);
?>