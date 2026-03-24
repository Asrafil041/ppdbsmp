<?php

function redirect_setelah_submit($url)
{
    echo '<script>window.location.href="' . $url . '";</script>';
    exit;
}

$id_user = $_SESSION['id_users'];
$id_pendaftar = null;
$data_pendaftar = [
    'nama' => '',
    'tmpt_lahir' => '',
    'tgl_lahir' => '',
    'jenis_kelamin' => '',
    'agama' => '',
    'alamat' => '',
    'email' => '',
    'telepon' => '',
    'foto' => '',
    'foto_SKHU' => '',
    'foto_skhu' => ''
];

$sql_pendaftar = "SELECT * FROM pendaftar where users_id = '$id_user'";
$result_pendaftar = mysqli_query($koneksi, $sql_pendaftar);

if($result_pendaftar && mysqli_num_rows($result_pendaftar)) {
    $data_pendaftar = mysqli_fetch_array($result_pendaftar);
    $id_pendaftar = $data_pendaftar['id'];
}

$kolom_foto_skhu = null;
$cek_kolom_foto_skhu = mysqli_query($koneksi, "SHOW COLUMNS FROM pendaftar LIKE 'foto_skhu'");
if($cek_kolom_foto_skhu && mysqli_num_rows($cek_kolom_foto_skhu) > 0) {
    $kolom_foto_skhu = 'foto_skhu';
} else {
    $cek_kolom_foto_SKHU = mysqli_query($koneksi, "SHOW COLUMNS FROM pendaftar LIKE 'foto_SKHU'");
    if($cek_kolom_foto_SKHU && mysqli_num_rows($cek_kolom_foto_SKHU) > 0) {
        $kolom_foto_skhu = 'foto_SKHU';
    }
}

if(isset($_POST['btn_simpan']) && $_POST['btn_simpan'] == 'simpan_profil') {
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? date("Y-m-d", strtotime($_POST['tanggal_lahir'])) : null;
    $jenis_kelamin = isset($_POST['jenis_kelamin']) && in_array($_POST['jenis_kelamin'], ['L', 'P']) ? $_POST['jenis_kelamin'] : null;
    $jenis_kelamin_sql = $jenis_kelamin ? "'" . $jenis_kelamin . "'" : "NULL";
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];

    if($id_pendaftar === null) {
        $sql_insert_profil = "INSERT INTO pendaftar (nama, tmpt_lahir, tgl_lahir, jenis_kelamin, agama, alamat, email, telepon, foto, users_id) VALUES ('$nama', '$tempat_lahir', " . ($tanggal_lahir ? "'$tanggal_lahir'" : "NULL") . ", $jenis_kelamin_sql, '$agama', '$alamat', '$email', '$telepon', '', '$id_user')";
        $query_insert_profil = mysqli_query($koneksi, $sql_insert_profil);

        if(!$query_insert_profil) {
            echo "Gagal membuat data profil"; die;
        }

        $id_pendaftar = mysqli_insert_id($koneksi);
    }

    if($_FILES['gambar']['name'] != '') {
        $ekstensi_diperbolehkan = array('png','jpg');
        $nama_gambar = $_FILES['gambar']['name'];
        $x = explode('.', $nama_gambar);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];

        $ubah_nama = 'profil_' . $nama . '.' . $ekstensi;

        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if($ukuran < 1044070) {
                move_uploaded_file($file_tmp, '../uploads/' . $ubah_nama);

                $sql_update_foto = "UPDATE pendaftar SET foto = '$ubah_nama' WHERE id='$id_pendaftar'";
                $query_update = mysqli_query($koneksi, $sql_update_foto);

                if(!$query_update) {
                    echo "GAGAL UPLOAD FOTO PROFIL"; die;
                }
            } else {
                echo "Gambar terlalu besar"; die;
            }
        } else {
            echo "Ekstensi tidak diperbolehkan"; die;
        }
    }

    if($_FILES['foto_skhu']['name'] != '') {
        $ekstensi_diperbolehkan = array('png','jpg','jpeg');
        $nama_skhu = $_FILES['foto_skhu']['name'];
        $x = explode('.', $nama_skhu);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto_skhu']['size'];
        $file_tmp = $_FILES['foto_skhu']['tmp_name'];

        $ubah_nama_skhu = 'skhu_' . $nama . '.' . $ekstensi;

        if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if($ukuran < 1044070) {
                move_uploaded_file($file_tmp, '../uploads/' . $ubah_nama_skhu);

                if($kolom_foto_skhu !== null) {
                    $sql_update_skhu = "UPDATE pendaftar SET $kolom_foto_skhu = '$ubah_nama_skhu' WHERE id='$id_pendaftar'";
                    $query_update_skhu = mysqli_query($koneksi, $sql_update_skhu);

                    if(!$query_update_skhu) {
                        echo "GAGAL UPLOAD FOTO SKHU"; die;
                    }
                }
            } else {
                echo "Gambar SKHU terlalu besar"; die;
            }
        } else {
            echo "Ekstensi SKHU tidak diperbolehkan"; die;
        }
    }

    $sql_update_profil = "UPDATE pendaftar SET 
                            nama='$nama', 
                            tmpt_lahir='$tempat_lahir', 
                            tgl_lahir=" . ($tanggal_lahir ? "'$tanggal_lahir'" : "NULL") . ", 
                                                        jenis_kelamin=$jenis_kelamin_sql, 
                            agama='$agama', 
                            alamat='$alamat', 
                            email='$email', 
                            telepon='$telepon' 
                          WHERE id='$id_pendaftar'";

    $query_update_profil = mysqli_query($koneksi, $sql_update_profil);

    if($query_update_profil) {
        $_SESSION['pesan_sukses'] = "Edit Profil Sukses!";
        redirect_setelah_submit('dashboard.php');
    } else {
        echo "Gagal update data profil"; die;
    }
}

?>
