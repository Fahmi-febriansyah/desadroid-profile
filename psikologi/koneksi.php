<?php
// Database Configuration
define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'desadroid_portfolio');
define('DB_USER', 'desadroi_fahmi');
define('DB_PASS', 'desadroid123');
define('DB_NAME', 'desadroi_konsul');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    die('Database Error: ' . $e->getMessage());
}

// Compatibility: buat juga koneksi mysqli karena banyak file di project
// masih menggunakan fungsi mysqli_* (mis. mysqli_query, mysqli_real_escape_string)
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$koneksi) {
    die('MySQLi Connection Error: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, 'utf8mb4');

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session Timeout (30 minutes)
$timeout = 30 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_destroy();
    header('Location: /portofolio perusahaan/admin/login.php?expired=1');
    exit;
}
$_SESSION['last_activity'] = time();