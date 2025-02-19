# 📚 Knowledge Management System (KMS) - Document Management Based  

![License](https://img.shields.io/badge/License-MIT-blue.svg)
![Status](https://img.shields.io/badge/Status-Development-orange)
![Contributors](https://img.shields.io/github/contributors/yourusername/your-repo-name)

## 🚀 Tentang Proyek  
Aplikasi **Knowledge Management System** berbasis web ini dikembangkan menggunakan **PHP murni** untuk membantu organisasi dalam **mengelola, menyimpan, dan mencari dokumen** secara efisien menggunakan metode **Document Management**.  

🎯 **Fitur Utama:**  
✔️ Manajemen dokumen dengan kategori & tagging  
✔️ Pencarian dokumen berbasis kata kunci  
✔️ Akses kontrol pengguna & hak akses  
✔️ Sistem login dan registrasi user  
✔️ UI/UX yang responsif dan modern  

---

## 🛠️ Teknologi yang Digunakan  

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

---

## 📦 Instalasi & Penggunaan  

### 🔧 **1. Clone Repository**  
```bash
git clone https://github.com/yourusername/your-repo-name.git
cd your-repo-name
```
⚙️ 2. Konfigurasi Database
Buat database baru di phpMyAdmin atau MySQL
Import file database.sql yang ada di folder proyek
🔑 3. Konfigurasi Koneksi Database
Edit file koneksi.php dan sesuaikan dengan database yang digunakan:
```bash
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "nama_database";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

```
🚀 4. Jalankan Aplikasi
Pastikan XAMPP atau MAMP sudah berjalan
Pindahkan folder proyek ke dalam htdocs (untuk XAMPP)
Akses melalui browser:
```bash
http://localhost/nama-folder-proyek/
```
📜 Lisensi
Proyek ini dilisensikan di bawah MIT License. Silakan lihat LICENSE untuk detailnya.

📌 Follow saya di Instagram: @asbullahhr
