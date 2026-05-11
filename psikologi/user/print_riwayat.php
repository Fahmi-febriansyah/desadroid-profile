<?php
session_start();
include '../koneksi.php';
include 'cek_login.php';

$id_user = $_SESSION['id_user'];

$query = "SELECT * FROM konsultasi WHERE id_user = $id_user ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

$user_q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user");
$user = mysqli_fetch_assoc($user_q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Riwayat Konsultasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; font-size: 13px; }
        .container { width: 100%; max-width: 1000px; margin: 0 auto; padding: 40px 20px; }
        .kop-surat { border-bottom: 3px solid #1e293b; display: flex; align-items: center; gap: 20px; padding-bottom: 20px; margin-bottom: 30px; }
        .kop-logo { width: 50px; height: 50px; background: #6366f1; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.2rem; }
        .kop-info h2 { font-size: 1.4rem; margin: 0; }
        .kop-info p { font-size: 0.85rem; color: #64748b; margin: 0; }
        .report-header { text-align: center; margin-bottom: 30px; }
        .report-header h3 { font-size: 1.2rem; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .report-header p { color: #64748b; margin: 5px 0 0; }
        
        .info-pasien { margin-bottom: 25px; }
        .info-pasien table { width: 100%; }
        .info-pasien td { padding: 4px 0; font-size: 0.9rem; }
        .info-pasien td:first-child { width: 150px; font-weight: 600; color: #64748b; }
        
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 8px; text-align: left; font-weight: 700; text-transform: uppercase; font-size: 11px; }
        .table td { border: 1px solid #e2e8f0; padding: 10px 8px; vertical-align: top; }
        .table tr:nth-child(even) { background: #fcfdfe; }
        
        .footer { display: flex; justify-content: flex-end; margin-top: 50px; }
        .ttd { text-align: center; width: 250px; }
        .ttd p { margin: 0; }
        
        @media print {
            .no-print { display: none; }
            @page { size: A4; margin: 1.5cm; }
            .container { padding: 0; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="background: #1e293b; color: #fff; padding: 15px; text-align: center;">
        <button onclick="window.print()" style="background:#6366f1; border:none; color:#fff; padding:8px 20px; border-radius:6px; cursor:pointer; font-weight:600;">Cetak Laporan</button>
        <button onclick="window.close()" style="background:#475569; border:none; color:#fff; padding:8px 20px; border-radius:6px; cursor:pointer; margin-left:10px;">Tutup</button>
    </div>

    <div class="container">
        <div class="kop-surat">
            <div class="kop-logo" style="background: transparent;">
                <img src="../logo.png" alt="Logo" style="width: 50px; height: auto;">
            </div>
            <div class="kop-info">
                <h2>PSIKOLOGI KITA</h2>
                <p>Layanan Konsultasi Psikologi Online | Jakarta, Indonesia</p>
            </div>
        </div>

        <div class="report-header">
            <h3>LAPORAN RIWAYAT DETEKSI KECEMASAN</h3>
            <p>Daftar riwayat konsultasi skrining kecemasan pengguna</p>
        </div>

        <div class="info-pasien">
            <table>
                <tr><td>Nama Pengguna</td><td>: <?php echo htmlspecialchars($user['nama']); ?></td></tr>
                <tr><td>Email</td><td>: <?php echo htmlspecialchars($user['email']); ?></td></tr>
                <tr><td>Usia</td><td>: <?php echo $user['umur']; ?> tahun</td></tr>
                <tr><td>Jenis Kelamin</td><td>: <?php echo ($user['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td></tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>ID Skrining</th>
                    <th>Tanggal</th>
                    <th>Total Skor</th>
                    <th>Kategori Diagnosis</th>
                    <th>Aspek Terindikasi Tinggi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                while ($row = mysqli_fetch_assoc($result)): 
                    // Ambil aspek terindikasi tinggi
                    $id_konsul = $row['id_konsultasi'];
                    $aspek_q = mysqli_query($koneksi, "SELECT a.nama_aspek FROM hasil_aspek ha JOIN aspek_hars a ON ha.id_aspek = a.id_aspek WHERE ha.id_konsultasi = $id_konsul ORDER BY ha.nilai_aspek DESC LIMIT 2");
                    $aspek_tinggi = [];
                    while($asp = mysqli_fetch_assoc($aspek_q)) {
                        $aspek_tinggi[] = $asp['nama_aspek'];
                    }
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>#<?php echo str_pad($row['id_konsultasi'], 5, '0', STR_PAD_LEFT); ?></td>
                    <td><?php echo format_indo($row['tanggal'], 'd F Y, H:i'); ?></td>
                    <td><strong><?php echo $row['total_skor']; ?></strong> / 56</td>
                    <td><strong><?php echo strtoupper($row['kategori']); ?></strong></td>
                    <td>
                        <?php if(count($aspek_tinggi) > 0): ?>
                            <ul style="margin: 0; padding-left: 15px;">
                                <?php foreach($aspek_tinggi as $nama): ?>
                                    <li><?php echo htmlspecialchars($nama); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="footer">
            <div class="ttd">
                <p>Jakarta, <?php echo format_indo(date('Y-m-d H:i:s'), 'd F Y'); ?></p>
                <p style="margin-bottom: 70px;">Administrator,</p>
                <p><strong>PSIKOLOGI KITA</strong></p>
                <p style="font-size: 11px; color: #64748b;">Laporan Riwayat Skrining</p>
            </div>
        </div>
    </div>

</body>
</html>
