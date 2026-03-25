<?php

$forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
if (strpos($forwardedProto, ',') !== false) {
    $forwardedProto = trim(explode(',', $forwardedProto)[0]);
}

$isHttps = (
    (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') ||
    strtolower((string) $forwardedProto) === 'https' ||
    strtolower((string) ($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '')) === 'on' ||
    strtolower((string) ($_SERVER['REQUEST_SCHEME'] ?? '')) === 'https'
);

$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? '');
if ($host !== '' && stripos($host, 'railway.app') !== false && !$isHttps) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    header('Location: https://' . $host . $requestUri, true, 301);
    exit;
}

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
    $foto_skhu = '';
    $password = md5($_POST['password']);
    $ulangi_password = md5($_POST['ulangi_password']);

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

    if($password != $ulangi_password) {
        echo "Error: Password tidak sama";
        echo "<br><br> <button type='button' onclick='history.back();'> Kembali </button>";
        die;
    }

    if(isset($_FILES['foto_SKHU']) && $_FILES['foto_SKHU']['name'] != '') {
        $ekstensi_diperbolehkan = array('png','jpg','jpeg');
        $nama_file = $_FILES['foto_SKHU']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto_SKHU']['size'];
        $tmp_file = $_FILES['foto_SKHU']['tmp_name'];

        if(!in_array($ekstensi, $ekstensi_diperbolehkan, true)) {
            echo "Ekstensi SKHU tidak diperbolehkan";
            echo "<br><br> <button type='button' onclick='history.back();'> Kembali </button>";
            die;
        }

        if($ukuran > 1048576) {
            echo "Ukuran SKHU terlalu besar (maks 1MB)";
            echo "<br><br> <button type='button' onclick='history.back();'> Kembali </button>";
            die;
        }

        $foto_skhu = 'skhu_' . time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', strstr($email, '@', true)) . '.' . $ekstensi;
        if(!move_uploaded_file($tmp_file, __DIR__ . '/uploads/' . $foto_skhu)) {
            echo "Gagal mengunggah foto SKHU";
            echo "<br><br> <button type='button' onclick='history.back();'> Kembali </button>";
            die;
        }
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

        if($kolom_foto_skhu !== null) {
            $sql_pendaftar = "INSERT INTO pendaftar (nama, tmpt_lahir, tgl_lahir, jenis_kelamin, agama, alamat, email, telepon, foto, $kolom_foto_skhu, users_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_pendaftar = mysqli_prepare($koneksi, $sql_pendaftar);
            if (!$stmt_pendaftar) {
                throw new Exception("Gagal menyiapkan query pendaftar.");
            }
            mysqli_stmt_bind_param($stmt_pendaftar, "ssssssssssi", $nama, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email, $telepon, $foto, $foto_skhu, $id_user);
        } else {
            $stmt_pendaftar = mysqli_prepare($koneksi, "INSERT INTO pendaftar (nama, tmpt_lahir, tgl_lahir, jenis_kelamin, agama, alamat, email, telepon, foto, users_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt_pendaftar) {
                throw new Exception("Gagal menyiapkan query pendaftar.");
            }
            mysqli_stmt_bind_param($stmt_pendaftar, "sssssssssi", $nama, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $agama, $alamat, $email, $telepon, $foto, $id_user);
        }
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