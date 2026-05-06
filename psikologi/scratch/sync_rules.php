<?php
include 'koneksi.php';

$rules = [
    ['R15', 'A1-A14 tersedia', 'Hitung Total Skor HARS'],
    ['R16', 'Total >= 0 AND Total <= 13', 'Tidak ada kecemasan'],
    ['R17', 'Total >= 14 AND Total <= 20', 'Kecemasan ringan'],
    ['R18', 'Total >= 21 AND Total <= 27', 'Kecemasan sedang'],
    ['R19', 'Total >= 28 AND Total <= 41', 'Kecemasan berat'],
    ['R20', 'Total >= 42 AND Total <= 56', 'Kecemasan sangat berat']
];

foreach ($rules as $rule) {
    $kode = $rule[0];
    $kondisi = mysqli_real_escape_string($koneksi, $rule[1]);
    $hasil = mysqli_real_escape_string($koneksi, $rule[2]);

    $check = mysqli_query($koneksi, "SELECT id_rule FROM rule WHERE kode_rule = '$kode'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($koneksi, "INSERT INTO rule (kode_rule, kondisi, hasil) VALUES ('$kode', '$kondisi', '$hasil')");
        echo "Inserted $kode\n";
    } else {
        mysqli_query($koneksi, "UPDATE rule SET kondisi = '$kondisi', hasil = '$hasil' WHERE kode_rule = '$kode'");
        echo "Updated $kode\n";
    }
}
?>
