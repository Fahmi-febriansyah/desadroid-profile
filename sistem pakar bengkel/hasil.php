<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_auth'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['id_konsultasi']) && !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_konsultasi = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['id_konsultasi'];
$id_user_login = $_SESSION['user_auth']['id_user'];

$stmt = $conn->prepare("
    SELECT k.*, u.nama_lengkap 
    FROM konsultasi k
    JOIN user u ON k.id_user = u.id_user
    WHERE k.id_konsultasi = ?
");
$stmt->execute([$id_konsultasi]);
$konsultasi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$konsultasi) {
    echo "<div style='text-align:center; padding: 50px; font-family:sans-serif;'><h2>Akses Ditolak</h2><p>Data konsultasi ini tidak ditemukan atau bukan milik Anda.</p><a href='riwayat.php'>Kembali ke Riwayat</a></div>";
    exit;
}

// Karena id_kategori bisa jadi tidak ada di hasil lama jika baru ditambahkan,
// kita pakai cara aman untuk fetch kategori.
$kategori_nama = "Umum";
if (isset($konsultasi['id_kategori'])) {
    $stmt_k = $conn->prepare("SELECT nama_kategori FROM kategori_mesin WHERE id_kategori = ?");
    $stmt_k->execute([$konsultasi['id_kategori']]);
    $kat = $stmt_k->fetchColumn();
    if ($kat) $kategori_nama = $kat;
}

$stmt = $conn->prepare("SELECT * FROM detail_konsultasi WHERE id_konsultasi = ?");
$stmt->execute([$id_konsultasi]);
$detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT solusi FROM kerusakan WHERE nama_kerusakan = ?");
$stmt->execute([$konsultasi['hasil_diagnosa']]);
$kerusakan = $stmt->fetch(PDO::FETCH_ASSOC);
$solusi = $kerusakan ? $kerusakan['solusi'] : 'Silakan bawa kendaraan Anda ke bengkel untuk pemeriksaan fisik lebih lanjut.';

include 'header.php';
?>

