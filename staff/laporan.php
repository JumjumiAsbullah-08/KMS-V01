<?php
session_start();
include "../database/koneksi.php";

// Periksa apakah admin telah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
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
    Staff BPS | Aceh Singkil
  </title>
  <style>
        @media print {
        /* Sembunyikan seluruh elemen pada halaman */
        body * {
            visibility: hidden;
        }
        /* Tampilkan hanya bagian dengan class "printable" beserta seluruh isinya */
        .printable, .printable * {
            visibility: visible;
        }
        /* Posisikan bagian printable agar berada di sudut kiri atas halaman cetak */
        .printable {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
        .no-print {
            display: none;
        }
        }
    </style>
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
          <a class="nav-link text-white active bg-gradient-primary" href="laporan.php">
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Laporan</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Laporan</h6>
        </nav>
        <?php
          include "navbar.php";
        ?>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-2 mb-3">
    <!-- Tombol untuk membuka modal
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
            <i class="fas fa-plus fa-lg"></i> Tambah
        </button> -->

        <!-- Tombol untuk refresh -->
        <button type="button" id="btnRefresh" class="btn btn-secondary">
            <i class="fas fa-refresh fa-lg"></i>
        </button>
    </div>

    <!-- Modal -->
</div>
<?php
// laporan.php
// include "koneksi.php"; // Pastikan koneksi ke database sudah benar

// Ambil parameter dari URL (jika ada)
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';
$start_date  = isset($_GET['start_date'])  ? $_GET['start_date']  : '';
$end_date    = isset($_GET['end_date'])    ? $_GET['end_date']    : '';

$data = array();

if ($report_type) {
    if ($report_type == 'users') {
        $query = "SELECT id, username, email, role, created_at FROM users";
        if ($start_date && $end_date) {
            $query .= " WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
        }
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } elseif ($report_type == 'categories') {
        $query = "SELECT id, name, description, topik, fungsi, tanggal_periode, created_at FROM categories";
        if ($start_date && $end_date) {
            $query .= " WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date'";
        }
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    } elseif ($report_type == 'dokumen') {
        // Query join tabel dokumen dan categories
        $query = "SELECT d.id_dokumen, d.name, d.file_path, d.file_type, d.file_size, d.access_level, d.uploaded_by, 
                         d.created_at, d.updated_at, c.name AS category_name, c.topik, c.fungsi, c.tanggal_periode 
                  FROM dokumen d 
                  JOIN categories c ON d.id_kategori = c.id";
        if ($start_date && $end_date) {
            $query .= " WHERE DATE(d.created_at) BETWEEN '$start_date' AND '$end_date'";
        }
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
}
?>
<div class="container-fluid py-4">
  <div class="card">
    <!-- Card Header -->
    <div class="card-header pb-0">
      <h4 class="card-title">Laporan</h4>
    </div>
    <!-- Card Body -->
    <div class="card-body">
      <!-- Form Filter Laporan -->
      <form method="GET" action="laporan.php">
        <div class="row">
          <!-- Kolom 1: Dropdown Pilih Laporan -->
          <div class="col-md-3">
          <label for="reportType" class="form-label">Pilih Laporan</label>
            <div class="input-group input-group-outline mb-3">
              <select name="report_type" id="reportType" class="form-control">
                <option value="">-- Pilih Laporan --</option>
                <option value="users" <?php if($report_type=='users') echo 'selected'; ?>>Users</option>
                <option value="categories" <?php if($report_type=='categories') echo 'selected'; ?>>Kategori</option>
                <option value="dokumen" <?php if($report_type=='dokumen') echo 'selected'; ?>>Dokumen</option>
              </select>
            </div>
          </div>
          <!-- Kolom 2: Tanggal Mulai -->
          <div class="col-md-3">
          <label for="startDate" class="form-label">Tanggal Mulai</label>
            <div class="input-group input-group-outline mb-3">
              <input type="date" name="start_date" id="startDate" value="<?php echo $start_date; ?>" class="form-control">
            </div>
          </div>
          <!-- Kolom 3: Tanggal Selesai -->
          <div class="col-md-3">
          <label for="endDate" class="form-label">Tanggal Selesai</label>
            <div class="input-group input-group-outline mb-3">
              <input type="date" name="end_date" id="endDate" value="<?php echo $end_date; ?>" class="form-control">
            </div>
          </div>
          <!-- Kolom 4: Tombol Submit -->
          <div class="col-md-3 d-flex align-items-center">
            <button type="submit" class="btn btn-primary" style="margin-top:30px !important;" title="Lihat Laporan">
              <i class="fas fa-eye fa-lg"></i>
            </button>
          </div>
        </div>
      </form>

      <?php if ($report_type) : ?>
        <?php if (count($data) > 0) : ?>
          <div class="table-responsive printable">
            <table class="table table-hover">
              <thead class="thead-light">
                <?php if ($report_type == 'users'): ?>
                  <tr>
                    <!-- <th>ID</th> -->
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                  </tr>
                <?php elseif ($report_type == 'categories'): ?>
                  <tr>
                    <!-- <th>ID</th> -->
                    <th>Name</th>
                    <th>Description</th>
                    <th>Topik</th>
                    <th>Fungsi</th>
                    <th>Tanggal Periode</th>
                    <th>Created At</th>
                  </tr>
                <?php elseif ($report_type == 'dokumen'): ?>
                  <tr>
                    <!-- <th>ID Dokumen</th> -->
                    <th>Name</th>
                    <th>File Path</th>
                    <!-- <th>File Type</th> -->
                    <th>File Size</th>
                    <th>Access Level</th>
                    <!-- <th>Uploaded By</th> -->
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Category Name</th>
                    <th>Topik</th>
                    <th>Fungsi</th>
                    <th>Tanggal Periode</th>
                  </tr>
                <?php endif; ?>
              </thead>
              <tbody>
                <?php foreach ($data as $row) : ?>
                  <tr>
                    <?php if ($report_type == 'users'): ?>
                      <!-- <td><?php echo $row['id']; ?></td> -->
                      <td><?php echo $row['username']; ?></td>
                      <td><?php echo $row['email']; ?></td>
                      <td><?php echo $row['role']; ?></td>
                      <td><?php echo $row['created_at']; ?></td>
                    <?php elseif ($report_type == 'categories'): ?>
                      <!-- <td><?php echo $row['id']; ?></td> -->
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['description']; ?></td>
                      <td><?php echo $row['topik']; ?></td>
                      <td><?php echo $row['fungsi']; ?></td>
                      <td><?php echo $row['tanggal_periode']; ?></td>
                      <td><?php echo $row['created_at']; ?></td>
                    <?php elseif ($report_type == 'dokumen'): ?>
                      <!-- <td><?php echo $row['id_dokumen']; ?></td> -->
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['file_path']; ?></td>
                      <!-- <td><?php echo $row['file_type']; ?></td> -->
                      <td><?php echo $row['file_size']; ?></td>
                      <td><?php echo $row['access_level']; ?></td>
                      <!-- <td><?php echo $row['uploaded_by']; ?></td> -->
                      <td><?php echo $row['created_at']; ?></td>
                      <td><?php echo $row['updated_at']; ?></td>
                      <td><?php echo $row['category_name']; ?></td>
                      <td><?php echo $row['topik']; ?></td>
                      <td><?php echo $row['fungsi']; ?></td>
                      <td><?php echo $row['tanggal_periode']; ?></td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Tombol Cetak dan Download PDF -->
          <div class="mt-3 no-print">
            <button onclick="window.print()" class="btn btn-secondary mr-2" title="Cetak Laporan"><i class="fa fa-print fa-lg"></i></button>
            <a href="download_pdf.php?report_type=<?php echo $report_type; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" title="Download PDF" target="_blank" class="btn btn-danger">
            <i class="fa fa-file-pdf fa-lg"></i>
            </a>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">Tidak ada data.</div>
        <?php endif; ?>
      <?php endif; ?>
    </div><!-- end card-body -->
  </div><!-- end card -->
</div><!-- end container-fluid -->


<!-- Bootstrap JS dan dependencies -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->

  </main>
<?php
  include "live_chat.php";
?>
  <!--   Core JS Files   -->

  <!-- jQuery fungsi simpan -->
   <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
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
    window.location.href = "http://127.0.0.1/kms/admin/laporan.php";
  });
</script>


</body>

</html>