<?php
// Koneksi database
include '../database/koneksi.php';

// Tangkap keyword dari request
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Query pencarian
$sql = "SELECT * FROM categories 
        WHERE name LIKE '%$keyword%' 
        OR topik LIKE '%$keyword%' 
        OR fungsi LIKE '%$keyword%' 
        ORDER BY id DESC";

// Eksekusi query
$result = $connection->query($sql);

// Debugging query jika error
if (!$result) {
    die("Error pada query: " . $connection->error);
}

// Hasil pencarian
if ($result->num_rows > 0) {
    $no = 1;
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

// Tutup koneksi
$connection->close();
?>

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