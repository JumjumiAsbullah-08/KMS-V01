<?php
session_start();
include "../database/koneksi.php";

// Periksa apakah admin telah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../index.php");
    exit;
}
// include "live_chat.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/logo.webp">
  <title>
    Staff BPS | Aceh Singkil
  </title>
  <!-- Pustaka CSS -->
<!-- Font Awesome Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- Google Fonts -->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

<!-- Nucleo Icons -->
<link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="../assets/css/nucleo-svg.css" rel="stylesheet" />

<!-- CSS untuk Material Dashboard -->
<link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />

<!-- Pustaka JavaScript -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- PDF.js (Untuk memproses dan menampilkan PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.worker.min.js"></script>

<!-- Mammoth (Untuk konversi dokumen Word) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.8/mammoth.browser.min.js"></script>

<!-- SheetJS (Untuk memproses file Excel .xls dan .xlsx) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<!-- Turn.js (Untuk tampilan flipbook) -->
<script src="../assets/turn/turn.min.js"></script>

<!-- Nepcha Analytics -->
<script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>
<style>
    #flipbook-modal .modal-dialog {
            max-width: 40%;
            width: 90%;
        }
        #flipbook-modal .modal-content {
            height: auto; /* Menyesuaikan tinggi dengan konten */
        }
        #magazine {
            width: 576px;
            height: 752px;
            margin: 0 auto; /* Center the flipbook */
        }
        #magazine .turn-page {
            background-color: #ccc;
            background-size: 100% 100%;
        }
        #docx-xlsx-content {
            max-height: 70vh; /* Batasi tinggi maksimal */
            overflow-y: auto; /* Tambahkan scroll jika konten terlalu panjang */
            padding: 20px;
        }

        #docx-xlsx-content table {
            width: 100%;
            border-collapse: collapse;
        }

        #docx-xlsx-content table, #docx-xlsx-content th, #docx-xlsx-content td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #docx-xlsx-content th {
            background-color: #f2f2f2;
        }
        /* Efek blur pada elemen selain SweetAlert2 */
        .blurred {
            filter: blur(5px);
            pointer-events: none; /* Menonaktifkan interaksi dengan elemen yang diblur */
            user-select: none; /* Menonaktifkan pemilihan teks */
            transition: filter 0.3s ease;
        }

        /* Pastikan SweetAlert2 tetap di atas dan tidak terpengaruh oleh efek blur */
        .swal2-container {
            z-index: 9999 !important; /* Memastikan SweetAlert tetap di atas */
        }
</style>
<div id="pageWrapper">
<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="../assets/img/logo.webp" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Staff BPS</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-white" href="tambah_pengguna.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person_add</i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Pengguna</span>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-white" href="kategori.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">layers</i>
            </div>
            <span class="nav-link-text ms-1">Kategori Dokumen</span>
          </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white active bg-gradient-primary" data-bs-toggle="collapse" href="#manajemenDokumen" role="button" aria-expanded="false" aria-controls="manajemenDokumen">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">folder</i>
                </div>
                <span class="nav-link-text ms-1">Manajemen Dokumen</span>
            </a>
            <div class="collapse" id="manajemenDokumen">
                <ul class="list-group">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="unggah_dokumen.php">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">cloud_upload</i> <!-- Ikon untuk Tambah Pengguna -->
                            </div>
                            <span class="nav-link-text ms-4">Unggah Dokumen</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link text-white" href="daftar_pengguna.php">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">list</i>
                            </div>
                            <span class="nav-link-text ms-4">Daftar Dokumen</span>
                        </a>
                    </li> -->
                </ul>
            </div>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-white " href="log_aktifitas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">accessibility</i>
            </div>
            <span class="nav-link-text ms-1">Log Aktifitas</span>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-white " href="laporan.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">picture_as_pdf</i>
            </div>
            <span class="nav-link-text ms-1">Laporan</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="profile.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="../logout.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assignment</i>
            </div>
            <span class="nav-link-text ms-1">Logout</span>
          </a>
        </li>
      </ul>
    </div>
    <!-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn btn-outline-primary mt-4 w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard?ref=sidebarfree" type="button">Documentation</a>
        <a class="btn bg-gradient-primary w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
      </div>
    </div> -->
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Unggah Dokumen</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Unggah Dokumen</h6>
        </nav>
        <?php
            include "navbar.php";
        ?>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Tombol untuk aksi di sebelah kiri -->
        <div class="d-flex gap-2">
            <!-- Tombol untuk membuka modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUnggah" title="Tambah">
                <i class="fas fa-plus fa-lg"></i> Tambah
            </button>

            <!-- Tombol untuk refresh -->
            <button type="button" id="btnRefresh" class="btn btn-secondary" title="Refresh">
                <i class="fas fa-refresh fa-lg"></i>
            </button>
        </div>

        <!-- Form pencarian di sebelah kanan -->
        <div class="d-flex" style="max-width: 350px; width: 100%;">
            <div class="input-group input-group-outline">
                <input 
                    type="text" 
                    id="searchKeyword" 
                    class="form-control" 
                    placeholder="Masukkan kata kunci..." 
                    style="border: 1px solid #ced4da; border-radius: 0.375rem;" 
                >
            </div>
        </div>
    </div>

    <!-- Tabel hasil pencarian -->
    <div id="searchResult">
        <!-- Hasil pencarian akan ditampilkan di sini -->
    </div>
