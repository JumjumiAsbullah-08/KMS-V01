<?php
session_start();
include "../database/koneksi.php";

// Periksa apakah admin telah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'asn') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/logo.webp">
  <title>
    ASN BPS | Aceh Singkil
  </title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>
<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="../assets/img/logo.webp" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">ASN BPS</span>
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
          <a class="nav-link text-white active bg-gradient-primary" href="kategori.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">layers</i>
            </div>
            <span class="nav-link-text ms-1">Kategori Dokumen</span>
          </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#manajemenDokumen" role="button" aria-expanded="false" aria-controls="manajemenDokumen">
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Kategori</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Kategori</h6>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKategori" title="Tambah">
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


    <!-- Modal -->
    <div class="modal fade" id="tambahKategori" tabindex="-1" aria-labelledby="tambahPenggunaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="tambahPenggunaLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Pengguna -->
                    <form method="POST" id="tambahKategoriForm">
                        <!-- Nama Kategori -->
                        <div class="input-group input-group-outline mb-3">
                            <label for="name" class="form-label">Jenis Dokumen</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <!-- Password -->
                        <div class="input-group input-group-outline mb-3">
                            <label for="topik" class="form-label">Topik</label>
                            <input type="text" id="topik" name="topik" class="form-control" required>
                        </div>

                        <!-- Email -->
                        <div class="input-group input-group-outline mb-3">
                            <label for="fungsi" class="form-label">Fungsi</label>
                            <input type="text" id="fungsi" name="fungsi" class="form-control" required>
                        </div>

                        <label for="description" class="form-label">Deskripsi</label>
                        <div class="input-group input-group-outline mb-3">
                            <textarea name="description" id="description" class="form-control" required></textarea>
                        </div>

                        <div class="input-group input-group-outline mb-3">
                            <label for="tanggal_periode" class="form-label">Periode</label>
                            <input type="date" id="tanggal_periode" name="tanggal_periode" class="form-control" required>
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid">
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
        $sql_total = "SELECT COUNT(*) AS total FROM categories";
        $result_total = $connection->query($sql_total);
        $total_data = $result_total->fetch_assoc()['total'];

        $total_pages = ceil($total_data / $limit);
        $sql = "SELECT id, name, description, topik, fungsi, tanggal_periode FROM categories LIMIT $start, $limit";
        $result = $connection->query($sql);
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
                                <th>Deskripsi</th>
                                <th>Tanggal Periode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php
                            if ($result->num_rows > 0) {
                                $no = $start + 1; // Nomor urut berdasarkan halaman
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['topik']}</td>
                                            <td>{$row['fungsi']}</td>
                                            <td class='text-wrap'>{$row['description']}</td>
                                            <td>{$row['tanggal_periode']}</td>
                                            <td>
                                                <button class='btn btn-info btn-sm btn-view' 
                                                        data-id='{$row['id']}' 
                                                        data-name='{$row['name']}' 
                                                        data-topik='{$row['topik']}' 
                                                        data-fungsi='{$row['fungsi']}' 
                                                        data-description='{$row['description']}' 
                                                        data-tanggal_periode='{$row['tanggal_periode']}' title='Lihat'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                                <button class='btn btn-warning btn-sm btn-edit' 
                                                        data-id='{$row['id']}' 
                                                        data-name='{$row['name']}' 
                                                        data-topik='{$row['topik']}' 
                                                        data-fungsi='{$row['fungsi']}' 
                                                        data-description='{$row['description']}' 
                                                        data-tanggal_periode='{$row['tanggal_periode']}' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <button class='btn btn-danger btn-sm btn-delete' 
                                                        data-id='{$row['id']}' 
                                                        data-name='{$row['name']}' title='Hapus'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                          </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data</td></tr>";
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
                                <a class="page-link" style="color:white;"href="?page=<?= $page - 1; ?>" aria-label="Previous">
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

<!-- Tambahkan CSS untuk menyesuaikan tampilan -->
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
</div>

<!-- Modal Lihat Pengguna -->
<div class="modal fade" id="lihatKategoriModal" tabindex="-1" aria-labelledby="lihatKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="lihatKategoriModalLabel">Detail Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="detailName" class="form-label">Jenis Dokumen:</label>
                <div class="input-group input-group-outline mb-3">
                    <input type="text" id="detailName" class="form-control" readonly>
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
                    <textarea name="detailDescription" id="detailDescription" class="form-control" readonly>description</textarea>
                    <!-- <input type="text" id="detailFungsi" class="form-control" > -->
                </div>
                <label for="detailTanggalPeriode" class="form-label">Tanggal Periode:</label>
                <div class="input-group input-group-outline mb-3">
                    <input type="text" id="detailTanggalPeriode" class="form-control" readonly>
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
                <h5 class="modal-title text-white" id="editKategoriLabel">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editKategoriForm">
                <div class="modal-body">
                    <!-- ID (Hidden) -->
                    <input type="hidden" id="editId" name="id">

                    <!-- Username -->
                    <div class="input-group input-group-outline mb-3">
                        <label for="editName" class="form-label">Jenis Dokumen</label>
                        <input type="text" id="editName" name="name" class="form-control" required>
                    </div>

                    <!-- Email -->
                    <div class="input-group input-group-outline mb-3">
                        <label for="editTopik" class="form-label">Topik</label>
                        <input type="text" id="editTopik" name="topik" class="form-control" required>
                    </div>

                    <!-- Role -->
                    <div class="input-group input-group-outline mb-3">
                        <label for="editFungsi" class="form-label">Fungsi</label>
                        <input type="text" id="editFungsi" name="fungsi" class="form-control" required>
                    </div>
                    <label for="editDescription" class="form-label">Deskripsi</label>
                    <div class="input-group input-group-outline mb-3">
                        <textarea name="description" id="editDescription" class="form-control" required>description</textarea>
                        <!-- <input type="text" id="editFungsi" name="fungsi" class="form-control" required> -->
                    </div>
                    <div class="input-group input-group-outline mb-3">
                        <label for="editTanggalPeriode" class="form-label">Tanggal Periode</label>
                        <input type="date" id="editTanggalPeriode" name="tanggal_periode" class="form-control" required>
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
  <!-- Live Chat Container -->
