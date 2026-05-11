<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_auth'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['merk_mobil']) && isset($_POST['id_kategori'])) {
    $id_kat = intval($_POST['id_kategori']);
    $stmt = $conn->prepare("SELECT nama_kategori FROM kategori_mesin WHERE id_kategori = ?");
    $stmt->execute([$id_kat]);
    $nama_kat = $stmt->fetchColumn();

    if ($nama_kat) {
        $_SESSION['temp_konsultasi'] = [
            'merk_mobil' => htmlspecialchars(trim($_POST['merk_mobil'])),
            'id_kategori' => $id_kat,
            'nama_kategori' => $nama_kat
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_data']) && isset($_SESSION['temp_konsultasi'])) {
    $_SESSION['active_konsultasi'] = $_SESSION['temp_konsultasi'];
    unset($_SESSION['temp_konsultasi']);

    // Simpan draft ke database
    $stmt = $conn->prepare("UPDATE user SET draft_merk_mobil = ?, draft_id_kategori = ? WHERE id_user = ?");
    $stmt->execute([
        $_SESSION['active_konsultasi']['merk_mobil'], 
        $_SESSION['active_konsultasi']['id_kategori'], 
        $_SESSION['user_auth']['id_user']
    ]);
}

if (!isset($_SESSION['temp_konsultasi']) && !isset($_SESSION['active_konsultasi'])) {
    header("Location: pilih_mobil.php");
    exit;
}

if (isset($_SESSION['active_konsultasi'])) {
    header("Location: konsultasi.php");
    exit;
}

$u = $_SESSION['user_auth'];
$merk = $_SESSION['temp_konsultasi']['merk_mobil'];
$kategori = $_SESSION['temp_konsultasi']['nama_kategori'];
include 'header.php'; 
?>

<div class="container" style="padding: 80px 20px; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="glass-card confirm-card" style="width: 100%; max-width: 550px; text-align: center;">
        <div class="confirm-icon"><i class="fas fa-clipboard-check"></i></div>
        <h2 style="margin-bottom: 8px; font-size: 24px;">Konfirmasi Data</h2>
        <p style="color: var(--text-muted); margin-bottom: 28px; font-size: 15px;">Pastikan data berikut sudah benar sebelum memulai diagnosa.</p>
        
        <div style="background: var(--bg-main); padding: 20px 24px; border-radius: var(--radius-sm); text-align: left; margin-bottom: 28px; border: 1px solid var(--border);">
            <div class="data-row">
                <div class="data-label">Nama Pemilik</div>
                <div class="data-value"><?= htmlspecialchars($u['nama_lengkap']) ?></div>
            </div>
            <div class="data-row">
                <div class="data-label">Kendaraan</div>
                <div class="data-value" style="color: var(--text-main);"><i class="fas fa-car" style="margin-right: 6px;"></i><?= htmlspecialchars($merk) ?></div>
            </div>
            <div class="data-row" style="border-bottom: none;">
                <div class="data-label">Teknologi Mesin</div>
                <div class="data-value" style="color: var(--primary);"><i class="fas fa-cogs" style="margin-right: 6px;"></i><?= htmlspecialchars($kategori) ?></div>
            </div>
        </div>

        <div style="background: var(--primary-light); padding: 20px; border-radius: var(--radius-sm); text-align: left; margin-bottom: 30px; border: 1px solid rgba(230,81,0,0.15);">
            <h3 style="font-size: 15px; color: var(--primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-info-circle"></i> Cara Sistem Bekerja
            </h3>
            <ul style="color: var(--text-secondary); font-size: 14px; line-height: 1.6; padding-left: 20px; margin: 0;">
                <li style="margin-bottom: 8px;">Di halaman berikutnya, <strong>pilih gejala</strong> yang Anda alami dan tentukan <strong>tingkat keyakinannya</strong>.</li>
                <li style="margin-bottom: 8px;">Sistem akan mengalikan tingkat keyakinan Anda dengan **nilai kepastian dari mekanik/pakar kami**.</li>
                <li>Metode <em>Certainty Factor</em> kemudian akan menggabungkan seluruh perhitungan tersebut untuk mencari <strong>kemungkinan kerusakan terbesar</strong> dalam bentuk persentase (%).</li>
            </ul>
        </div>

        <form action="konfirmasi.php" method="POST" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <input type="hidden" name="confirm_data" value="1">
            <a href="pilih_mobil.php" class="btn btn-secondary" style="padding: 12px 28px;"><i class="fas fa-pen"></i> Ubah</a>
            <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">Mulai Diagnosa <i class="fas fa-arrow-right"></i></button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
