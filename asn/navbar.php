<?php
// navbar_notif.php
// session_start();
include '../database/koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}

$user_id         = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'];
$currentRole     = $_SESSION['role'];
?>

  <!-- Navbar -->
  <div class="collapse navbar-collapse" id="navbar">
    <ul class="navbar-nav ms-auto justify-content-end">
      <!-- Icon Notifikasi -->
      <li class="nav-item dropdown pe-2 d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" onclick="markAllAsRead()">
          <i class="fa fa-bell cursor-pointer"></i>
          <span class="badge bg-danger text-white rounded-circle" style="font-size: 12px;" id="notif-badge" hidden>0</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton" id="notif-dropdown">
          <!-- Notifikasi akan di-update lewat JavaScript -->
          <li class="mb-2">
            <a class="dropdown-item border-radius-md" href="javascript:;">
              <div class="d-flex py-1">
                <p class="text-xs text-secondary mb-0">Tidak ada notifikasi baru.</p>
              </div>
            </a>
          </li>
        </ul>
      </li>
      <!-- Profile Dropdown -->
      <li class="nav-item dropdown d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-body font-weight-bold px-0" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-user me-sm-1"></i>
          <span class="d-sm-inline d-none"><strong><?php echo htmlspecialchars($currentUsername); ?></strong></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="profileDropdown">
          <li>
            <a class="dropdown-item" href="profile.php">
              <i class="fa fa-user me-2"></i> Profile
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="../logout.php">
              <i class="fa fa-sign-out-alt me-2"></i> Logout
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
  
  <!-- Audio Notifikasi -->
  <audio id="notif-audio" src="../assets/audio/pesan.mp3"></audio>
  
  <script>
    let prevUnread = 0;
    
    function updateNotifications() {
      fetch('fetch_notifications.php')
        .then(response => response.json())
        .then(data => {
          let totalUnread = 0;
          let html = "";
          if (data.length > 0) {
            data.forEach(function(notif) {
              if (notif.read_status == 0) totalUnread++;
              let notifMessage = "";
              if (notif.source === "user") {
                notifMessage = "Pengguna <strong>" + notif.username + "</strong> dengan role <strong>" + notif.role + "</strong> berhasil ditambahkan.";
              } else if (notif.source === "kategori") {
                notifMessage = "Jenis Dokumen <strong>" + notif.name + "</strong> dengan topik <strong>" + notif.topik + "</strong> berhasil ditambahkan.";
              } else if (notif.source === "dokumen") {
                notifMessage = "Dokumen <strong>" + notif.name + "</strong> dengan akses level <strong>" + notif.access_level + "</strong> berhasil ditambahkan.";
              }
              html += '<li class="mb-2">';
              html += '  <a class="dropdown-item border-radius-md d-flex justify-content-between" href="javascript:;" style="background-color: ' + (notif.read_status == 0 ? '#f8f9fa' : 'transparent') + ';">';
              html += '    <div class="d-flex py-1">';
              html += '      <div class="my-auto"><img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="avatar"></div>';
              html += '      <div class="d-flex flex-column justify-content-center">';
              html += '        <h6 class="text-sm font-weight-normal mb-1">Notifikasi | <span class="font-weight-bold">' + notif.created_by + '</span></h6>';
              html += '        <p class="text-xs text-secondary mb-0">' + notifMessage + '</p>';
              html += '        <p class="text-xs text-secondary mb-0"><i class="fa fa-clock me-1"></i>' + notif.created_at + '</p>';
              html += '      </div>';
              html += '    </div>';
              html += '    <button class="btn btn-sm btn-light rounded-circle" style="width: 35px; height: 30px;" onclick="deleteNotif(' + notif.id + ', \'' + notif.source + '\')"><i class="fa fa-times"></i></button>';
              html += '  </a>';
              html += '</li>';
            });
          } else {
            html = '<li class="mb-2"><a class="dropdown-item border-radius-md" href="javascript:;"><div class="d-flex py-1"><p class="text-xs text-secondary mb-0">Tidak ada notifikasi baru.</p></div></a></li>';
          }
          document.getElementById("notif-dropdown").innerHTML = html;
          const badge = document.getElementById("notif-badge");
          if (totalUnread > 0) {
            badge.innerText = totalUnread;
            badge.hidden = false;
            if (totalUnread > prevUnread) {
              playNotificationSound();
            }
          } else {
            badge.hidden = true;
          }
          prevUnread = totalUnread;
        })
        .catch(error => console.error("Fetch error:", error));
    }
    
    setInterval(updateNotifications, 5000);
    document.addEventListener("DOMContentLoaded", updateNotifications);
    
    function markAllAsRead() {
      fetch('notification_actions.php?action=read_all', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            updateNotifications();
          } else {
            console.error("Gagal menandai notifikasi sebagai dibaca:", data);
          }
        })
        .catch(error => console.error("Fetch error:", error));
    }
    
    function deleteNotif(notifId, source) {
      let notif_table = "";
      if (source === "user") {
        notif_table = "notif_users";
      } else if (source === "kategori") {
        notif_table = "notif_kategori";
      } else if (source === "dokumen") {
        notif_table = "notif_dokumen";
      }
      console.log("deleteNotif() called with:", "notifId =", notifId, "source =", source, "notif_table =", notif_table);
      fetch(`notification_actions.php?action=clear&notif_id=${notifId}&notif_table=${notif_table}`, { method: "GET" })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            updateNotifications();
          } else {
            alert("Gagal menghapus notifikasi: " + data.message);
          }
        })
        .catch(error => console.error("Fetch error:", error));
    }
    
    function playNotificationSound() {
      const audio = document.getElementById("notif-audio");
      if (audio) {
        audio.play().catch(error => console.error("Audio play error:", error));
      }
    }
  </script>