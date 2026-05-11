<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_auth']) || !isset($_SESSION['active_konsultasi'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST['gejala'])) {
    header("Location: konsultasi.php");
    exit;
}

$id_user = $_SESSION['user_auth']['id_user'];
$merk_mobil = $_SESSION['active_konsultasi']['merk_mobil'];
$id_kategori = $_SESSION['active_konsultasi']['id_kategori'];

$selected_gejala = $_POST['gejala'];
$cf_user = $_POST['cf_user']; 

// Ambil rules spesifik untuk Kategori Mesin ini
$in_clause = implode(',', array_map('intval', $selected_gejala));
$stmt = $conn->prepare("SELECT * FROM rule_cf WHERE id_kategori = ? AND id_gejala IN ($in_clause)");
$stmt->execute([$id_kategori]);
$rules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$kerusakan_cf = []; 

foreach($rules as $rule) {
    $id_k = $rule['id_kerusakan'];
    $id_g = $rule['id_gejala'];
    $cf_pakar = (float) $rule['cf_pakar'];
    $user_cf_val = (float) $cf_user[$id_g];
    
    $cf_he = $user_cf_val * $cf_pakar;
    
    if(!isset($kerusakan_cf[$id_k])) {
        $kerusakan_cf[$id_k] = $cf_he;
    } else {
        $cf_old = $kerusakan_cf[$id_k];
        $kerusakan_cf[$id_k] = $cf_old + ($cf_he * (1 - $cf_old));
    }
}

if(empty($kerusakan_cf)) {
    $_SESSION['error'] = "Tidak dapat menemukan diagnosa dari gejala yang dipilih.";
    header("Location: konsultasi.php");
    exit;
}

arsort($kerusakan_cf);

$highest_id_k = key($kerusakan_cf);
$highest_cf_val = current($kerusakan_cf);
$nilai_persen = number_format($highest_cf_val * 100, 2);

$stmt = $conn->prepare("SELECT * FROM kerusakan WHERE id_kerusakan = ?");
$stmt->execute([$highest_id_k]);
$hasil_k = $stmt->fetch(PDO::FETCH_ASSOC);

// Save to DB (Tambahkan id_kategori)
$stmt = $conn->prepare("INSERT INTO konsultasi (id_user, merk_mobil, id_kategori, hasil_diagnosa, nilai_cf) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$id_user, $merk_mobil, $id_kategori, $hasil_k['nama_kerusakan'], $nilai_persen]);
$id_konsultasi = $conn->lastInsertId();

foreach($selected_gejala as $id_g) {
    $s = $conn->query("SELECT kode_gejala, nama_gejala FROM gejala WHERE id_gejala = " . intval($id_g))->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("INSERT INTO detail_konsultasi (id_konsultasi, id_gejala, kode_gejala, nama_gejala, cf_user) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_konsultasi, $id_g, $s['kode_gejala'], $s['nama_gejala'], $cf_user[$id_g]]);
}

$_SESSION['id_konsultasi'] = $id_konsultasi;
unset($_SESSION['active_konsultasi']);

// Hapus draft dari database karena konsultasi sudah selesai
$stmt = $conn->prepare("UPDATE user SET draft_merk_mobil = NULL, draft_id_kategori = NULL WHERE id_user = ?");
$stmt->execute([$id_user]);

header("Location: hasil.php");
exit;
?>
