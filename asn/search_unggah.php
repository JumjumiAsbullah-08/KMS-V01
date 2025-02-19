<?php
// Koneksi database
include '../database/koneksi.php';

// Tangkap keyword dari request
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

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
        WHERE c.name LIKE ? 
           OR c.topik LIKE ? 
           OR c.fungsi LIKE ?
           OR d.access_level LIKE ?
        ORDER BY d.id_dokumen DESC";

// Prepared statement untuk menghindari SQL Injection
$stmt = $connection->prepare($sql);
$search = "%$keyword%";
$stmt->bind_param("ssss", $search, $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

// Debugging query jika error
if (!$result) {
    die("Error pada query: " . $connection->error);
}

// Hasil pencarian
if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        // Ambil ekstensi file
        $file_extension = strtolower(pathinfo($row['dokumen_name'], PATHINFO_EXTENSION));

        // Tentukan ikon berdasarkan ekstensi file dengan path lengkap
        $icon_map = [
            'pdf'  => 'PDF.png',
            'xlsx' => 'xlsx.png',
            'xls'  => 'xlsx.png',
            'docx' => 'word.png',
            'doc'  => 'word.png'
        ];
        $icon_file = isset($icon_map[$file_extension]) ? $icon_map[$file_extension] : 'default.png';
        $icon_path = "../assets/img/" . $icon_file;

        // Path file untuk download (gunakan nama file asli)
        $file_link = "../uploads/" . $row['dokumen_name'];

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
                            <img src='" . $icon_path . "' alt='" . $file_extension . "' style='width:30px; height:30px; margin-right:5px;'>
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

                    // Tutup koneksi
                    $stmt->close();
                    $connection->close();
                    ?>
<!-- Script untuk menangani aksi tombol -->
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