<?php

// Load environment variables if .env exists
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

function env_first(array $keys): ?string
{
    foreach ($keys as $key) {
        $value = getenv($key);
        if ($value !== false) {
            $value = trim((string) $value);
            if ($value !== '') {
                return $value;
            }
        }
    }
    return null;
}

$host = env_first(['DB_HOST', 'MYSQLHOST']);
$port = env_first(['DB_PORT', 'MYSQLPORT']);
$username = env_first(['DB_USER', 'MYSQLUSER']);
$password = env_first(['DB_PASSWORD', 'MYSQLPASSWORD']);
$database = env_first(['DB_NAME', 'MYSQLDATABASE']);

$dbUrl = env_first(['MYSQL_URL', 'DATABASE_URL', 'DB_URL']);
if ($dbUrl) {
    $parsed = parse_url($dbUrl);
    if ($parsed !== false) {
        $host = $host ?: ($parsed['host'] ?? null);
        $port = $port ?: (($parsed['port'] ?? null) ? (string) $parsed['port'] : null);
        $username = $username ?: ($parsed['user'] ?? null);
        $password = $password ?: ($parsed['pass'] ?? null);
        $path = $parsed['path'] ?? '';
        $database = $database ?: ($path ? ltrim($path, '/') : null);
    }
}

$host = $host ?: '127.0.0.1';
$port = (int) ($port ?: 3306);
$username = $username ?: 'root';
$password = $password ?? '';
$database = $database ?: 'pendaftar';

try {
    $koneksi = mysqli_init();
    mysqli_options($koneksi, MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    mysqli_real_connect($koneksi, $host, $username, $password, $database, $port);
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan variabel DB_HOST/DB_PORT/DB_USER/DB_PASSWORD/DB_NAME (atau MYSQL_URL) sudah benar di Railway.');
}

$tableCheck = mysqli_query($koneksi, "SHOW TABLES LIKE 'users'");
$usersTableExists = $tableCheck && mysqli_num_rows($tableCheck) > 0;

if (!$usersTableExists) {
    $sqlDumpPath = __DIR__ . '/../db/pendaftaran.sql';
    if (file_exists($sqlDumpPath)) {
        $sqlDump = file_get_contents($sqlDumpPath);
        if ($sqlDump !== false && trim($sqlDump) !== '') {
            if (mysqli_multi_query($koneksi, $sqlDump)) {
                do {
                    if ($result = mysqli_store_result($koneksi)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_more_results($koneksi) && mysqli_next_result($koneksi));
            }
        }
    }
}

mysqli_set_charset($koneksi, 'utf8mb4');

?>