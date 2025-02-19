<?php
session_start();
include "../database/koneksi.php";

$response = ['status' => 'error', 'message' => 'Terjadi kesalahan!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hindari langsung menggunakan data dari input tanpa sanitasi
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password']; // Tidak perlu escape karena langsung diverifikasi dengan password_verify

    // Query untuk mencari pengguna berdasarkan username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Memverifikasi password dengan hash bcrypt
        if (password_verify($password, $user['password'])) {
            // Simpan data ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            $response['status'] = 'success';
            $response['message'] = 'Login berhasil!';
            $response['redirect'] = "{$user['role']}/index.php";
        } else {
            $response['message'] = 'Password salah!';
        }
    } else {
        $response['message'] = 'Username tidak ditemukan!';
    }

    $stmt->close(); // Tutup statement
}

$connection->close(); // Tutup koneksi
echo json_encode($response);
?>
