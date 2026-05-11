<?php
date_default_timezone_set('Asia/Jakarta');
define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'konsul'); 
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

function format_indo($datetime, $format = 'd M Y, H:i') {
    if (!$datetime || $datetime == '0000-00-00 00:00:00') return '-';
    
    $time = strtotime($datetime);
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $bulan_pendek = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    
    $d = date('d', $time);
    $m_idx = (int)date('m', $time);
    $y = date('Y', $time);
    $h = date('H:i', $time);
    
    if ($format == 'd M Y') return "$d " . $bulan_pendek[$m_idx] . " $y";
    if ($format == 'd F Y') return "$d " . $bulan[$m_idx] . " $y";
    if ($format == 'd F Y, H:i') return "$d " . $bulan[$m_idx] . " $y, $h WIB";
    if ($format == 'M') return $bulan_pendek[$m_idx];
    if ($format == 'F') return $bulan[$m_idx];
    
    // Default format d M Y, H:i
    return "$d " . $bulan_pendek[$m_idx] . " $y, $h";
}
?>