</div>
<div class="modal fade" id="tambahUnggah" tabindex="-1" aria-labelledby="tambahPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="tambahPenggunaLabel">Unggah Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="tambahUnggahForm" enctype="multipart/form-data">
                    <!-- Pilih Kategori -->
                    <label for="name" class="form-label">Jenis Dokumen</label>
                    <div class="input-group input-group-outline mb-3">
                    <select id="name" name="name" class="form-control" required>
                        <option value="">Pilih Jenis Dokumen</option>
                        <?php
                        include '../database/koneksi.php';
                        $query = "SELECT id, name, description, topik, fungsi, tanggal_periode FROM categories";
                        $result = $connection->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '" data-description="' . htmlspecialchars($row['description'], ENT_QUOTES) . '" data-topik="' . htmlspecialchars($row['topik'], ENT_QUOTES) . '" data-fungsi="' . htmlspecialchars($row['fungsi'], ENT_QUOTES) . '" data-tanggal_periode="' . htmlspecialchars($row['tanggal_periode'], ENT_QUOTES) . '">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</option>';
                        }
                        ?>
                    </select>
                    </div>

                    <!-- Deskripsi, Topik, Fungsi, Tanggal Periode (Diperbarui berdasarkan kategori) -->
                    <div id="dokumenInfo" class="mt-3 d-none">
                    <label for="description" class="form-label">Deskripsi</label>
                    <div class="input-group input-group-outline mb-3">
                        <textarea name="description" id="description" class="form-control"></textarea>
                        <!-- <input type="text" id="description" name="description" class="form-control" readonly> -->
                    </div>

                    <label for="topik" class="form-label mt-3">Topik</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="text" id="topik" name="topik" class="form-control" readonly>
                    </div>

                    <label for="fungsi" class="form-label mt-3">Fungsi</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="text" id="fungsi" name="fungsi" class="form-control" readonly>
                    </div>

                    <label for="tanggal_periode" class="form-label mt-3">Tanggal Periode</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="text" id="tanggal_periode" name="tanggal_periode" class="form-control" readonly>
                    </div>
                    </div>

                    <!-- Unggah Dokumen -->
                    <label for="file_path" class="form-label mt-3">Unggah Dokumen</label>
                    <div class="input-group input-group-outline mb-3">
                    <input type="file" id="file_path" name="file_path" class="form-control" accept=".pdf,.doc,.docx,.xlsx" required>
                    </div>
                    <!-- Level Akses -->
                    <label for="access_level" class="form-label mt-3">Level Akses</label>
                    <div class="input-group input-group-outline mb-3">
                    <select id="access_level" name="access_level" class="form-control" required>
                        <option value="biasa">Biasa</option>
                        <option value="sedang">Sedang</option>
                        <option value="rahasia">Rahasia</option>
                    </select>
                    </div>

                    <!-- Password (untuk dokumen rahasia) -->
                    <div class="input-group input-group-outline mt-3 d-none" id="passwordContainer">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    // Tentukan jumlah data per halaman
    $limit = 5; // Data per halaman

    // Ambil halaman saat ini (default: halaman 1)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page > 1) ? ($page * $limit) - $limit : 0;

    // Hitung total data
    $sql_total = "SELECT COUNT(*) AS total FROM dokumen";
    $result_total = $connection->query($sql_total);

    if ($result_total) {
        $total_data = $result_total->fetch_assoc()['total'];
    } else {
        // Tangani jika query total gagal
        die("Query total data gagal: " . $connection->error);
    }

    $total_pages = ceil($total_data / $limit);
    
    // Query dengan JOIN untuk mengambil data dokumen dan kategori
    $sql = "SELECT 
                d.id_dokumen, 
                d.id_kategori, 
                c.name AS kategori_name, 
                d.name AS dokumen_name, 
                c.topik, 
                c.fungsi, 
                c.tanggal_periode, 
                d.file_path,
                c.description, 
                d.access_level, 
                d.password 
            FROM dokumen d
            LEFT JOIN categories c ON d.id_kategori = c.id
            LIMIT $start, $limit";
    $result = $connection->query($sql);

    // Cek apakah query berhasil
    if (!$result) {
        die("Query dokumen gagal: " . $connection->error);
    }
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Tabel Pengguna -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Dokumen</th>
                                <th>Topik</th>
                                <th>Fungsi</th>
                                <th>Tanggal Periode</th>
                                <th>Nama File</th>
                                <th>Kategori</th>
                                <th>Status Dokumen</th>
                                <th>Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            if ($result->num_rows > 0) {
                                $no = $start + 1; // Nomor urut berdasarkan halaman
                                while ($row = $result->fetch_assoc()) {
                                    // Ambil ekstensi file
                                    $file_extension = strtolower(pathinfo($row['dokumen_name'], PATHINFO_EXTENSION));

                                    // Tentukan path ikon berdasarkan ekstensi file
                                    $icon_path = "../assets/img/"; // Path folder ikon
                                    $icon_file = ""; // Nama file ikon

                                    switch ($file_extension) {
                                        case 'pdf':
                                            $icon_file = "PDF.png";
                                            break;
                                        case 'xlsx':
                                        case 'xls':
                                            $icon_file = "xlsx.png";
                                            break;
                                        case 'docx':
                                        case 'doc':
                                            $icon_file = "word.png";
                                            break;
                                        default:
                                            $icon_file = "default.png"; // Ikon default jika ekstensi tidak dikenali
                                            break;
                                    }

                                    // Path file untuk download
                                    $file_link = "../uploads/" . $row['dokumen_name'];

                                    // Menampilkan baris data dokumen
                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td>{$row['kategori_name']}</td>
                                            <td>{$row['topik']}</td>
                                            <td>{$row['fungsi']}</td>
                                            <td>{$row['tanggal_periode']}</td>
                                            <td>
                                                <a href='#' class='btn-view-pdf'
                                                    data-file='" . urlencode($row['dokumen_name']) . "'
                                                    data-id='" . $row['id_dokumen'] . "'
                                                    data-password='" . $row['password'] . "'>
                                                    <img src='" . $icon_path . $icon_file . "' alt='" . $file_extension . "' style='width:30px; height:30px; margin-right:5px;'>
                                                    " . htmlspecialchars($row['dokumen_name']) . "
                                                </a>
                                            </td>
                                            <td>{$row['kategori_name']}</td>
                                            <td>{$row['access_level']}</td>
                                            <td>";
                                            
                                            // Menampilkan password atau tanda "-"
                                            if (!empty($row['password'])) {
                                                echo htmlspecialchars($row['password']);
                                            } else {
                                                echo "-"; // Jika password kosong, tampilkan tanda "-"
                                            }
                                    echo    "</td>
                                            <td>
                                                <button type='button' class='btn btn-info btn-sm btn-view' 
                                                    data-id='{$row['id_dokumen']}' 
                                                    data-kategori_name='{$row['kategori_name']}'
                                                    data-dokumen_name='{$row['dokumen_name']}' 
                                                    data-topik='{$row['topik']}' 
                                                    data-fungsi='{$row['fungsi']}'
                                                    data-description='{$row['description']}'
                                                    data-tanggal_periode='{$row['tanggal_periode']}' 
                                                    data-access_level='{$row['access_level']}' 
                                                    data-password='{$row['password']}' 
                                                    title='Lihat'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                                <button type='button' class='btn btn-warning btn-sm btn-edit' 
                                                    data-id='{$row['id_dokumen']}' 
                                                    data-name='{$row['dokumen_name']}'
                                                    data-id_kategori='{$row['id_kategori']}' 
                                                    data-topik='{$row['topik']}' 
                                                    data-fungsi='{$row['fungsi']}' 
                                                    data-description='{$row['description']}' 
                                                    data-tanggal_periode='{$row['tanggal_periode']}' 
                                                    data-access_level='{$row['access_level']}' 
                                                    data-password='{$row['password']}' 
                                                    data-file_path='{$row['file_path']}'
                                                    title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <button type='button' class='btn btn-danger btn-sm btn-delete' 
                                                        data-id='{$row['id_dokumen']}' 
                                                        data-name='{$row['dokumen_name']}' 
                                                        data-password='{$row['password']}' 
                                                        title='Hapus'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                                <a href='{$file_link}' 
                                                    class='btn btn-success btn-sm btn-download' 
                                                    title='Download' 
                                                    download
                                                    data-id='{$row['id_dokumen']}' 
                                                    data-password='{$row['password']}'>
                                                    <i class='fas fa-download'></i>
                                                </a>
                                            </td>
                                        </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center'>Tidak ada data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" style="color:white;" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<style>
    .table td {
        word-wrap: break-word; /* Membungkus teks panjang ke bawah */
        white-space: normal;  /* Membuat teks tidak dipotong */
        max-width: 300px;     /* Batas lebar kolom */
    }
    .pagination .page-link {
        color: #007bff; /* Warna teks default (biru Bootstrap) */
    }

    .pagination .page-link:hover {
        color: #0056b3; /* Warna teks saat di-hover */
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff; /* Warna background untuk halaman aktif */
        border-color: #007bff; /* Border halaman aktif */
        color: #ffffff; /* Warna teks halaman aktif */
    }
</style>
<!-- Modal Lihat Pengguna -->
<div class="modal fade" id="lihatKategoriModal" tabindex="-1" aria-labelledby="lihatKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="lihatKategoriModalLabel">Detail Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="detailName" class="form-label">Jenis Dokumen:</label>
                <div class="input-group input-group-outline mb-3">
                <input type="text" id="detailName" class="form-control" readonly>
                </div>
                
                <label for="detailDocument" class="form-label">Nama Dokumen:</label>
                <div class="input-group input-group-outline mb-3">
                <input type="text" id="detailDocument" class="form-control" readonly>
                </div>
                
                <label for="detailTopik" class="form-label">Topik:</label>
                <div class="input-group input-group-outline mb-3">
                <input type="text" id="detailTopik" class="form-control" readonly>
                </div>
                
                <label for="detailFungsi" class="form-label">Fungsi:</label>
                <div class="input-group input-group-outline mb-3">
                <input type="text" id="detailFungsi" class="form-control" readonly>
                </div>

                <label for="detailDescription" class="form-label">Deskripsi:</label>
                <div class="input-group input-group-outline mb-3">
                <textarea id="detailDescription" class="form-control" readonly></textarea>
                </div>

                <label for="detailTanggalPeriode" class="form-label">Tanggal Periode:</label>
                <div class="input-group input-group-outline mb-3">
                <input type="text" id="detailTanggalPeriode" class="form-control" readonly>
                </div>

                <label for="detailAccessLevel" class="form-label">Level Akses:</label>
                <div class="input-group input-group-outline mb-3">
                <input type="text" id="detailAccessLevel" class="form-control" readonly>
                </div>

                <div id="passwordContainerView" class="input-group input-group-outline d-none">
                    <label for="detailPassword" class="form-label">Password:</label>
                    <input type="text" id="detailPassword" class="form-control" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- end lihat data -->
<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriLabel" aria-hidden="true">  
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="editKategoriLabel">Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editKategoriForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- ID Dokumen (Hidden) -->
                    <input type="hidden" id="editId" name="id_dokumen">

                    <!-- Jenis Dokumen -->
                    <label for="editKategori" class="form-label">Jenis Dokumen</label>
                    <div class="input-group input-group-outline mb-3">
                        <select id="editKategori" name="id_kategori" class="form-control" required>
                            <option value="">Pilih Jenis Dokumen</option>
                            <?php
                                include '../database/koneksi.php';
                                $query = "SELECT id, name FROM categories";
                                $result = $connection->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Topik -->
                    <label for="editTopik" class="form-label">Topik</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="text" id="editTopik" name="topik" class="form-control" readonly>
                    </div>

                    <!-- Fungsi -->
                    <label for="editFungsi" class="form-label">Fungsi</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="text" id="editFungsi" name="fungsi" class="form-control" readonly>
                    </div>

                    <!-- Deskripsi -->
                    <label for="editDescription" class="form-label">Deskripsi</label>
                    <div class="input-group input-group-outline mb-3">
                        <textarea name="description" id="editDescription" class="form-control" readonly></textarea>
                    </div>

                    <!-- Tanggal Periode -->
                    <label for="editTanggalPeriode" class="form-label">Tanggal Periode</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="date" id="editTanggalPeriode" name="tanggal_periode" class="form-control" readonly>
                    </div>

                    <!-- Nama Dokumen (Upload File) -->
                    <!-- <label for="editNamaDokumen" class="form-label">Nama Dokumen</label> -->
                    <div class="input-group input-group-outline mb-3">
                        <input type="hidden" id="editNamaDokumen" name="name" class="form-control" required>
                    </div>

                    <!-- Upload File -->
                    <label for="editFile" class="form-label">Upload Dokumen</label>
                    <div class="input-group input-group-outline mb-3">
                        <input type="file" id="editFile" name="file_path" class="form-control" accept=".pdf,.doc,.docx,.xlsx">
                    </div>

                    <!-- Tampilkan Nama File Sebelumnya -->
                    <p id="currentFileText" style="margin-top: 5px; display: none;">
                        File saat ini: <a id="currentFileLink" href="#" target="_blank"></a>
                    </p>
                    <!-- File Path (Hidden) -->
                    <input type="hidden" id="editFilePath" name="file_path">

                    <!-- Access Level -->
                    <label for="editAccesLevel" class="form-label">Access Level</label>
                    <div class="input-group input-group-outline mb-3">
                        <select id="editAccesLevel" name="access_level" class="form-control" required>
                            <option value="biasa">Biasa</option>
                            <option value="sedang">Sedang</option>
                            <option value="rahasia">Rahasia</option>
                        </select>
                    </div>

                    <!-- Password (Hidden by Default) -->
                    <div id="passwordContainerEdit" class="input-group input-group-outline mb-3" style="display: none;">
                        <label for="editPassword" class="form-label">Password</label>
                        <input type="password" id="editPassword" name="password" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end edit pengguna -->
  </main>
  
<div class="modal fade" id="docx-xlsx-modal" tabindex="-1" aria-labelledby="docxXlsxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="docxXlsxModalLabel">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="docx-xlsx-content">
                <!-- Konten DOCX atau XLSX akan dimuat di sini -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="flipbook-modal" tabindex="-1" aria-labelledby="flipbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="flipbookModalLabel">Flipbook PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="pdf-content">
                <div id="magazine-container">
                    <div id="magazine">
                        <div class="hard">Cover</div>
                        <div class="hard">Back</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</div>
<?php
    include "live_chat.php";
?>
<script>
  function handlePasswordAction(action, id_dokumen, hasPassword) {
      return new Promise((resolve, reject) => {
          // Jika tidak ada password, lanjutkan tanpa meminta password
          if (!hasPassword) {
              resolve(true);
              return;
          }

          // Tambahkan blur hanya pada konten dalam #pageWrapper
          $('#pageWrapper').addClass('blurred');

          Swal.fire({
              title: 'Masukkan Password',
              input: 'password',
              inputPlaceholder: 'Masukkan password',
              showCancelButton: true,
              confirmButtonText: 'Submit',
              cancelButtonText: 'Batal',
              allowOutsideClick: false,
              showLoaderOnConfirm: true,
              preConfirm: (inputPassword) => {
                  return new Promise((resolvePreConfirm, rejectPreConfirm) => {
                      $.ajax({
                          url: 'password-modal.php',
                          method: 'POST',
                          data: {
                              password: inputPassword,
                              action: action,
                              id_dokumen: id_dokumen
                          },
                          success: function(response) {
                              try {
                                  const data = JSON.parse(response);
                                  if (data.status === 'success') {
                                      resolvePreConfirm(data.message);
                                  } else {
                                      rejectPreConfirm(data.message);
                                  }
                              } catch (error) {
                                  rejectPreConfirm('Terjadi kesalahan saat memproses respons.');
                              }
                          },
                          error: function() {
                              rejectPreConfirm('Terjadi kesalahan saat menghubungi server.');
                          }
                      });
                  });
              }
          }).then((result) => {
              // Hapus blur dari konten setelah SweetAlert selesai
              $('#pageWrapper').removeClass('blurred');

              if (result.isConfirmed) {
                  Swal.fire({
                      icon: 'success',
                      title: 'Berhasil',
                      text: result.value,
                      showConfirmButton: true,
                      allowOutsideClick: false
                  }).then(() => {
                      resolve(true);
                  });
              } else if (result.isDismissed) {
                  window.location.reload(true);
                  reject('User membatalkan');
              }
          }).catch((errorMessage) => {
              $('#pageWrapper').removeClass('blurred');
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: errorMessage,
                  showConfirmButton: true,
                  allowOutsideClick: false
              });
              reject(errorMessage);
          });
      });
  }

    $(document).on('click', '.btn-download', function(e) {
        e.preventDefault();
        const id_dokumen = $(this).data('id');
        const fileLink = $(this).attr('href');
        const hasPassword = $(this).data('password') !== "";
        
        handlePasswordAction('download', id_dokumen, hasPassword)
            .then(() => {
                // Membuat elemen <a> sementara untuk trigger download
                const a = document.createElement('a');
                a.href = fileLink;
                a.download = ""; // Anda dapat menambahkan nama file jika diperlukan, misalnya a.download = "namafile.pdf";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            })
            .catch((error) => {
                if (error === 'User membatalkan') return;
                // console.error(error);
            });
    });
    // $(document).off('click', '.btn-view-pdf').on('click', '.btn-view-pdf', function (e) {
    //     e.preventDefault();
    //     const id_dokumen = $(this).data('id');
    //     const hasPassword = $(this).data('password') !== "";

    //     handlePasswordAction('view', id_dokumen, hasPassword)
    //         .then(() => {
    //             $('#flipbook-modal').modal('show'); // Gunakan ID yang benar untuk modal
    //         })
    //         .catch((error) => {
    //             if (error === 'User membatalkan') return;
    //             console.error(error);
    //         });
    // });

    // Event handler untuk tombol View
  $(document).on('click', '.btn-view', function(e) {
      e.preventDefault();
      const id_dokumen = $(this).data('id');
      const hasPassword = $(this).data('password') !== "";

      handlePasswordAction('view', id_dokumen, hasPassword)
          .then(() => {
              $('#lihatKategoriModal').modal('show');
          })
          .catch((error) => {
              if (error === 'User membatalkan') return;
            //   console.error(error);
          });
  });

  // Event handler untuk tombol Edit
  $(document).on('click', '.btn-edit', function(e) {
      e.preventDefault();
      const id_dokumen = $(this).data('id');
      const hasPassword = $(this).data('password') !== "";

      handlePasswordAction('edit', id_dokumen, hasPassword)
          .then(() => {
              $('#editKategoriModal').modal('show');
          })
          .catch((error) => {
              if (error === 'User membatalkan') return;
            //   console.error(error);
          });
  });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        
        const id_dokumen = $(this).data('id');
        const namaDokumen = $(this).data('name');
        const hasPassword = $(this).data('password') !== "";
        
        // Pertama-tama, jika dokumen memiliki password, minta input password terlebih dahulu
        handlePasswordAction('delete', id_dokumen, hasPassword)
            .then(() => {
                // Setelah password valid (atau jika tidak diperlukan), tampilkan konfirmasi hapus
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Dokumen "${namaDokumen}" akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lakukan AJAX request untuk menghapus dokumen
                        $.ajax({
                            url: 'hapus_dokumen.php',
                            method: 'POST',
                            data: { id: id_dokumen },
                            success: function(response) {
                                try {
                                    const data = JSON.parse(response);
                                    if (data.status === 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: data.message,
                                            showConfirmButton: true
                                        }).then(() => {
                                            // Reload halaman atau hapus baris tabel secara dinamis
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: data.message,
                                            showConfirmButton: true
                                        });
                                    }
                                } catch (error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Terjadi kesalahan saat memproses respons dari server.',
                                        showConfirmButton: true
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Terjadi kesalahan pada server.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            })
            .catch((error) => {
                // Jika user membatalkan atau terjadi error, jangan lakukan apa-apa
                if (error === 'User membatalkan') return;
                // console.error(error);
            });
    });


  // AJAX untuk pencarian
  $(document).ready(function () {
      $('#searchKeyword').on('input', function () {
          var keyword = $(this).val();

          $.ajax({
              url: 'search_unggah.php',
              type: 'GET',
              data: { keyword: keyword },
              success: function (response) {
                  $('#tableBody').html(response);
              },
              error: function () {
                  alert('Terjadi kesalahan saat memuat data.');
              }
          });
      });
  });
</script>

<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.worker.min.js";

    const startFlipSound = new Audio('../assets/sounds/start-flip.mp3');
    const endFlipSound = new Audio('../assets/sounds/end-flip.mp3');

    $(document).ready(function () {
        $(document).off('click', '.btn-view-pdf').on('click', '.btn-view-pdf', function (e) {
            e.preventDefault();
            const file = $(this).data('file');
            const id_dokumen = $(this).data('id');
            const hasPassword = $(this).data('password') !== "";
            const extension = file.split('.').pop().toLowerCase(); // Ekstensi file

            // console.log("File extension:", extension);

            // Ambil nama file dari database
            $.ajax({
                url: 'get_category.php', // File PHP untuk mengambil nama dari database
                type: 'POST',
                data: { id_dokumen: id_dokumen },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const fileName = response.name; // Nama file dari database

                        // Pastikan password divalidasi sebelum membuka modal
                        handlePasswordAction('view', id_dokumen, hasPassword)
                            .then(() => {
                                if (extension === 'pdf') {
                                    // console.log("Opening PDF modal");
                                    $('#flipbook-modal').data('file', file).data('fileName', fileName).modal('show');
                                } else if (extension === 'docx' || extension === 'xlsx') {
                                    // console.log("Opening DOCX/XLSX modal");
                                    $('#docx-xlsx-modal').data('file', file).data('fileName', fileName).modal('show');

                                    // Pastikan fungsi ini hanya dipanggil jika ada
                                    if (typeof openDocxXlsxModal === "function") {
                                        openDocxXlsxModal(file, extension);
                                    }
                                } else {
                                    alert("Format file tidak didukung.");
                                }
                            })
                            .catch((error) => {
                                if (error === 'User membatalkan') return;
                                // console.error(error);
                            });

                    } else {
                        alert("Gagal mengambil nama file.");
                    }
                },
                error: function (xhr, status, error) {
                    // console.error("AJAX Error:", status, error);
                    // console.error("Response Text:", xhr.responseText);
                    alert("Terjadi kesalahan dalam mengambil data. Cek konsol untuk detailnya.");
                }
            });
        });
    });

    // Fungsi resetFlipbook diperbarui agar menampilkan nama file dari database
    function resetFlipbook() {
        const magazine = $('#magazine');
        if (magazine.data('turn')) {
            magazine.turn('destroy');
        }

        // Ambil nama file dari modal
        const fileName = $('#flipbook-modal').data('fileName') || "Nama File Tidak Diketahui";

        magazine.html(`
            <div class="hard" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <img src="../assets/img/logo.webp" alt="Logo" style="width: 120px; height: auto; margin-bottom: 10px;">
                <p style="text-align: center; font-weight: bold;">${fileName}</p>
            </div>
        `);
    }



    // // Fungsi untuk membuka modal DOCX/XLSX
    // function openDocxXlsxModal(file, extension) {
    //     const url = "uploads/" + decodeURIComponent(file);
    //     const contentDiv = $('#docx-xlsx-content');

    //     contentDiv.html('<p>Loading document...</p>'); // Tampilkan pesan loading

    //     if (extension === 'docx') {
    //         fetch(url)
    //             .then(response => response.arrayBuffer())
    //             .then(arrayBuffer => mammoth.convertToHtml({ arrayBuffer }))
    //             .then(result => {
    //                 contentDiv.html(`<div style="padding: 10px;">${result.value}</div>`);
    //             })
    //             .catch(error => {
    //                 // console.error("Gagal memuat DOCX: ", error);
    //                 contentDiv.html('<p>Error: Tidak dapat membuka file DOCX.</p>');
    //             });
    //     } else if (extension === 'xlsx') {
    //         fetch(url)
    //             .then(response => response.arrayBuffer())
    //             .then(arrayBuffer => {
    //                 const workbook = XLSX.read(arrayBuffer, { type: 'array' });
    //                 const sheetName = workbook.SheetNames[0]; // Ambil sheet pertama
    //                 const sheet = workbook.Sheets[sheetName];
    //                 const html = XLSX.utils.sheet_to_html(sheet);
    //                 contentDiv.html(html);
    //             })
    //             .catch(error => {
    //                 // console.error("Gagal memuat XLSX: ", error);
    //                 contentDiv.html('<p>Error: Tidak dapat membuka file XLSX.</p>');
    //             });
    //     }
    // }
    function openDocxXlsxModal(file, extension) {
        // Bangun URL secure untuk preview menggunakan endpoint PHP yang baru dibuat
        const url = window.location.origin + "/kms/admin/file_preview.php?file=" + encodeURIComponent(decodeURIComponent(file));
        const contentDiv = $('#docx-xlsx-content');

        contentDiv.html('<p>Loading document...</p>'); // Tampilkan pesan loading

        if (extension === 'docx') {
            fetch(url)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => mammoth.convertToHtml({ arrayBuffer }))
                .then(result => {
                    contentDiv.html(`<div style="padding: 10px;">${result.value}</div>`);
                })
                .catch(error => {
                    console.error("Error memuat DOCX:", error);
                    contentDiv.html('<p>Error: Tidak dapat membuka file DOCX.</p>');
                });
        } else if (extension === 'xlsx') {
            fetch(url)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => {
                    const workbook = XLSX.read(arrayBuffer, { type: 'array' });
                    const sheetName = workbook.SheetNames[0]; // Ambil sheet pertama
                    const sheet = workbook.Sheets[sheetName];
                    const html = XLSX.utils.sheet_to_html(sheet);
                    contentDiv.html(html);
                })
                .catch(error => {
                    console.error("Error memuat XLSX:", error);
                    contentDiv.html('<p>Error: Tidak dapat membuka file XLSX.</p>');
                });
        }
    }


    $('#flipbook-modal').on('shown.bs.modal', function () {
        const file = $(this).data('file');
        if (file) openPDFModal(file);
    });

    $('#flipbook-modal').on('hidden.bs.modal', function () {
        resetFlipbook();
    });

    // function resetFlipbook() {
    //     const magazine = $('#magazine');
    //     if (magazine.data('turn')) {
    //         magazine.turn('destroy');
    //     }
    //     magazine.html(`
    //         <div class="hard" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
    //             <img src="../assets/img/logo.webp" alt="Logo" style="width: 120px; height: auto; margin-bottom: 10px;">
    //             <p style="text-align: center; font-weight: bold;">Nama File</p>
    //         </div>
    //     `);
    // }


    function openPDFModal(file) {
        resetFlipbook();

        const magazine = document.getElementById('magazine');
        const url = "../uploads/" + decodeURIComponent(file); // Path file
        const extension = file.split('.').pop().toLowerCase(); // Ambil ekstensi file

        // console.log("Memuat file dari:", url);

        if (extension === 'pdf') {
            // Handle PDF
            pdfjsLib.getDocument(url).promise.then(pdf => {
                // console.log("PDF berhasil dimuat. Jumlah halaman:", pdf.numPages);

                if (pdf.numPages < 1) {
                    alert("PDF tidak memiliki halaman.");
                    return;
                }

                const totalPages = pdf.numPages;
                let pagesLoaded = 0;
                let pageImages = new Array(totalPages).fill(null);

                const loadPage = async (i) => {
                    try {
                        const page = await pdf.getPage(i);
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale });

                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        await page.render({ canvasContext: context, viewport }).promise;

                        pageImages[i - 1] = canvas.toDataURL();

                        pagesLoaded++;
                        if (pagesLoaded === totalPages) {
                            renderPages(pageImages, totalPages);
                        }
                    } catch (error) {
                        // console.error("Gagal memuat halaman:", i, error);
                    }
                };

                for (let i = 1; i <= totalPages; i++) {
                    loadPage(i);
                }
            }).catch(error => {
                // console.error("Gagal memuat PDF: ", error);
                alert("Gagal memuat PDF. Pastikan file PDF ada dan dapat diakses.");
            });
        } else if (extension === 'docx') {
            // Handle DOCX
            fetch(url)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => mammoth.extractRawText({ arrayBuffer }))
                .then(result => {
                    const content = result.value; // Ambil teks dari DOCX
                    magazine.innerHTML = `<div style="padding: 20px;">${content}</div>`; // Tampilkan teks di dalam div
                    // Nonaktifkan flipbook untuk DOCX
                    $('#magazine').turn('destroy');
                })
                .catch(error => {
                    // console.error("Gagal memuat DOCX: ", error);
                    alert("Gagal memuat DOCX. Pastikan file DOCX ada dan dapat diakses.");
                });
        } else if (extension === 'xls' || extension === 'xlsx') {
            // Handle XLS/XLSX
            fetch(url)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => {
                    const workbook = XLSX.read(arrayBuffer, { type: 'array' });
                    const sheetName = workbook.SheetNames[0]; // Ambil sheet pertama
                    const sheet = workbook.Sheets[sheetName];
                    const html = XLSX.utils.sheet_to_html(sheet); // Konversi ke HTML
                    magazine.innerHTML = html; // Tampilkan HTML di flipbook
                    // Nonaktifkan flipbook untuk XLS/XLSX
                    $('#magazine').turn('destroy');
                })
                .catch(error => {
                    // console.error("Gagal memuat XLS/XLSX: ", error);
                    alert("Gagal memuat XLS/XLSX. Pastikan file ada dan dapat diakses.");
                });
        } else {
            alert("Format file tidak didukung.");
        }
    }

    function renderPages(pageImages, totalPages, fileName) {
        const magazine = $('#magazine');

        // Tambahkan cover dengan logo dan nama file

        // Tambahkan halaman isi dokumen dari halaman ke-2
        pageImages.forEach(src => {
            if (src) {
                magazine.append(`<div style="background-image: url(${src}); background-size: cover;"></div>`);
            }
        });

        // Pastikan jumlah halaman tetap genap
        if ((totalPages + 1) % 2 !== 0) {
            magazine.append('<div></div>');
        }

        // Inisialisasi flipbook
        initFlipbook(totalPages + 1);
    }



    function initFlipbook(totalPages) {
        const magazine = $('#magazine');

        magazine.turn({
            width: 576,
            height: 752,
            display: 'single',
            acceleration: true,
            gradients: true,
            elevation: 50,
            autoCenter: true,
            pages: totalPages,
            when: {
                turning: function (e, page) {
                    // console.log("Turning to page:", page);
                    startFlipSound.play();
                },
                turned: function (e, page) {
                    // console.log("Turned to page:", page);
                    endFlipSound.play();
                }
            }
        });

        magazine.turn('page', 1);
    }

    // Navigasi dengan tombol keyboard
    $(window).on('keydown', function (e) {
        if (e.keyCode === 37) $('#magazine').turn('previous'); // Tombol kiri
        else if (e.keyCode === 39) $('#magazine').turn('next'); // Tombol kanan
    });
