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

if(isset($_POST['btn_login'])) {
    // jika sudah ditekan
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $sql_user = "SELECT * FROM users where username = '$username' and password = '$password'";
    $result_user = mysqli_query($koneksi, $sql_user);

    if($result_user && mysqli_num_rows($result_user) > 0) {
        // jika data tersedia, simpan data user kedalam session
        $data_user = mysqli_fetch_assoc($result_user);
        if ($data_user) {
            $_SESSION['status'] = 'login';
            $_SESSION['id_users'] = $data_user['id'];
            $_SESSION['nama'] = $data_user['nama'];
            $_SESSION['level'] = $data_user['level'];

            if($data_user['level'] == 'admin') {
                header('location:admin/dashboard.php');
                exit;

            } else if($data_user['level'] == 'siswa') {
                header('location:siswa/dashboard.php');
                exit;

            }
        }
    } else if(!$result_user) {
        $_SESSION['login_error'] = "Terjadi masalah koneksi database. Coba lagi sebentar.";
        header('location:login.php');
        exit;
    } else {
        $_SESSION['login_error'] = "Username atau Password anda Salah!";
        header('location:login.php');
        exit;

    }

} else {
    header('location:login.php');
    exit;

}

?>