<div class="container" style="padding: 50px 20px 80px;">
    <div class="page-header print-hide">
        <h1>Laporan Hasil Diagnosa</h1>
        <p>Gunakan laporan ini sebagai referensi saat memperbaiki kendaraan Anda.</p>
    </div>

    <div style="max-width: 850px; margin: 0 auto;">
        <!-- Area Dokumen Cetak -->
        <div class="result-card document-print" style="background: #fff; padding: 0; position: relative;">
            
            <!-- Kop Surat Dokumen -->
            <div style="padding: 40px 50px; border-bottom: 3px double #333; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="assets/logo.png" alt="Logo" style="height: 50px; filter: grayscale(100%);">
                    <div>
                        <h2 style="font-size: 24px; font-weight: 800; color: #111; margin-bottom: 4px; letter-spacing: 1px;">DPM GARAGE</h2>
                        <div style="font-size: 13px; color: #555;">Sistem Pakar Diagnosa Kerusakan Mobil</div>
                    </div>
                </div>
                <div style="text-align: right; font-size: 12px; color: #444; line-height: 1.5;">
                    Jl. Sapi Perah, Cipayung, Jakarta Timur<br>
                    Telp: (021) 1234-5678 / 0812-3456-7890<br>
                    <strong>ID Konsultasi: #<?= str_pad($konsultasi['id_konsultasi'], 5, '0', STR_PAD_LEFT) ?></strong>
                </div>
            </div>
            
            <div style="padding: 30px 50px;">
                <h3 style="text-align: center; font-size: 18px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; text-decoration: underline;">Rekam Medis Kendaraan</h3>

                <!-- Data Identitas -->
                <table style="width: 100%; margin-bottom: 25px; font-size: 13px;">
                    <tr>
                        <td style="width: 130px; padding: 4px 0; font-weight: 600; color: #444;">Nama Pemilik</td>
                        <td style="width: 15px; padding: 4px 0;">:</td>
                        <td style="padding: 4px 0; font-weight: 600;"><?= htmlspecialchars($konsultasi['nama_lengkap']) ?></td>
                        
                        <td style="width: 130px; padding: 4px 0; font-weight: 600; color: #444;">Tanggal</td>
                        <td style="width: 15px; padding: 4px 0;">:</td>
                        <td style="padding: 4px 0;"><?= date('d M Y, H:i', strtotime($konsultasi['tanggal'])) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; font-weight: 600; color: #444;">Merk & Tipe</td>
                        <td style="padding: 4px 0;">:</td>
                        <td style="padding: 4px 0;"><?= htmlspecialchars($konsultasi['merk_mobil']) ?></td>
                        
                        <td style="padding: 4px 0; font-weight: 600; color: #444;">Kategori Mesin</td>
                        <td style="padding: 4px 0;">:</td>
                        <td style="padding: 4px 0;"><?= htmlspecialchars($kategori_nama) ?></td>
                    </tr>
                </table>

                <!-- Gejala Table -->
                <h4 style="font-size: 14px; margin-bottom: 10px; color: #222; border-bottom: 1px solid #ccc; padding-bottom: 6px;">1. Gejala yang Dialami</h4>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 40px; background-color: #f0f0f0; color: #000; font-weight: bold;">No</th>
                            <th style="border: 1px solid #000; padding: 8px; text-align: left; background-color: #f0f0f0; color: #000; font-weight: bold;">Kode</th>
                            <th style="border: 1px solid #000; padding: 8px; text-align: left; background-color: #f0f0f0; color: #000; font-weight: bold;">Deskripsi Gejala</th>
                            <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 120px; background-color: #f0f0f0; color: #000; font-weight: bold;">Tingkat Keyakinan (CF)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($detail as $d): ?>
                        <tr>
                            <td style="border: 1px solid #000; padding: 8px; text-align: center; color: #000;"><?= $no++ ?></td>
                            <td style="border: 1px solid #000; padding: 8px; color: #000;">[<?= $d['kode_gejala'] ?>]</td>
                            <td style="border: 1px solid #000; padding: 8px; color: #000;"><?= htmlspecialchars($d['nama_gejala']) ?></td>
                            <td style="border: 1px solid #000; padding: 8px; text-align: center; color: #000;"><?= $d['cf_user'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Hasil Diagnosa Box -->
                <h4 style="font-size: 14px; margin-bottom: 10px; color: #222; border-bottom: 1px solid #ccc; padding-bottom: 6px;">2. Hasil Kesimpulan (Certainty Factor)</h4>
                <?php if ($konsultasi['nilai_cf'] < 10): ?>
                    <div style="border: 2px solid #28a745; padding: 15px; margin-bottom: 25px; text-align: center; background-color: #fdfdfd;">
                        <div style="font-size: 18px; font-weight: 800; color: #28a745; text-transform: uppercase; margin-bottom: 6px;">
                            KONDISI MESIN PRIMA (TIDAK ADA KERUSAKAN SIGNIFIKAN)
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: #000;">
                            Tingkat Kepastian (CF): <span style="background: #e0e0e0; color: #000; padding: 3px 8px; border: 1px solid #000;"><?= $konsultasi['nilai_cf'] ?>%</span>
                        </div>
                    </div>
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: #222; border-bottom: 1px solid #ccc; padding-bottom: 6px;">3. Solusi & Rekomendasi Tindakan</h4>
                    <div style="border: 1px solid #000; padding: 12px; font-size: 13px; line-height: 1.5; color: #000; margin-bottom: 40px; background: #fff;">
                        Berdasarkan gejala yang Anda pilih, sistem kami tidak mendeteksi adanya indikasi kerusakan yang membutuhkan penanganan pakar (Tingkat CF sangat rendah). Kendaraan Anda dinilai masih dalam kondisi yang wajar dan aman digunakan. Tetap lakukan servis rutin berkala.
                    </div>
                <?php else: ?>
                    <div style="border: 2px solid #000; padding: 15px; margin-bottom: 25px; text-align: center; background-color: #fdfdfd;">
                        <div style="font-size: 13px; color: #333; margin-bottom: 6px;">Berdasarkan analisa sistem pakar, kendaraan mengalami kerusakan pada:</div>
                        <div style="font-size: 20px; font-weight: 800; color: #000; text-transform: uppercase; margin-bottom: 6px;">
                            <?= htmlspecialchars($konsultasi['hasil_diagnosa']) ?>
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: #000;">
                            Tingkat Kepastian: <span style="background: #e0e0e0; color: #000; padding: 3px 8px; border: 1px solid #000;"><?= $konsultasi['nilai_cf'] ?>%</span>
                        </div>
                    </div>

                    <!-- Solusi Table -->
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: #222; border-bottom: 1px solid #ccc; padding-bottom: 6px;">3. Solusi & Rekomendasi Tindakan</h4>
                    <div style="border: 1px solid #000; padding: 12px; font-size: 13px; line-height: 1.5; color: #000; margin-bottom: 40px; background: #fff;">
                        <?= nl2br(htmlspecialchars($solusi)) ?>
                    </div>
                <?php endif; ?>

                <!-- Tanda Tangan -->
                <table style="width: 100%; text-align: center; font-size: 13px; page-break-inside: avoid;">
                    <tr>
                        <td style="width: 50%; padding-bottom: 60px; color: #000;">
                            Pemilik Kendaraan,
                        </td>
                        <td style="width: 50%; padding-bottom: 60px; color: #000;">
                            Jakarta, <?= date('d F Y') ?><br>
                            Mekanik / Pakar DPM,
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; text-decoration: underline; color: #000;">
                            <?= htmlspecialchars($konsultasi['nama_lengkap']) ?>
                        </td>
                        <td style="font-weight: 600; text-decoration: underline; color: #000;">
                            ( ........................................... )
                        </td>
                    </tr>
                </table>

            </div>
        </div>

        <!-- Actions -->
        <div class="print-hide" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-top: 32px;">
            <a href="pilih_mobil.php" class="btn btn-secondary" style="padding: 14px 32px;"><i class="fas fa-redo"></i> Konsultasi Baru</a>
            <button onclick="window.print()" class="btn btn-primary" style="padding: 14px 32px; box-shadow: 0 4px 15px rgba(230,81,0,0.3);"><i class="fas fa-print"></i> Cetak Dokumen Resmi</button>
        </div>
    </div>
</div>

<style>
    .document-print {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        color: #111;
        background: #fff;
    }
    @media print {
        @page { size: A4 portrait; margin: 15mm; }
        body { background: white !important; margin: 0; padding: 0; font-family: 'Times New Roman', Times, serif; color: #000 !important; }
        .print-hide, header, nav, footer, .cookie-banner { display: none !important; }
        .container { padding: 0 !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; }
        
        .document-print { 
            box-shadow: none !important; 
            border: none !important; 
            width: 100% !important; 
            margin: 0 !important; 
            padding: 0 !important; 
            background: transparent !important;
        }
        
        table th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
        
        /* Pastikan background box hasil tetap terprint jika browser support */
        div[style*="background-color: #fdfdfd;"] { background-color: #fdfdfd !important; -webkit-print-color-adjust: exact; }
        span[style*="background: #e0e0e0;"] { background: #e0e0e0 !important; -webkit-print-color-adjust: exact; }
    }
</style>

<?php include 'footer.php'; ?>
