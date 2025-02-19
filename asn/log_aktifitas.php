<?php
session_start();
include "../database/koneksi.php";

// Periksa apakah admin telah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'asn') {
    header("Location: ../index.php");
    exit;
}
// Query untuk mengambil data log aktivitas (diurutkan dari yang terbaru)
$query_logs = "SELECT * FROM activity_logs ORDER BY created_at DESC";
$result_logs = mysqli_query($connection, $query_logs);

// Query untuk mengambil data chats (diurutkan dari yang terbaru)
$query_chats = "SELECT * FROM chats ORDER BY created_at DESC";
$result_chats = mysqli_query($connection, $query_chats);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/logo.webp">
  <title>
    Admin BPS | Aceh Singkil
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
        <span class="ms-1 font-weight-bold text-white">Admin BPS</span>
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
        <li class="nav-item">
          <a class="nav-link text-white" href="tambah_pengguna.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person_add</i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Pengguna</span>
          </a>
        </li>
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
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-primary" href="log_aktifitas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">accessibility</i>
            </div>
            <span class="nav-link-text ms-1">Log Aktifitas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="laporan.php">
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Log Aktivitas</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Log Aktivitas</h6>
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
<div class="container-fluid py-4">
    <div class="card">
      <!-- Card Header dengan Tab -->
      <div class="card-header pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="liveTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="false">Categories</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen" role="tab" aria-controls="dokumen" aria-selected="false">Dokumen</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats" role="tab" aria-controls="chats" aria-selected="false">Chats</a>
          </li>
        </ul>
      </div>
      <!-- Card Body dengan Tab Content -->
      <div class="card-body">
        <div class="tab-content" id="liveTabsContent">
          <!-- Tab Users -->
          <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody id="usersTableBody">
                  <!-- Data Users akan diupdate via AJAX -->
                </tbody>
              </table>
            </div>
          </div>
          <!-- Tab Categories -->
          <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Topik</th>
                    <th>Fungsi</th>
                    <th>Tanggal Periode</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody id="categoriesTableBody">
                  <!-- Data Categories akan diupdate via AJAX -->
                </tbody>
              </table>
            </div>
          </div>
          <!-- Tab Dokumen -->
          <div class="tab-pane fade" id="dokumen" role="tabpanel" aria-labelledby="dokumen-tab">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>ID Dokumen</th>
                    <th>Name</th>
                    <th>File Path</th>
                    <th>File Type</th>
                    <th>File Size</th>
                    <th>Access Level</th>
                    <th>Uploaded By</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                  </tr>
                </thead>
                <tbody id="dokumenTableBody">
                  <!-- Data Dokumen akan diupdate via AJAX -->
                </tbody>
              </table>
            </div>
          </div>
          <!-- Tab Chats -->
          <div class="tab-pane fade" id="chats" role="tabpanel" aria-labelledby="chats-tab">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>Sender ID</th>
                    <th>Recipient ID</th>
                    <th>Message</th>
                    <th>Is Read</th>
                    <th>Created At</th>
                  </tr>
                </thead>
                <tbody id="chatsTableBody">
                  <!-- Data Chats akan diupdate via AJAX -->
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- end tab-content -->
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
 <!-- Script Realtime Update via AJAX polling -->
 <script>
    function updateData() {
      $.ajax({
        url: 'fetch_logs.php',  // Pastikan file fetch_data.php berada di lokasi yang tepat
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          // Update Users
          var usersRows = '';
          data.users.forEach(function(user) {
            usersRows += '<tr>';
            usersRows += '<td>' + user.id + '</td>';
            usersRows += '<td>' + user.username + '</td>';
            usersRows += '<td>' + user.email + '</td>';
            usersRows += '<td>' + user.role + '</td>';
            usersRows += '<td>' + user.created_at + '</td>';
            usersRows += '</tr>';
          });
          $('#usersTableBody').html(usersRows);

          // Update Categories
          var categoriesRows = '';
          data.categories.forEach(function(cat) {
            categoriesRows += '<tr>';
            categoriesRows += '<td>' + cat.id + '</td>';
            categoriesRows += '<td>' + cat.name + '</td>';
            categoriesRows += '<td>' + cat.description + '</td>';
            categoriesRows += '<td>' + cat.topik + '</td>';
            categoriesRows += '<td>' + cat.fungsi + '</td>';
            categoriesRows += '<td>' + cat.tanggal_periode + '</td>';
            categoriesRows += '<td>' + cat.created_at + '</td>';
            categoriesRows += '</tr>';
          });
          $('#categoriesTableBody').html(categoriesRows);

          // Update Dokumen
          var dokumenRows = '';
          data.dokumen.forEach(function(doc) {
            dokumenRows += '<tr>';
            dokumenRows += '<td>' + doc.id_dokumen + '</td>';
            dokumenRows += '<td>' + doc.name + '</td>';
            dokumenRows += '<td>' + doc.file_path + '</td>';
            dokumenRows += '<td>' + doc.file_type + '</td>';
            dokumenRows += '<td>' + doc.file_size + '</td>';
            dokumenRows += '<td>' + doc.access_level + '</td>';
            dokumenRows += '<td>' + doc.uploaded_by + '</td>';
            dokumenRows += '<td>' + doc.created_at + '</td>';
            dokumenRows += '<td>' + doc.updated_at + '</td>';
            dokumenRows += '</tr>';
          });
          $('#dokumenTableBody').html(dokumenRows);

          // Update Chats
          var chatsRows = '';
          data.chats.forEach(function(chat) {
            chatsRows += '<tr>';
            chatsRows += '<td>' + chat.id + '</td>';
            chatsRows += '<td>' + chat.sender_id + '</td>';
            chatsRows += '<td>' + chat.recipient_id + '</td>';
            chatsRows += '<td>' + chat.message + '</td>';
            chatsRows += '<td>' + (chat.is_read == 1 ? 'Yes' : 'No') + '</td>';
            chatsRows += '<td>' + chat.created_at + '</td>';
            chatsRows += '</tr>';
          });
          $('#chatsTableBody').html(chatsRows);
        },
        error: function(xhr, status, error) {
          console.error("Error fetching data:", error);
        }
      });
    }
    
    $(document).ready(function(){
      // Panggil pembaruan data pertama kali
      updateData();
      // Lakukan polling setiap 5 detik (5000 milidetik)
      setInterval(updateData, 5000);
    });
  </script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
    location.reload();
  });
</script>


</body>

</html>