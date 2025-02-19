<?php
// Konfigurasi koneksi ke database
$host = "localhost"; // Nama host (default: localhost)
$username = "root";  // Nama pengguna database (default: root)
$password = "";      // Password pengguna database (kosong jika default)
$database = "bps";   // Nama database

// Membuat koneksi ke database
$connection = mysqli_connect($host, $username, $password, $database);

// Mengecek koneksi
if (!$connection) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
} else {
    // echo "Koneksi berhasil!";
}

// Catatan: Hapus atau komentari echo di atas pada aplikasi produksi
?>
