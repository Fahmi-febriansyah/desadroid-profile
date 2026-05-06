<?php
define('DB_HOST', 'localhost');

define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'konsul'); 

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

    $koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$koneksi) {
        throw new Exception("MySQLi Connection failed: " . mysqli_connect_error());
    }

} catch(Exception $e) {
    die('Database Error: ' . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout = 30 * 60;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_destroy();

    header('Location: /desadroid/psikologi/login.php?expired=1');
    exit;
}
$_SESSION['last_activity'] = time();
?>