</script>

  <script>
    $('#tambahUnggahForm').submit(function(event) {
        event.preventDefault(); // Mencegah form untuk submit secara default
        
        var formData = new FormData(this); // Ubah ke FormData agar mendukung file upload

        $.ajax({
            type: "POST",
            url: "proses_unggah.php",
            data: formData,
            processData: false, // Jangan proses data form
            contentType: false, // Jangan atur tipe konten
            dataType: "json",
            success: function(response) {
                // console.log(response); // Debugging respons dari server
                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#tambahUnggah').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message,
                        confirmButtonText: 'Coba Lagi'
                    });
                }
            },
            error: function(xhr, status, error) {
                // console.log(xhr.responseText); // Debugging error dari server
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal mengirim data. Coba lagi nanti.',
                    confirmButtonText:  'OK'
                });
            }
        });
    });   
  </script>
  <!-- End jQuery -->

  <!-- jQuery Lihat data -->
<script>
  $(document).ready(function() {
    $('.btn-view').click(function() {
        $('#detailName').val($(this).data('kategori_name'));
        $('#detailDocument').val($(this).data('dokumen_name'));
        $('#detailTopik').val($(this).data('topik'));
        $('#detailFungsi').val($(this).data('fungsi'));
        $('#detailDescription').val($(this).data('description'));
        $('#detailTanggalPeriode').val($(this).data('tanggal_periode'));
        $('#detailAccessLevel').val($(this).data('access_level'));

        if ($(this).data('access_level') === 'rahasia') {
            $('#passwordContainerView').removeClass('d-none');
            $('#detailPassword').val($(this).data('password'));
        } else {
            $('#passwordContainerView').addClass('d-none');
            $('#detailPassword').val('');
        }

        $('#lihatKategoriModal').modal('show');
    });
  });
