<?php
include '../koneksi.php';
$q = mysqli_query($koneksi, "SELECT * FROM rule");
echo "<table border='1'>";
while($r = mysqli_fetch_assoc($q)) {
    echo "<tr><td>{$r['kode_rule']}</td><td>{$r['kondisi']}</td><td>{$r['hasil']}</td></tr>";
}
echo "</table>";
?>
