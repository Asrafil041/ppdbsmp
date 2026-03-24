<?php

$id_user = $_SESSION['id_users'];
$data_pendaftar = [];
$status = null;

$stmt_pendaftar = mysqli_prepare($koneksi, "SELECT * FROM pendaftar WHERE users_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt_pendaftar, "i", $id_user);
mysqli_stmt_execute($stmt_pendaftar);
$result_pendaftar = mysqli_stmt_get_result($stmt_pendaftar);

if ($result_pendaftar && mysqli_num_rows($result_pendaftar) > 0) {
    $data_pendaftar = mysqli_fetch_assoc($result_pendaftar);
} else {
    $stmt_user = mysqli_prepare($koneksi, "SELECT nama, username FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_user, "i", $id_user);
    mysqli_stmt_execute($stmt_user);
    $result_user = mysqli_stmt_get_result($stmt_user);
    $data_user = $result_user ? mysqli_fetch_assoc($result_user) : null;

    if ($data_user) {
        $nama = $data_user['nama'] ?? '';
        $email = $data_user['username'] ?? '';
        $foto = '';
        $stmt_insert_pendaftar = mysqli_prepare($koneksi, "INSERT INTO pendaftar (nama, tmpt_lahir, tgl_lahir, jenis_kelamin, agama, alamat, email, telepon, foto, users_id) VALUES (?, '', NULL, NULL, '', '', ?, '', ?, ?)");
        mysqli_stmt_bind_param($stmt_insert_pendaftar, "sssi", $nama, $email, $foto, $id_user);
        mysqli_stmt_execute($stmt_insert_pendaftar);

        $stmt_refetch = mysqli_prepare($koneksi, "SELECT * FROM pendaftar WHERE users_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt_refetch, "i", $id_user);
        mysqli_stmt_execute($stmt_refetch);
        $result_refetch = mysqli_stmt_get_result($stmt_refetch);
        if ($result_refetch && mysqli_num_rows($result_refetch) > 0) {
            $data_pendaftar = mysqli_fetch_assoc($result_refetch);
        }
    }
}

if (!empty($data_pendaftar) && isset($data_pendaftar['id'])) {
    $id_pendaftar = (int) $data_pendaftar['id'];

    $stmt_nilai = mysqli_prepare($koneksi, "SELECT * FROM nilai WHERE pendaftar_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt_nilai, "i", $id_pendaftar);
    mysqli_stmt_execute($stmt_nilai);
    $result_nilai = mysqli_stmt_get_result($stmt_nilai);

    if ($result_nilai && mysqli_num_rows($result_nilai) > 0) {
        $data_nilai = mysqli_fetch_assoc($result_nilai);
        $status = $data_nilai['status'];
    }

    if(isset($_POST['btn_simpan']) && $_POST['btn_simpan'] == 'simpan_nilai') {
        $un = (float) $_POST['un'];
        $us = (float) $_POST['us'];
        $uts_1 = (float) $_POST['uts_1'];

        $stmt_insert_nilai = mysqli_prepare($koneksi, "INSERT INTO nilai (nilai_un, nilai_us, nilai_uts_1, status, pendaftar_id) VALUES (?, ?, ?, 0, ?)");
        mysqli_stmt_bind_param($stmt_insert_nilai, "dddi", $un, $us, $uts_1, $id_pendaftar);
        $query_insert_nilai = mysqli_stmt_execute($stmt_insert_nilai);

        if($query_insert_nilai){
            header('location:dashboard.php');
            exit;
        } else {
            echo "error ". mysqli_error($koneksi);
            die;
        }
    }
}




?>
