<?php
session_start(); // Memulai session untuk menyimpan notifikasi

include '../database/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password']; // Ambil password asli dari input form
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Hash password menggunakan bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Cek apakah username atau email sudah ada di database
    $sql_check = "SELECT username, email FROM users WHERE username = ? OR email = ?";
    $stmt_check = $connection->prepare($sql_check);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // Jika ada hasil, periksa apakah username/email sudah digunakan
    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        if ($row['username'] === $username && $row['email'] === $email) {
            echo json_encode(["status" => "error", "message" => "Username dan email sudah digunakan."]);
        } elseif ($row['username'] === $username) {
            echo json_encode(["status" => "error", "message" => "Username sudah digunakan."]);
        } elseif ($row['email'] === $email) {
            echo json_encode(["status" => "error", "message" => "Email sudah digunakan."]);
        }
    } else {
        // Jika username dan email tidak ada, lanjutkan untuk menyimpan data
        $sql_insert = "INSERT INTO users (username, password, email, role, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt_insert = $connection->prepare($sql_insert);
        $stmt_insert->bind_param("ssss", $username, $hashed_password, $email, $role);

        if ($stmt_insert->execute()) {
            // Menyimpan notifikasi dalam session dengan informasi username, role, dan waktu
            $_SESSION['notif'] = [
                'username' => $username,
                'role' => $role,
                'time' => date('Y-m-d H:i:s') // Waktu penambahan pengguna
            ];

            // Menyimpan notifikasi ke dalam tabel notifications
            $created_by = $_SESSION['username'] ?? 'System'; // Nama admin, diambil dari session jika tersedia
            $sql_notify = "INSERT INTO notif_users (username, role, created_by) VALUES (?, ?, ?)";
            $stmt_notify = $connection->prepare($sql_notify);
            $stmt_notify->bind_param("sss", $username, $role, $created_by);
            $stmt_notify->execute();
            $stmt_notify->close(); // Tutup statement notify

            echo json_encode(["status" => "success", "message" => "Data berhasil disimpan."]);
        } else {
            // Menyimpan pesan error jika gagal
            $_SESSION['notif'] = "Gagal menambahkan pengguna.";
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan data."]);
        }        

        $stmt_insert->close(); // Tutup statement insert
    }

    $stmt_check->close(); // Tutup pemeriksaan username/email
    $connection->close(); // Tutup koneksi
}
?>
