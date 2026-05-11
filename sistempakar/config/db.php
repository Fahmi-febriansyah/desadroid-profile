<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'desadroi_fahmi');
define('DB_PASS', 'desadroid123');
define('DB_NAME', 'desadroi_sistem_pakar_mobil');

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
    $conn = $pdo; // Maintain compatibility with existing code using $conn
} catch(PDOException $e) {
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
    // Redirect logic: if in admin folder, go to admin/login.php, else root login.php
    $redirect = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? 'login.php?expired=1' : 'login.php?expired=1';
    header('Location: ' . $redirect);
    exit;
}
$_SESSION['last_activity'] = time();
