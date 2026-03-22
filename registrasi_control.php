<?php

include('config/koneksi.php');
session_start();

if(isset($_POST['btn_registrasi'])) {
    // print_r($_POST);

    $nama = trim($_POST['nama']);
    $tempat_lahir = trim($_POST['tempat_lahir']);
    $tanggal_lahir = date("Y-m-d", strtotime($_POST['tanggal_lahir']));
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : null;
    $agama = trim($_POST['agama']);
    $alamat = trim($_POST['alamat']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $foto = '';
    $password = md5($_POST['password']);
    $ulangi_password = md5($_POST['ulangi_password']);

    if($password != $ulangi_password) {
        echo "Error: Password tidak sama";
        echo "<br><br> <button type='button' onclick='history.back();'> Kembali </button>";
        die;
    }

    mysqli_begin_transaction($koneksi);

    try {
        $stmt_user = mysqli_prepare($koneksi, "INSERT INTO users (nama, username, password, level) VALUES (?, ?, ?, 'siswa')");
        if (!$stmt_user) {
            throw new Exception("Gagal menyiapkan query users.");
        }

        mysqli_stmt_bind_param($stmt_user, "sss", $nama, $email, $password);
        mysqli_stmt_execute($stmt_user);

        $id_user = mysqli_insert_id($koneksi);
        mysqli_stmt_close($stmt_user);

        $stmt_pendaftar = mysqli_prepare($koneksi, "INSERT INTO pendaftar (nama, tmpt_lahir, tgl_lahir, jenis_kelamin, agama, alamat, email, telepon, foto, users_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt_pendaftar) {
            throw new Exception("Gagal menyiapkan query pendaftar.");
        }

        mysqli_stmt_bind_param($stmt_pendaftar, "sssssssssi", $nama, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email, $telepon, $foto, $id_user);
        mysqli_stmt_execute($stmt_pendaftar);
        mysqli_stmt_close($stmt_pendaftar);

        mysqli_commit($koneksi);

        $_SESSION['pesan_registrasi'] = "Registrasi Berhasil, Login Menggunakan Email dan Password anda!";
        header('location:login.php');
        exit;

    } catch (Throwable $e) {
        mysqli_rollback($koneksi);
        echo "Error registrasi: " . $e->getMessage();
        echo "<br><br> <button type='button' onclick='history.back();'> Kembali </button>";
        die;
    }

} else {
    header('location:registrasi.php');
    exit;
}

?>