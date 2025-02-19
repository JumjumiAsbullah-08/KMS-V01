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
    Admin BPS | Aceh Singkil
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
          <a class="nav-link text-white active bg-gradient-primary" href="tambah_pengguna.php">
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
          <a class="nav-link text-white " href="log_aktifitas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">accessibility</i>
            </div>
            <span class="nav-link-text ms-1">Log Aktifitas</span>
          </a>
        </li>
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tambah Pengguna</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Tambah Pengguna</h6>
        </nav>
        <?php
            include "navbar.php";
        ?> 
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
    <div class="d-flex justify-content-start gap-2 mb-3">
    <!-- Tombol untuk membuka modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPenggunaModal">
            <i class="fas fa-plus fa-lg"></i> Tambah
        </button>

        <!-- Tombol untuk refresh -->
        <button type="button" id="btnRefresh" class="btn btn-secondary">
            <i class="fas fa-refresh fa-lg"></i>
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahPenggunaModal" tabindex="-1" aria-labelledby="tambahPenggunaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="tambahPenggunaLabel">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Pengguna -->
                    <form method="POST" id="tambahPenggunaForm">
                        <!-- Username -->
                        <div class="input-group input-group-outline mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>

                        <!-- Password -->
                        <div class="input-group input-group-outline mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <!-- Email -->
                        <div class="input-group input-group-outline mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <!-- Role -->
                        <div class="input-group input-group-outline mb-3">
                            <select id="role" name="role" class="form-control" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="asn">ASN</option>
                                <option value="staff">Staff</option>
                            </select>
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Tabel Pengguna -->
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($connection->connect_error) {
                                die("Koneksi gagal: " . $connection->connect_error);
                            }

                            // Query untuk mengambil data dari tabel users
                            $sql = "SELECT id, password, username, email, role, created_at FROM users";
                            $result = $connection->query($sql);
                            if ($result->num_rows > 0) {
                                $no = 1; // Variabel untuk nomor urut
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td>{$row['username']}</td>
                                            <td>***</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['role']}</td>
                                            <td>{$row['created_at']}</td>
                                            <td>
                                                <button class='btn btn-info btn-sm btn-view' 
                                                        data-id='{$row['id']}' 
                                                        data-username='{$row['username']}' 
                                                        data-email='{$row['email']}' 
                                                        data-role='{$row['role']}' 
                                                        data-created_at='{$row['created_at']}' title='Lihat'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                                <button class='btn btn-warning btn-sm btn-edit' 
                                                        data-id='{$row['id']}'
                                                        data-username='{$row['username']}'
                                                        data-email='{$row['email']}'
                                                        data-role='{$row['role']}' title='Edit'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <button class='btn btn-danger btn-sm btn-delete' 
                                                        data-id='{$row['id']}' 
                                                        data-username='{$row['username']}' title='Hapus'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </td>
                                          </tr>";
                                    $no++; // Increment nomor urut
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Tidak ada data pengguna</td></tr>";
                            }

                            $connection->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lihat Pengguna -->
<div class="modal fade" id="lihatPenggunaModal" tabindex="-1" aria-labelledby="lihatPenggunaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="lihatPenggunaModalLabel">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="detailUsername" class="form-label">Username:</label>
                <div class="input-group input-group-outline mb-3">
                    <input type="text" id="detailUsername" class="form-control" readonly>
                </div>
                <label for="detailEmail" class="form-label">Email:</label>
                <div class="input-group input-group-outline mb-3">
                    <input type="email" id="detailEmail" class="form-control" readonly>
                </div>
                <label for="detailRole" class="form-label">Role:</label>
                <div class="input-group input-group-outline mb-3">
                    <input type="text" id="detailRole" class="form-control" readonly>
                </div>
                <label for="detailCreatedAt" class="form-label">Created At:</label>
                <div class="input-group input-group-outline mb-3">
                    <input type="text" id="detailCreatedAt" class="form-control" readonly>
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
<div class="modal fade" id="editPenggunaModal" tabindex="-1" aria-labelledby="editPenggunaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="editPenggunaLabel">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPenggunaForm">
                <div class="modal-body">
                    <!-- ID (Hidden) -->
                    <input type="hidden" id="editId" name="id">

                    <!-- Username -->
                    <div class="input-group input-group-outline mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" id="editUsername" name="username" class="form-control" required>
                    </div>

                    <!-- Email -->
                    <div class="input-group input-group-outline mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" id="editEmail" name="email" class="form-control" required>
                    </div>

                    <!-- Role -->
                    <label for="editRole" class="form-label">Role</label>
                    <div class="input-group input-group-outline mb-3">
                        <select id="editRole" name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="asn">ASN</option>
                            <option value="staff">Staff</option>
                        </select>
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
<?php
  include "live_chat.php";
?>
  <!--   Core JS Files   -->

  <!-- jQuery fungsi simpan -->
   <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
    // Form tambah pengguna
    $('#tambahPenggunaForm').submit(function(event) {
        event.preventDefault(); // Mencegah form untuk melakukan submit secara default
        
        // Ambil data form
        var formData = $(this).serialize();

        // Kirim data ke server menggunakan AJAX
        $.ajax({
            type: "POST",
            url: "proses_pengguna.php",
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
                        $('#tambahPenggunaModal').modal('hide');
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
        var username = $(this).data('username');
        var email = $(this).data('email');
        var role = $(this).data('role');
        var hashedPassword = $(this).data('password'); // Hash MD5
        var createdAt = $(this).data('created_at');

        $('#detailUsername').val(username);
        $('#detailEmail').val(email);
        $('#detailRole').val(role);
        // $('#detailPassword').val(hashedPassword); // Tampilkan hash MD5
        $('#detailCreatedAt').val(createdAt);

        $('#lihatPenggunaModal').modal('show');
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
          var username = $(this).data('username');
          var email = $(this).data('email');
          var role = $(this).data('role');

          // Masukkan data ke dalam modal
          $('#editId').val(id);
          $('#editUsername').val(username);
          $('#editEmail').val(email);
          $('#editRole').val(role);

          // Tampilkan modal Edit
          $('#editPenggunaModal').modal('show');
      });

      // Event listener untuk submit form Edit Pengguna
      $('#editPenggunaForm').submit(function (e) {
          e.preventDefault(); // Mencegah refresh halaman

          // Ambil data dari form
          var formData = $(this).serialize();

          // Kirim data ke server menggunakan AJAX
          $.ajax({
              url: 'edit_pengguna_proses.php', // Endpoint untuk menyimpan perubahan
              type: 'POST',
              data: formData,
              success: function (response) {
                  var data = JSON.parse(response);
                  if (data.status === 'success') {
                      Swal.fire('Berhasil!', data.message, 'success').then(() => {
                          location.reload(); // Refresh halaman
                      });
                  } else {
                      Swal.fire('Gagal!', data.message, 'error');
                  }
              },
              error: function () {
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
        var username = $(this).data('username'); // Ambil username untuk ditampilkan di pesan

        // SweetAlert2 untuk konfirmasi
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Pengguna "${username}" akan dihapus secara permanen!`,
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
                    url: 'hapus_pengguna_proses.php', // Endpoint untuk menghapus data
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