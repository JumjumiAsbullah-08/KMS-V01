<?php
session_start();
include "../database/koneksi.php";

// Periksa apakah admin telah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
// Ambil jumlah data Users
$queryUsers = "SELECT COUNT(*) AS count FROM users";
$resultUsers = mysqli_query($connection, $queryUsers);
$rowUsers = mysqli_fetch_assoc($resultUsers);
$usersCount = $rowUsers['count'];

// Ambil jumlah data Categories
$queryCategories = "SELECT COUNT(*) AS count FROM categories";
$resultCategories = mysqli_query($connection, $queryCategories);
$rowCategories = mysqli_fetch_assoc($resultCategories);
$categoriesCount = $rowCategories['count'];

// Ambil jumlah data Dokumen
$queryDokumen = "SELECT COUNT(*) AS count FROM dokumen";
$resultDokumen = mysqli_query($connection, $queryDokumen);
$rowDokumen = mysqli_fetch_assoc($resultDokumen);
$dokumenCount = $rowDokumen['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/logo.webp">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css" rel="stylesheet" />
  <title>
    Admin BPS | Aceh Singkil
  </title>
  <style>
    /* Tambahan styling jika diperlukan */
    /* Custom styling untuk card dan kalender */
    .card {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    /* Kalender container */
    #calendar {
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 10px;
    }
    /* Custom FullCalendar Toolbar Styling */
    .fc .fc-toolbar {
      background-color: #f5f5f5;
      border-bottom: 1px solid #e0e0e0;
      padding: 8px;
    }
    .fc .fc-toolbar-title {
      font-size: 1.1rem;
      color: #333;
    }
    .fc .fc-button {
      background-color: #fff;
      border: 1px solid #d0d0d0;
      color: #007bff;
      font-size: 0.85rem;
      padding: 4px 8px;
      border-radius: 4px;
      margin: 0 2px;
    }
    .fc .fc-button:hover {
      background-color: #e9ecef;
      color: #0056b3;
    }
    .fc .fc-button:focus {
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    /* Custom styling untuk event text */
    .fc .fc-daygrid-event {
      font-size: 0.8rem;
      padding: 2px 4px;
      border-radius: 4px;
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
          <a class="nav-link text-white active bg-gradient-primary" href="index.php">
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
        <!-- <li class="nav-item">
            <a class="nav-link text-white" data-bs-toggle="collapse" href="#manajemenPengguna" role="button" aria-expanded="false" aria-controls="manajemenPengguna">
                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">perm_contact_calendar</i>
                </div>
                <span class="nav-link-text ms-1">Manajemen Pengguna</span>
            </a>
            <div class="collapse" id="manajemenPengguna">
                <ul class="list-group">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="tambah_pengguna.php">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person_add</i>
                            </div>
                            <span class="nav-link-text ms-4">Tambah Pengguna</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="daftar_pengguna.php">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">list</i>
                            </div>
                            <span class="nav-link-text ms-4">Daftar Pengguna</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li> -->
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
          <a class="nav-link text-white" href="../logout.php">
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Dashboard</h6>
        </nav>
        <?php include "navbar.php";?>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <!-- Kolom 1: Pengguna -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2 position-relative">
              <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">person</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Pengguna</p>
                <h4 class="mb-0"><?php echo number_format($usersCount); ?></h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <!-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p> -->
            </div>
          </div>
        </div>
        <!-- Kolom 2: Kategori Dokumen -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2 position-relative">
              <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">layers</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Kategori Dokumen</p>
                <h4 class="mb-0"><?php echo number_format($categoriesCount); ?></h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <!-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month</p> -->
            </div>
          </div>
        </div>
        <!-- Kolom 3: Unggah Dokumen -->
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2 position-relative">
              <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-icons opacity-10">cloud_upload</i>
              </div>
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Unggah Dokumen</p>
                <h4 class="mb-0"><?php echo number_format($dokumenCount); ?></h4>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
              <!-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p> -->
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="row mb-4">
  <!-- Kolom Kiri: Grafik Harian Data (8 kolom pada layar besar) -->
  <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h6 class="mb-0">Grafik Harian Data</h6>
            <p class="text-sm mb-0">Data harian untuk Users, Dokumen, dan Categories</p>
          </div>
          <div class="col-md-4 text-end">
            <!-- Dropdown untuk memilih bulan -->
            <div class="input-group input-group-outline mb-3">
            <select id="monthSelector" class="form-control">
              <option value="1">Januari</option>
              <option value="2">Februari</option>
              <option value="3">Maret</option>
              <option value="4">April</option>
              <option value="5">Mei</option>
              <option value="6">Juni</option>
              <option value="7">Juli</option>
              <option value="8">Agustus</option>
              <option value="9">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="p-3">
          <canvas id="dailyChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Kolom Kanan: Orders Overview (4 kolom pada layar besar) -->
  <div class="col-lg-4 col-md-6">
  <div class="card">
      <!-- Card Header -->
      <div class="card-header pb-0">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h6 class="mb-0">Kalender</h6>
            <!-- <p class="text-sm mb-0">Jadwal kegiatan dan acara pendidikan</p> -->
          </div>
        </div>
      </div>
      <!-- Card Body -->
      <div class="card-body px-0 pb-2">
        <div class="p-3">
          <div id="calendar"></div>
        </div>
      </div>
    </div>
    </div>

    </div>
  </main>
  <?php
    include "live_chat.php";
  ?>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <!-- Sertakan JS Chart.js, jQuery, Popper.js, dan Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.4/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.4/index.global.min.js"></script>
  <!-- Sertakan semua locale FullCalendar agar bisa menggunakan locale 'id' -->
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.4/locales-all.global.min.js"></script>
  <!-- Sertakan Bootstrap JS -->
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var calendarEl = document.getElementById('calendar');
      var calendar;

      // Fungsi untuk merender kalender berdasarkan bulan tertentu
      function renderCalendar(month) {
        // Dapatkan tahun saat ini
        var currentYear = new Date().getFullYear();
        // Buat tanggal baru dengan tanggal pertama bulan yang dipilih
        var newDate = new Date(currentYear, month - 1, 1);
        
        // Jika kalender sudah ada, hancurkan agar tidak duplikasi
        if (calendar) {
          calendar.destroy();
        }
        
        calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'id', // Menggunakan locale Indonesia
          initialDate: newDate,
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          height: 500,
          // Data event: contoh data statis, ganti dengan AJAX bila perlu
          // events: [
          //   { title: 'Ujian Nasional', start: currentYear + '-' + (month < 10 ? '0' + month : month) + '-15' },
          //   { title: 'Hari Guru', start: currentYear + '-' + (month < 10 ? '0' + month : month) + '-05' },
          //   { title: 'Libur Sekolah', start: currentYear + '-' + (month < 10 ? '0' + month : month) + '-20', end: currentYear + '-' + (month < 10 ? '0' + month : month) + '-25' }
          // ],
          // Animasi transisi (FullCalendar sudah mendukung transisi halus secara default)
          eventDidMount: function(info) {
            // Tambahan styling atau marker jika diperlukan
            // Misalnya, tambahkan tooltip custom
            $(info.el).tooltip({
              title: info.event.title,
              placement: 'top',
              trigger: 'hover'
            });
          }
        });
        
        calendar.render();
      }
      
      // Set default dropdown ke bulan saat ini
      var currentMonth = new Date().getMonth() + 1;
      $('#monthSelectors').val(currentMonth);
      renderCalendar(currentMonth);
      
      // Update kalender ketika dropdown berubah
      $('#monthSelectors').on('change', function(){
        var selectedMonth = parseInt($(this).val());
        renderCalendar(selectedMonth);
      });
    });
  </script>
  <script>
  $(document).ready(function(){
    // Set default bulan ke bulan sekarang
    var currentMonth = new Date().getMonth() + 1;
    $('#monthSelector').val(currentMonth);

    var ctx = document.getElementById("dailyChart").getContext("2d");
    var dailyChart;

    function fetchAndRenderChart(month) {
      $.ajax({
        url: 'fetch_monthly_data.php',  // Pastikan file ini berada di lokasi yang tepat
        type: 'GET',
        data: { month: month },
        dataType: 'json',
        success: function(response) {
          // Response berisi: days, users, dokumen, categories
          var days = response.days; // Array hari, misalnya [1, 2, ..., 30]
          var usersData = response.users;
          var dokumenData = response.dokumen;
          var categoriesData = response.categories;

          // Jika grafik sudah ada, hancurkan sebelumnya agar bisa di-update
          if (dailyChart) {
            dailyChart.destroy();
          }

          dailyChart = new Chart(ctx, {
            type: 'line',  // Grafik garis
            data: {
              labels: days,
              datasets: [
                {
                  label: "Users",
                  data: usersData,
                  borderColor: "rgba(75, 192, 192, 1)",
                  backgroundColor: "rgba(75, 192, 192, 0.2)",
                  fill: false,
                  tension: 0.3,
                  pointStyle: 'circle',
                  pointRadius: 5,
                  pointBackgroundColor: "rgba(75, 192, 192, 1)"
                },
                {
                  label: "Dokumen",
                  data: dokumenData,
                  borderColor: "rgba(153, 102, 255, 1)",
                  backgroundColor: "rgba(153, 102, 255, 0.2)",
                  fill: false,
                  tension: 0.3,
                  pointStyle: 'circle',
                  pointRadius: 5,
                  pointBackgroundColor: "rgba(153, 102, 255, 1)"
                },
                {
                  label: "Categories",
                  data: categoriesData,
                  borderColor: "rgba(255, 159, 64, 1)",
                  backgroundColor: "rgba(255, 159, 64, 0.2)",
                  fill: false,
                  tension: 0.3,
                  pointStyle: 'circle',
                  pointRadius: 5,
                  pointBackgroundColor: "rgba(255, 159, 64, 1)"
                }
              ]
            },
            options: {
              responsive: true,
              animation: {
                duration: 2000  // Animasi 2 detik
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    precision: 0
                  }
                }
              },
              plugins: {
                legend: {
                  labels: {
                    font: { size: 14 }
                  }
                },
                tooltip: {
                  enabled: true,
                  mode: 'index',
                  intersect: false
                }
              }
            }
          });
        },
        error: function(xhr, status, error) {
          console.error("Error fetching daily data:", error);
        }
      });
    }

    // Render grafik untuk bulan default/current
    fetchAndRenderChart(currentMonth);

    // Ubah grafik ketika dropdown bulan berubah
    $('#monthSelector').on('change', function(){
      var selectedMonth = $(this).val();
      fetchAndRenderChart(selectedMonth);
    });
  });
</script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>