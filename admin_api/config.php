<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'desadroi_fahmi'); // Or 'root' locally
define('DB_PASS', 'desadroid123'); // Or '' locally

// Database Names
define('DB_PORTFOLIO', 'desadroi_desadroid_portfolio'); // desadroid_portfolio on local? Using what we found earlier. Wait, SHOW DATABASES showed "desadroid_portfolio" and "proyekdesa".
// Let's use the actual live names from cPanel
define('DB_PORTFOLIO_LOCAL', 'desadroi_desadroid_portfolio');
define('DB_PROYEK_LOCAL', 'desadroi_proyekdesa');

try {
    // Connect to Portfolio DB
    $pdo_portofolio = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_PORTFOLIO_LOCAL . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Connect to Proyek DB
    $pdo_proyek = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_PROYEK_LOCAL . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
