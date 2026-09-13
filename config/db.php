<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'desadroi_fahmi');
define('DB_PASS', 'desadroid123');
define('DB_NAME', 'desadroi_desadroid_portfolio');

try {
    // Try primary credentials (production/cPanel)
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    try {
        // Fallback to local development credentials (XAMPP root)
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=desadroid_portfolio;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch (PDOException $ex) {
        die('Database Error: ' . $ex->getMessage());
    }
}

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session Timeout for Admin (30 minutes)
$timeout = 30 * 60;
if (isset($_SESSION['admin_id'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_email'], $_SESSION['admin_name'], $_SESSION['admin_role']);
        header('Location: /portofolio perusahaan/admin/login.php?expired=1');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

