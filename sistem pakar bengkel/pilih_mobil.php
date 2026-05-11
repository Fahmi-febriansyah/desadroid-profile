<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_auth'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT draft_merk_mobil, draft_id_kategori FROM user WHERE id_user = ?");
$stmt->execute([$_SESSION['user_auth']['id_user']]);
$user_draft = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($user_draft['draft_merk_mobil']) && !empty($user_draft['draft_id_kategori'])) {
    $stmt_kat = $conn->prepare("SELECT nama_kategori FROM kategori_mesin WHERE id_kategori = ?");
    $stmt_kat->execute([$user_draft['draft_id_kategori']]);
    $nama_kat = $stmt_kat->fetchColumn();

    $_SESSION['active_konsultasi'] = [
        'merk_mobil' => $user_draft['draft_merk_mobil'],
        'id_kategori' => $user_draft['draft_id_kategori'],
        'nama_kategori' => $nama_kat
    ];
    header("Location: konsultasi.php");
    exit;
}

// Ambil daftar kategori dari database
$stmt = $conn->query("SELECT * FROM kategori_mesin ORDER BY id_kategori ASC");
$kategori_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php'; 
?>

<div class="container" style="padding: 80px 20px; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="glass-card confirm-card" style="width: 100%; max-width: 600px;">
        <div style="text-align: center; margin-bottom: 28px;">
            <div class="confirm-icon"><i class="fas fa-car-battery"></i></div>
            <h2 style="margin-bottom: 8px; font-size: 24px;">Mulai Konsultasi</h2>
            <p style="color: var(--text-muted); font-size: 15px; line-height: 1.6;">Aturan diagnosa pakar kami dirancang khusus berdasarkan teknologi mesin mobil Anda.</p>
        </div>
        
        <form action="konfirmasi.php" method="POST">
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label"><i class="fas fa-car-side"></i> Merk & Tipe Mobil</label>
                <input type="text" name="merk_mobil" class="form-control" required placeholder="Contoh: Toyota Avanza 2018">
            </div>
            
            <div class="form-group" style="margin-bottom: 30px;">
                <label class="form-label"><i class="fas fa-cogs"></i> Kategori Teknologi Mesin</label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Teknologi Mesin --</option>
                    <?php foreach($kategori_list as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 8px;"><i class="fas fa-info-circle"></i> Basis pengetahuan sistem pakar akan menyesuaikan kategori yang dipilih.</p>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Lanjutkan <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
