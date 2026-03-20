<?php

// Load environment variables if .env exists
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

// Get database config from environment or use defaults
$host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: 'localhost';
$port = (int) (getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306);
$username = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '';
$database = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'pendaftar';

$koneksi = mysqli_connect($host, $username, $password, $database, $port);

if (!$koneksi) {
    http_response_code(500);
    exit('Koneksi database gagal. Periksa konfigurasi environment database.');
}

mysqli_set_charset($koneksi, 'utf8mb4');

?>