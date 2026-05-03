<?php
$host = "localhost";
define('DB_USER', 'desadroi_fahmi');
define('DB_PASS', 'desadroid123');
define('DB_NAME', 'desadroi_konsul');

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