</script>

  <!-- end jQuery -->

<!-- jQuery Edit data -->
<script>
    $(document).ready(function () {
        $('.btn-edit').click(function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var kategoriId = $(this).data('id_kategori');
            var topik = $(this).data('topik');
            var fungsi = $(this).data('fungsi');
            var description = $(this).data('description');
            var tanggalPeriode = $(this).data('tanggal_periode');
            var access_level = $(this).data('access_level');
            var password = $(this).data('password');
            var filePath = $(this).data('file_path');

            // Set nilai ke dalam modal edit
            $('#editId').val(id);
            $('#editNamaDokumen').val(name); // Menambahkan pengisian nama dokumen
            $('#editTopik').val(topik);
            $('#editFungsi').val(fungsi);
            $('#editDescription').val(description);
            $('#editTanggalPeriode').val(tanggalPeriode);
            $('#editAccesLevel').val(access_level);
            $('#editKategori').val(kategoriId);
            $('#editPassword').val(password);
            $('#editFilePath').val(filePath); // Menyimpan path file lama

            // Tampilkan Password jika levelnya 'rahasia'
            if (access_level === 'rahasia') {
                $('#passwordContainerEdit').show();
            } else {
                $('#passwordContainerEdit').hide();
                $('#editPassword').val('');
            }

            // Tampilkan nama file lama jika ada
            if (filePath) {
                $('#currentFileText').show();
                $('#currentFileLink').attr('href', filePath).text(name);
            } else {
                $('#currentFileText').hide();
            }

            $('#editKategoriModal').modal('show');
        });

        $('#editKategoriForm').submit(function(event) {
            event.preventDefault(); // Mencegah form untuk submit secara default

            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: "edit_dokumen.php",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    // console.log(response); // Debugging
                    // alert(response.message);
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#editKategoriModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengirim data. Coba lagi nanti.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    // Event listener saat access level berubah
        $('#editAccesLevel').change(function () {
            var selectedLevel = $(this).val();
            if (selectedLevel === 'rahasia') {
                $('#passwordContainerEdit').show();
            } else {
                $('#passwordContainerEdit').hide();
                $('#editPassword').val('');
            }
        });
    });
</script>
<script>
    $('#editKategori').change(function () {
    var kategoriId = $(this).val(); // Ambil ID kategori yang dipilih

    if (kategoriId) {
        // Kirim permintaan AJAX untuk mengambil data kategori
        $.ajax({
            url: 'get_category_by_id.php', // File PHP untuk mengambil data kategori
            type: 'GET',
            data: { id: kategoriId },
            success: function (response) {
                var data = JSON.parse(response);

                // Isi field secara otomatis
                $('#editFungsi').val(data.fungsi);
                $('#editTopik').val(data.topik);
                $('#editDescription').val(data.description);
                $('#editTanggalPeriode').val(data.tanggal_periode);
            },
            error: function () {
                Swal.fire('Error!', 'Gagal mengambil data kategori.', 'error');
            }
        });
    } else {
        // Kosongkan field jika tidak ada kategori yang dipilih
        $('#editFungsi').val('');
        $('#editTopik').val('');
        $('#editDescription').val('');
        $('#editTanggalPeriode').val('');
    }
});
</script>
<!-- end edit data -->

<!-- hapus pengguna -->
<!-- <script>
  $(document).ready(function() {
    // Handle klik pada tombol Hapus
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const id_dokumen = $(this).data('id');
        const nama_dokumen = $(this).data('name');

        handlePasswordAction('delete', id_dokumen)
            .then(() => {
                // Tampilkan SweetAlert2 untuk konfirmasi penghapusan
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus dokumen "${nama_dokumen}"`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lakukan penghapusan
                        $.ajax({
                            url: 'hapus_unggah.php',
                            method: 'POST',
                            data: { id_dokumen: id_dokumen },
                            success: function(response) {
                                Swal.fire('Dihapus!', 'Dokumen berhasil dihapus.', 'success').then(() => {
                                    location.reload(); // Reload halaman setelah penghapusan
                                });
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan saat menghapus dokumen.', 'error');
                            }
                        });
                    }
                });
            })
            .catch((error) => {
                console.error(error); // Tampilkan error di konsol
            });
    });
});
// kode refresh
</script> -->
<!-- Script AJAX -->
<!-- <script>
    $(document).ready(function () {
        $('#searchKeyword').on('input', function () {
            var keyword = $(this).val();

            // AJAX request untuk pencarian
            $.ajax({
                url: 'search_kategori.php', // File PHP untuk pencarian
                type: 'GET',
                data: { keyword: keyword },
                success: function (response) {
                    $('#tableBody').html(response); // Ganti isi tbody dengan hasil pencarian
                },
                error: function () {
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        });
    });
</script> -->
<script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- end hapus -->
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
  <!-- end hapus -->
  
<script>
  document.getElementById('btnRefresh').addEventListener('click', function () {
    location.reload(); // Me-refresh halaman
  });
</script>
<script>
    $(document).ready(function() {
        // Update form input saat kategori berubah
        $('#id_kategori').on('change', function() {
            const selectedOption = $(this).find(':selected');
            $('#topik').val(selectedOption.data('topik') || '');
            $('#fungsi').val(selectedOption.data('fungsi') || '');
            $('#description').val(selectedOption.data('description') || '');
            $('#tanggal_periode').val(selectedOption.data('tanggal_periode') || '');
        });

        // Tampilkan input password jika level akses "rahasia"
        $('#access_level').on('change', function() {
            if ($(this).val() === 'rahasia') {
                $('#passwordContainer').removeClass('d-none');
            } else {
                $('#passwordContainer').addClass('d-none');
                $('#password').val('');
            }
        });
    });
</script>
<script>
    $('#name').change(function() {
    var selectedOption = $(this).find('option:selected');
    
    // Ambil data dari option yang dipilih
    var description = selectedOption.data('description');
    var topik = selectedOption.data('topik');
    var fungsi = selectedOption.data('fungsi');
    var tanggal_periode = selectedOption.data('tanggal_periode');
    
    // Perbarui inputan dengan data dari kategori yang dipilih
    $('#description').val(description);
    $('#topik').val(topik);
    $('#fungsi').val(fungsi);
    $('#tanggal_periode').val(tanggal_periode);
    
    // Tampilkan informasi jika kategori dipilih
    if ($(this).val() !== "") {
        $('#dokumenInfo').removeClass('d-none');
    } else {
        $('#dokumenInfo').addClass('d-none');
    }
});

$('#access_level').change(function() {
    if ($(this).val() === 'rahasia') {
        $('#passwordContainer').removeClass('d-none');
    } else {
        $('#passwordContainer').addClass('d-none');
    }
});

</script>

</body>

</html>