<?php
session_start();
include '../koneksi.php';

if (!isset($_GET['id'])) {
    exit('ID tidak valid');
}

$id_konsultasi = intval($_GET['id']);
$query = "SELECT k.*, u.nama, u.email, u.umur, u.jenis_kelamin 
          FROM konsultasi k 
          JOIN users u ON k.id_user = u.id_user 
          WHERE k.id_konsultasi = $id_konsultasi";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    exit('Data tidak ditemukan');
}

$total_skor = $data['total_skor'];
$kategori = $data['kategori'];
$tanggal = $data['tanggal'];

$catatan = 'Tidak ada catatan';
$rekom_q = mysqli_query($koneksi, "SELECT * FROM rekomendasi WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kategori) . "' LIMIT 1");
if ($rek = mysqli_fetch_assoc($rekom_q)) {
    $catatan = $rek['isi'];
}

$badge_color = '#10b981'; 
if (strpos($kategori, 'ringan') !== false) $badge_color = '#f59e0b';
elseif (strpos($kategori, 'sedang') !== false) $badge_color = '#f97316';
elseif (strpos($kategori, 'berat') !== false) $badge_color = '#ef4444';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - <?php echo $data['nama']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; background: #fff; font-size: 14px; }
        .surat-medis { width: 100%; max-width: 800px; margin: 0 auto; background: #fff; }
        .kop-surat { padding: 20px 0; border-bottom: 3px solid #1e293b; display: flex; align-items: center; gap: 20px; }
        .kop-logo { width: 45px; height: 45px; border-radius: 10px; background: #6366f1; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.1rem; flex-shrink: 0; }
        .kop-info h2 { font-size: 1.2rem; color: #1e293b; margin: 0; }
        .kop-info p { font-size: 0.75rem; color: #64748b; margin: 0; }
        .surat-body { padding: 25px 0; }
        .surat-judul { text-align: center; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px; }
        .surat-judul h3 { font-size: 1rem; margin: 0; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
        .info-pasien { margin-bottom: 25px; }
        .info-pasien table { width: 100%; border-collapse: collapse; }
        .info-pasien td { padding: 5px 0; font-size: 14px; }
        .info-pasien td:first-child { width: 150px; font-weight: 600; color: #64748b; }
        .diagnosis-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 25px; text-align: center; }
        .diagnosis-kategori { font-size: 1.4rem; font-weight: 800; margin-bottom: 4px; }
        .section-title { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 15px 0 8px; padding-bottom: 4px; border-bottom: 2px solid #6366f1; display: inline-block; }
        .catatan-box { background: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 12px 18px; border-radius: 0 8px 8px 0; margin-bottom: 30px; }
        .catatan-box h4 { font-size: 0.9rem; margin: 0 0 6px; color: #0369a1; }
        .ttd-section { display: flex; justify-content: flex-end; margin-top: 40px; }
        .ttd-box { text-align: center; width: 220px; }
        .ttd-box p { margin: 0; font-size: 14px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; font-size: 14px; }
            @page { size: A4; margin: 1.5cm; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="no-print" style="background: #1e293b; color: #fff; padding: 15px; text-align: center; font-weight: 600;">
        Tampilan Cetak Laporan (A4) | <button onclick="window.print()" style="background:#6366f1; border:none; color:#fff; padding:5px 15px; border-radius:5px; cursor:pointer;">Cetak Sekarang</button>
    </div>

    <div class="surat-medis">
        <div class="kop-surat">
            <div class="kop-logo" style="background: transparent;">
                <img src="../logo.png" alt="Logo" style="width: 50px; height: auto;">
            </div>
            <div class="kop-info">
                <h2>PSIKOLOGI KITA</h2>
                <p>Layanan Konsultasi Psikologi Online | Jakarta, Indonesia</p>
            </div>
        </div>

        <div class="surat-body">
            <div class="surat-judul">
                <h3>Hasil Skrining Kecemasan (HARS)</h3>
                <p>No. Konsultasi: #<?php echo str_pad($id_konsultasi, 5, '0', STR_PAD_LEFT); ?></p>
            </div>

            <div class="info-pasien">
                <table>
                    <tr><td>Nama Pasien</td><td>: <?php echo htmlspecialchars($data['nama']); ?></td></tr>
                    <tr><td>Email</td><td>: <?php echo htmlspecialchars($data['email']); ?></td></tr>
                    <tr><td>Usia</td><td>: <?php echo $data['umur']; ?> tahun</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: <?php echo ($data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td></tr>
                    <tr><td>Tanggal Periksa</td><td>: <?php echo format_indo($tanggal, 'd F Y, H:i'); ?></td></tr>
                </table>
            </div>

            <div class="diagnosis-box" style="-webkit-print-color-adjust: exact; background: #f8fafc !important;">
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 8px;">Kesimpulan Diagnosis</p>
                <p class="diagnosis-kategori" style="color: <?php echo $badge_color; ?> !important;"><?php echo strtoupper($kategori); ?></p>
                <p style="font-size: 1rem; color: #1e293b;">Total Skor: <strong><?php echo $total_skor; ?></strong> / 56</p>
            </div>

            <?php
            $aspek_q = mysqli_query($koneksi, "SELECT a.nama_aspek, ha.nilai_aspek FROM hasil_aspek ha JOIN aspek_hars a ON ha.id_aspek = a.id_aspek WHERE ha.id_konsultasi = $id_konsultasi ORDER BY ha.nilai_aspek DESC LIMIT 2");
            if (mysqli_num_rows($aspek_q) > 0):
            ?>
            <p class="section-title" style="-webkit-print-color-adjust: exact; border-bottom: 2px solid #6366f1 !important;">Aspek Terindikasi Tinggi</p>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 14px; border: 1px solid #e2e8f0;">
                <thead>
                    <tr style="background: #f8fafc !important; -webkit-print-color-adjust: exact; text-align: left;">
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">No</th>
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Aspek / Gejala</th>
                        <th style="padding: 10px; border-bottom: 1px solid #e2e8f0;">Skor Keparahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($asp = mysqli_fetch_assoc($aspek_q)): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #f1f5f9;"><?php echo $no++; ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #f1f5f9;"><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 6px;"></i> <?php echo htmlspecialchars($asp['nama_aspek']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #f1f5f9; color: #ef4444; font-weight: bold;"><?php echo $asp['nilai_aspek']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <p class="section-title" style="-webkit-print-color-adjust: exact; border-bottom: 2px solid #6366f1 !important;">Rekomendasi Psikolog</p>
            <div class="catatan-box" style="-webkit-print-color-adjust: exact; background: #f0f9ff !important; border-left: 4px solid #0ea5e9 !important;">
                <h4><i class="fas fa-info-circle"></i> Saran Tindak Lanjut:</h4>
                <p><?php echo htmlspecialchars($catatan); ?></p>
            </div>

            <div class="ttd-section">
                <div class="ttd-box">
                    <p style="color: #64748b; margin-bottom: 60px;">Jakarta, <?php echo format_indo($tanggal, 'd F Y'); ?></p>
                    <p style="font-weight: 700; border-top: 1px solid #1e293b; padding-top: 4px;">PSIKOLOGI KITA</p>
                    <p style="font-size: 0.8rem; color: #64748b;">Layanan Konsultasi Psikologi</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
