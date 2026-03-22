<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include('koneksi.php');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
$basePath = preg_replace('#/(admin|siswa)/[^/]+$#', '', $scriptPath);
$basePath = preg_replace('#/[^/]+$#', '', (string) $basePath);
$basePath = rtrim((string) $basePath, '/');
$base_url = $scheme . '://' . $host . ($basePath !== '' ? $basePath : '');

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri_segment = array_values(array_filter(explode('/', (string) $requestPath), static function ($value) {
    return $value !== '';
}));

$firstSegment = $uri_segment[0] ?? '';

if(isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    // lanjut
    if($firstSegment == $_SESSION['level']) {
        // lanjut
    } else {
        echo "Error: Forbidden !!!";
        echo "<br><br> <button type='button' onclick='history.back()'> Kembali </button>";
        die;
    }

} else {
    $_SESSION['login_error'] = "Silahkan Login untuk masuk kedalam sistem";
    header('location:'. $base_url . '/login.php');
    exit;
}

?>