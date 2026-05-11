<?php
session_start();
require_once 'config/db.php';
include 'header.php';

// Jika yang login bukan admin (saat ini sistem tidak ada admin), maka batasi riwayat hanya miliknya saja?
// User mintanya "di riwayat saya bisa print seluruh riwayat". Anggap saja semua riwayat (atau miliknya saja? 
// Biasanya riwayat user hanya milik user tersebut. Mari kita batasi milik user tersebut).
if (!isset($_SESSION['user_auth'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_auth']['id_user'];

$stmt = $conn->prepare("
    SELECT k.*, u.nama_lengkap, km.nama_kategori 
    FROM konsultasi k
    JOIN user u ON k.id_user = u.id_user
    JOIN kategori_mesin km ON k.id_kategori = km.id_kategori
    WHERE k.id_user = ?
    ORDER BY k.tanggal DESC 
");
$stmt->execute([$id_user]);
$riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container" style="padding: 50px 20px 80px; min-height: 80vh;">
    <div class="page-header print-hide" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="text-align: left; margin-bottom: 8px;">Riwayat Konsultasi Saya</h1>
            <p style="text-align: left; margin: 0;">Daftar seluruh konsultasi yang pernah Anda lakukan.</p>
        </div>
        <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 20px;"><i class="fas fa-print"></i> Cetak Semua Riwayat</button>
    </div>

    <!-- Area Cetak Khusus Print -->
    <div class="print-only" style="display: none;">
        <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px;">
            <h2 style="margin: 0; font-size: 24px; text-transform: uppercase;">DPM Garage</h2>
            <p style="margin: 5px 0 0; font-size: 14px;">Laporan Seluruh Riwayat Konsultasi Kendaraan</p>
        </div>
        <div style="margin-bottom: 20px; font-size: 14px;">
            <strong>Nama Pemilik:</strong> <?= htmlspecialchars($_SESSION['user_auth']['nama_lengkap']) ?><br>
            <strong>Tanggal Cetak:</strong> <?= date('d F Y') ?>
        </div>
    </div>

    <div class="glass-card document-print" style="padding: 0; overflow-x: auto; background: #fff;">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--surface);">
                    <th style="padding: 15px; border-bottom: 1px solid var(--border); text-align: left;">No</th>
                    <th style="padding: 15px; border-bottom: 1px solid var(--border); text-align: left;">Tanggal</th>
                    <th style="padding: 15px; border-bottom: 1px solid var(--border); text-align: left;">Kendaraan</th>
                    <th style="padding: 15px; border-bottom: 1px solid var(--border); text-align: left;">Teknologi Mesin</th>
                    <th style="padding: 15px; border-bottom: 1px solid var(--border); text-align: left;">Hasil Diagnosa</th>
                    <th style="padding: 15px; border-bottom: 1px solid var(--border); text-align: center;">CF</th>
                    <th class="print-hide" style="padding: 15px; border-bottom: 1px solid var(--border); text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($riwayat)): ?>
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: var(--text-muted);">
                        <i class="fas fa-inbox" style="font-size: 40px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
                        Belum ada riwayat konsultasi.
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($riwayat as $r): ?>
                    <tr>
                        <td style="padding: 15px; border-bottom: 1px solid var(--border);"><?= $no++ ?></td>
                        <td style="padding: 15px; border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 14px;"><?= date('d M Y H:i', strtotime($r['tanggal'])) ?></td>
                        <td style="padding: 15px; border-bottom: 1px solid var(--border); color: var(--primary); font-weight: 500;"><?= htmlspecialchars($r['merk_mobil']) ?></td>
                        <td style="padding: 15px; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 14px;"><?= htmlspecialchars($r['nama_kategori']) ?></td>
                        <td style="padding: 15px; border-bottom: 1px solid var(--border); font-weight: 600;"><?= htmlspecialchars($r['hasil_diagnosa']) ?></td>
                        <td style="padding: 15px; border-bottom: 1px solid var(--border); text-align: center;"><span class="badge badge-primary"><?= $r['nilai_cf'] ?>%</span></td>
                        <td class="print-hide" style="padding: 15px; border-bottom: 1px solid var(--border); text-align: center;">
                            <a href="hasil.php?id=<?= $r['id_konsultasi'] ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;"><i class="fas fa-eye"></i> Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        @page { size: A4 portrait; margin: 20mm; }
        body { background: white !important; margin: 0; padding: 0; font-family: 'Times New Roman', Times, serif; color: #000 !important; }
        .print-hide, header, nav, footer, .cookie-banner { display: none !important; }
        .print-only { display: block !important; }
        .container { padding: 0 !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; }
        
        .document-print { 
            box-shadow: none !important; 
            border: none !important; 
            margin: 0 !important; 
            padding: 0 !important;
            background: transparent !important;
        }
        
        table { border-collapse: collapse !important; width: 100% !important; }
        table th, table td { 
            border: 1px solid #000 !important; 
            color: #000 !important; 
            padding: 8px 10px !important;
            font-size: 12px !important;
        }
        table th { 
            background-color: #e0e0e0 !important; 
            -webkit-print-color-adjust: exact; 
            color-adjust: exact;
            font-weight: bold !important;
            text-align: center !important;
        }
        
        /* Hilangkan styling warna-warni pada teks untuk mode print */
        td[style*="color: var(--primary)"], 
        td[style*="color: var(--text-muted)"], 
        td[style*="color: var(--text-secondary)"] {
            color: #000 !important;
        }
        
        .badge { 
            background: none !important; 
            color: #000 !important; 
            padding: 0 !important; 
            border: none !important; 
            font-weight: bold !important; 
            font-size: 12px !important;
        }
    }
</style>

<?php include 'footer.php'; ?>
