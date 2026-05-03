<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'desadroi_fahmi');
define('DB_PASS', 'desadroid123');
define('DB_NAME', 'desadroi_konsul'); // Menggunakan nama DB dari perubahan terakhir Anda

try {
    // PDO Connection (Requested)
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // MySQLi Connection (Compatibility for current code)
    $koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$koneksi) {
        throw new Exception("MySQLi Connection failed: " . mysqli_connect_error());
    }

} catch(Exception $e) {
    die('Database Error: ' . $e->getMessage());
}

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session Timeout (30 minutes)
$timeout = 30 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_destroy();
    // Redirect disesuaikan dengan folder project psikologi
    header('Location: /desadroid/psikologi/login.php?expired=1');
    exit;
}
$_SESSION['last_activity'] = time();
?>
