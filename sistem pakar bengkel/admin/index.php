<?php
require_once '../config/db.php';
include 'header.php';

$t_user = $conn->query("SELECT COUNT(*) FROM user")->fetchColumn();
$t_admin = $conn->query("SELECT COUNT(*) FROM admin")->fetchColumn();
$t_konsultasi = $conn->query("SELECT COUNT(*) FROM konsultasi")->fetchColumn();
$t_kategori = $conn->query("SELECT COUNT(*) FROM kategori_mesin")->fetchColumn();
$t_rule = $conn->query("SELECT COUNT(*) FROM rule_cf")->fetchColumn();
$t_gejala = $conn->query("SELECT COUNT(*) FROM gejala")->fetchColumn();

// Ambil 5 konsultasi terakhir
$stmt = $conn->query("
    SELECT k.*, u.nama_lengkap, km.nama_kategori 
    FROM konsultasi k
    JOIN user u ON k.id_user = u.id_user
    JOIN kategori_mesin km ON k.id_kategori = km.id_kategori
    ORDER BY k.tanggal DESC LIMIT 5
");
$recent_konsultasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
    <div>
        <h2 style="margin: 0 0 5px 0;">Selamat Datang, Admin</h2>
        <p style="color: var(--text-muted); margin: 0;">Ringkasan statistik sistem pakar hari ini.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;"><i class="fas fa-users"></i></div>
        <div class="stat-info"><h4>Total Pengguna</h4><div class="num"><?= $t_user ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fas fa-user-shield"></i></div>
        <div class="stat-info"><h4>Total Admin</h4><div class="num"><?= $t_admin ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fas fa-history"></i></div>
        <div class="stat-info"><h4>Total Konsultasi</h4><div class="num"><?= $t_konsultasi ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="fas fa-cogs"></i></div>
        <div class="stat-info"><h4>Kategori Mesin</h4><div class="num"><?= $t_kategori ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(236, 72, 153, 0.1); color: #ec4899;"><i class="fas fa-project-diagram"></i></div>
        <div class="stat-info"><h4>Aturan Pakar (Rule)</h4><div class="num"><?= $t_rule ?></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fas fa-list-ul"></i></div>
        <div class="stat-info"><h4>Total Gejala</h4><div class="num"><?= $t_gejala ?></div></div>
    </div>
</div>

<div class="glass-card" style="padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <h3 style="margin-bottom: 20px; font-size: 16px;">Konsultasi Terbaru</h3>
    <table class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Tanggal</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Pengguna</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Kendaraan</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Hasil Diagnosa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recent_konsultasi as $r): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-size: 14px;"><?= date('d/m/Y H:i', strtotime($r['tanggal'])) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 500;"><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); color: var(--primary);"><?= htmlspecialchars($r['merk_mobil']) ?> <span style="color:var(--text-muted); font-size: 12px;">(<?= htmlspecialchars($r['nama_kategori']) ?>)</span></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 600;"><?= htmlspecialchars($r['hasil_diagnosa']) ?> (<?= $r['nilai_cf'] ?>%)</td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($recent_konsultasi)): ?>
            <tr><td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">Belum ada data konsultasi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