<!-- Live Chat Toggle -->
<!-- <script src="live_chat.js"></script> -->
<?php
include 'live_chat.php';
?>
<!-- <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2" onclick="toggleChat()">
        <i class="material-icons py-2">chat</i>
    </a>
    <div class="card shadow-lg" id="chat-container">
        <div class="card-header pb-0 pt-3">
            <div class="float-start">
                <h5 class="mt-3 mb-0">Live Chat</h5>
            </div>
            <div class="float-end mt-4">
                <button class="btn btn-link text-dark p-0 fixed-plugin-close-button" onclick="toggleChat()">
                    <i class="material-icons">clear</i>
                </button>
            </div>
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body chat-box p-3" id="chat-box">
            
        </div>
        <div class="card-footer">
            <div class="d-flex">
                <input type="text" id="message" class="form-control me-2" placeholder="Ketik pesan..." onkeydown="handleMention(event)">
                <button class="btn btn-primary" onclick="sendMessage()">
                    <i class="material-icons">send</i>
                </button>
            </div>
        </div>
    </div>
</div> -->


  <!--   Core JS Files   -->

  <!-- jQuery fungsi simpan -->
   <!-- SweetAlert2 -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
    // Form tambah pengguna
    $('#tambahKategoriForm').submit(function(event) {
        event.preventDefault(); // Mencegah form untuk melakukan submit secara default
        
        // Ambil data form
        var formData = $(this).serialize();

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            type: "POST",
            url: "proses_kategori.php",
            data: formData,
            dataType: "json",
            success: function(response) {
                // Tampilkan SweetAlert2 berdasarkan status
                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: response.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Setelah klik OK, tutup modal dan reload halaman
                        $('#tambahKategori').modal('hide');
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
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal mengirim data. Coba lagi nanti.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
  </script>
  <!-- End jQuery -->

  <!-- jQuery Lihat data -->
<script>
  $(document).ready(function() {
    // Event listener untuk button "Lihat"
    $('.btn-view').click(function() {
        var name = $(this).data('name');
        var topik = $(this).data('topik');
        var fungsi = $(this).data('fungsi');
        var description = $(this).data('description');
        var tanggal_periode = $(this).data('tanggal_periode');

        $('#detailName').val(name);
        $('#detailTopik').val(topik);
        $('#detailFungsi').val(fungsi);
        $('#detailDescription').val(description);
        $('#detailTanggalPeriode').val(tanggal_periode);

        $('#lihatKategoriModal').modal('show');
    });

  });
</script>
  <!-- end jQuery -->

<!-- jQuery Edit data -->
<script>
  $(document).ready(function () {
      // Event listener untuk tombol Edit
      $('.btn-edit').click(function () {
          // Ambil data dari atribut tombol
          var id = $(this).data('id');
          var name = $(this).data('name');
          var description = $(this).data('description');
          var topik = $(this).data('topik');
          var fungsi = $(this).data('fungsi');
          var tanggal_periode = $(this).data('tanggal_periode');

          // Masukkan data ke dalam modal
          $('#editId').val(id);
          $('#editName').val(name);
          $('#editDescription').val(description);
          $('#editTopik').val(topik);
          $('#editFungsi').val(fungsi);
          $('#editTanggalPeriode').val(tanggal_periode);

          // Tampilkan modal Edit
          $('#editKategoriModal').modal('show');
      });

      // Event listener untuk submit form Edit Kategori
      $('#editKategoriForm').submit(function (e) {
          e.preventDefault(); // Mencegah refresh halaman

          // Ambil data dari form
          var formData = $(this).serialize();
          
          // Debugging: Log form data
          // console.log('Form Data:', formData);

          // Kirim data ke server menggunakan AJAX
          $.ajax({
              url: 'edit_ketegori_proses.php', // Endpoint untuk menyimpan perubahan
              type: 'POST',
              data: formData,
              success: function (response) {
                  // console.log('Response:', response); // Log response dari server
                  var data = JSON.parse(response);
                  if (data.status === 'success') {
                      Swal.fire('Berhasil!', data.message, 'success').then(() => {
                          location.reload(); // Refresh halaman
                      });
                  } else {
                      Swal.fire('Gagal!', data.message, 'error');
                  }
              },
              error: function (xhr, status, error) {
                  // console.log('Error:', error); // Log error jika ada masalah di request
                  Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
              }
          });
      });
  });
</script>

<!-- end edit data -->

<!-- hapus pengguna -->
<script>
  $(document).ready(function () {
    // Event listener untuk tombol Hapus
    $('.btn-delete').click(function () {
        var id = $(this).data('id'); // Ambil ID pengguna
        var name = $(this).data('name'); // Ambil username untuk ditampilkan di pesan

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Jenis Dokumen "${name}" akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna mengonfirmasi, kirim AJAX untuk menghapus data
                $.ajax({
                    url: 'hapus_kategori.php', // Endpoint untuk menghapus data
                    type: 'POST',
                    data: { id: id },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                location.reload(); // Refresh halaman setelah penghapusan berhasil
                            });
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    });
});
// kode refresh
</script>
<!-- Script AJAX -->
<script>
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
</script>
    
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
<script>
  document.getElementById('btnRefresh').addEventListener('click', function () {
    location.reload(); // Me-refresh halaman
  });
</script>

</body>

</html>