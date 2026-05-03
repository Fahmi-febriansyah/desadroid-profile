<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    
    // Jika ada session login user, gunakan id_user. Jika tidak, set NULL.
    $id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 'NULL';
    
    // Cegah double testimoni jika login
    if ($id_user !== 'NULL') {
        $cek = mysqli_query($koneksi, "SELECT id_testimoni FROM testimoni WHERE id_user = $id_user");
        if (mysqli_num_rows($cek) > 0) {
            $_SESSION['pesan'] = "Anda sudah pernah mengirimkan testimoni.";
            header("Location: testimoni.php");
            exit();
        }
    }

    // Status default 'pending' sesuai dengan enum pada database
    $query = "INSERT INTO testimoni (id_user, isi, status) VALUES ($id_user, '$isi', 'pending')";
    
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = "Terima kasih! Testimoni Anda telah terkirim dan menunggu moderasi.";
    } else {
        $_SESSION['pesan'] = "Maaf, terjadi kesalahan: " . mysqli_error($koneksi);
    }
    
    header("Location: testimoni.php");
    exit();
} else {
    header("Location: testimoni.php");
    exit();
}
?>
