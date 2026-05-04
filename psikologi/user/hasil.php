<?php
session_start();
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id_konsultasi = intval($_GET['id']);
    
    $extra_where = "";
    if (isset($_SESSION['id_user'])) {
        $extra_where = " AND id_user = " . intval($_SESSION['id_user']);
    }
    
    $konsul_q = mysqli_query($koneksi, "SELECT * FROM konsultasi WHERE id_konsultasi = $id_konsultasi $extra_where");
    if (mysqli_num_rows($konsul_q) == 0) {
        header("Location: riwayat.php");
        exit();
    }
    $konsul = mysqli_fetch_assoc($konsul_q);
    
    $total_skor = $konsul['total_skor'];
    $kategori = $konsul['kategori'];
    $tanggal = $konsul['tanggal'];
    
    $nilai_per_aspek = array();
    $aspek_q = mysqli_query($koneksi, "SELECT ha.*, a.kode_aspek, a.nama_aspek 
                                       FROM hasil_aspek ha 
                                       JOIN aspek_hars a ON ha.id_aspek = a.id_aspek 
                                       WHERE ha.id_konsultasi = $id_konsultasi");
    while ($asp = mysqli_fetch_assoc($aspek_q)) {
        $nilai_per_aspek[$asp['kode_aspek']] = array(
            'id_aspek' => $asp['id_aspek'],
            'kode_aspek' => $asp['kode_aspek'],
            'nama_aspek' => $asp['nama_aspek'],
            'nilai' => $asp['nilai_aspek']
        );
    }
    
    $catatan = 'Tidak ada catatan';
    $rekom_q = mysqli_query($koneksi, "SELECT * FROM rekomendasi WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kategori) . "' LIMIT 1");
    if ($rek = mysqli_fetch_assoc($rekom_q)) {
        $catatan = $rek['isi'];
    }
    
    $rule_terpicu = array();
    $rule_q = mysqli_query($koneksi, "SELECT * FROM rule ORDER BY id_rule ASC");
    while ($rule = mysqli_fetch_assoc($rule_q)) {
        $kondisi = $rule['kondisi'];
        $parts = explode(' ', trim($kondisi));
        if (count($parts) >= 3) {
            $kode = $parts[0];
            $operator = $parts[1];
            $threshold = floatval($parts[2]);
            
            if (isset($nilai_per_aspek[$kode])) {
                $nilai_aspek = $nilai_per_aspek[$kode]['nilai'];
                $terpicu = false;
                switch ($operator) {
                    case '>': $terpicu = ($nilai_aspek > $threshold); break;
                    case '>=': $terpicu = ($nilai_aspek >= $threshold); break;
                    case '<': $terpicu = ($nilai_aspek < $threshold); break;
                    case '<=': $terpicu = ($nilai_aspek <= $threshold); break;
                    case '==': $terpicu = ($nilai_aspek == $threshold); break;
                }
                $rule_terpicu[] = array(
                    'kode_rule' => $rule['kode_rule'],
                    'kondisi' => $kondisi,
                    'hasil' => $rule['hasil'],
                    'nilai_aktual' => $nilai_aspek,
                    'terpicu' => $terpicu
                );
            }
        }
    }
} elseif (isset($_SESSION['hasil_konsultasi'])) {
    $hasil = $_SESSION['hasil_konsultasi'];
    $id_konsultasi = $hasil['id_konsultasi'];
    $total_skor = $hasil['total_skor'];
    $kategori = $hasil['kategori'];
    $catatan = $hasil['rekomendasi'] ?? $hasil['catatan'] ?? 'Tidak ada catatan';
    $nilai_per_aspek = $hasil['nilai_per_aspek'];
    $rule_terpicu = $hasil['rule_terpicu'];
    $tanggal = $hasil['tanggal'];
} else {
    header("Location: kuesioner.php");
    exit();
}

$nama_user = 'Pasien / Pengguna';
$umur_user = '-';
$jk_user = '-';
if (isset($_SESSION['id_user'])) {
    $user_q = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = " . intval($_SESSION['id_user']));
    if ($u = mysqli_fetch_assoc($user_q)) {
        $nama_user = $u['nama'];
        $umur_user = $u['umur'] . ' tahun';
        $jk_user = ($u['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan';
    }
}

$badge_color = '#10b981'; 
$badge_bg = 'rgba(16,185,129,0.1)';
if (strpos($kategori, 'ringan') !== false) {
    $badge_color = '#f59e0b';
    $badge_bg = 'rgba(245,158,11,0.1)';
} elseif (strpos($kategori, 'sedang') !== false) {
    $badge_color = '#f97316';
    $badge_bg = 'rgba(249,115,22,0.1)';
} elseif (strpos($kategori, 'berat') !== false && strpos($kategori, 'sekali') === false) {
    $badge_color = '#ef4444';
    $badge_bg = 'rgba(239,68,68,0.1)';
} elseif (strpos($kategori, 'berat sekali') !== false) {
    $badge_color = '#dc2626';
    $badge_bg = 'rgba(220,38,38,0.1)';
}

$skor_max = 56; 

$page_title = 'Hasil Konsultasi HARS - Psikologi Kita';
$extra_css = '
<style>
    .hasil-wrapper {
        padding: 100px 24px 60px;
        background: #f1f5f9;
        min-height: 100vh;
    }
    .surat-medis {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #d1d5db;
        position: relative;
    }

    .kop-surat {
        padding: 30px 40px 20px;
        border-bottom: 3px solid #1e293b;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .kop-logo {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.8rem;
        flex-shrink: 0;
    }
    .kop-info {
        flex: 1;
    }
    .kop-info h2 {
        font-size: 1.5rem;
        color: #1e293b;
        margin: 0 0 2px;
        letter-spacing: 1px;
    }
    .kop-info p {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    .kop-info .sub-line {
        font-size: 0.8rem;
        margin-top: 2px;
    }

    .surat-body {
        padding: 30px 40px;
    }
    .surat-judul {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    .surat-judul h3 {
        font-size: 1.2rem;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0 0 5px;
    }
    .surat-judul p {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    .info-pasien {
        margin-bottom: 25px;
    }
    .info-pasien table {
        width: 100%;
    }
    .info-pasien td {
        padding: 4px 0;
        font-size: 0.95rem;
        vertical-align: top;
    }
    .info-pasien td:first-child {
        width: 170px;
        font-weight: 600;
        color: #1e293b;
    }
    .info-pasien td:nth-child(2) {
        width: 15px;
        text-align: center;
    }
    .info-pasien td:last-child {
        color: #475569;
    }

    .diagnosis-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 25px;
        text-align: center;
    }
    .diagnosis-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 8px;
    }
    .diagnosis-kategori {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .diagnosis-skor {
        font-size: 0.95rem;
        color: #64748b;
    }

    .tabel-aspek {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
        font-size: 0.9rem;
    }
    .tabel-aspek thead th {
        background: #1e293b;
        color: #fff;
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
    }
    .tabel-aspek tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
    }
    .tabel-aspek tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    .nilai-bar {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bar-track {
        flex: 1;
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #6366f1;
        display: inline-block;
    }
    .rule-list {
        margin-bottom: 25px;
    }
    .rule-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    .rule-badge {
        padding: 3px 10px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }
    .rule-badge.aktif {
        background: #dcfce7;
        color: #16a34a;
    }
    .rule-badge.tidak {
        background: #fef2f2;
        color: #dc2626;
    }
    .rule-kondisi {
        color: #475569;
        flex: 1;
    }
    .rule-hasil {
        font-weight: 600;
        color: #1e293b;
    }

    .catatan-box {
        background: #eef2ff;
        border-left: 4px solid #6366f1;
        padding: 18px 20px;
        border-radius: 0 8px 8px 0;
        margin-bottom: 25px;
    }
    .catatan-box h4 {
        font-size: 0.95rem;
        color: #4338ca;
        margin: 0 0 6px;
    }
    .catatan-box p {
        font-size: 0.95rem;
        color: #475569;
        margin: 0;
        line-height: 1.6;
    }

    .ttd-section {
        display: flex;
        justify-content: flex-end;
        margin-top: 40px;
        padding-top: 20px;
    }
    .ttd-box {
        text-align: center;
        width: 220px;
    }
    .ttd-box .tanggal {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 60px;
    }
    .ttd-box .nama-ttd {
        font-weight: 700;
        color: #1e293b;
        border-top: 1px solid #1e293b;
        padding-top: 6px;
        font-size: 0.9rem;
    }
    .ttd-box .jabatan-ttd {
        font-size: 0.8rem;
        color: #64748b;
    }

    .catatan-kaki {
        padding: 15px 40px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        font-size: 0.78rem;
        color: #94a3b8;
        line-height: 1.5;
    }

    .aksi-wrapper {
        max-width: 800px;
        margin: 24px auto 0;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-aksi {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all 0.2s;
        font-family: inherit;
    }
    .btn-print {
        background: #1e293b;
        color: #fff;
    }
    .btn-print:hover {
        background: #0f172a;
    }
    .btn-testimoni {
        background: #6366f1;
        color: #fff;
    }
    .btn-testimoni:hover {
        background: #4f46e5;
    }
    .btn-ulang {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .btn-ulang:hover {
        background: #e2e8f0;
    }
    .btn-beranda {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    .btn-beranda:hover {
        background: #e2e8f0;
    }

    @media print {
        .navbar, .footer, .aksi-wrapper {
            display: none !important;
        }
        .hasil-wrapper {
            padding: 0 !important;
            background: #fff !important;
        }
        .surat-medis {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background: #fff !important;
        }
    }

    @media (max-width: 600px) {
        .kop-surat {
            padding: 20px;
            flex-direction: column;
            text-align: center;
        }
        .surat-body {
            padding: 20px;
        }
        .catatan-kaki {
            padding: 12px 20px;
        }
        .aksi-wrapper {
            flex-direction: column;
        }
        .btn-aksi {
            justify-content: center;
        }
    }
</style>
';
include 'header.php';
?>

    <div class="hasil-wrapper">
        <div class="surat-medis" id="suratMedis">
            <div class="kop-surat">
                <div class="kop-logo">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="kop-info">
                    <h2>PSIKOLOGI KITA</h2>
                    <p>Layanan Konsultasi Psikologi Online Terpercaya</p>
                    <p class="sub-line">Jl. Ahmad Yani No. 123, Yogyakarta 55123 | Telp: (0274) 555-1234</p>
                </div>
            </div>

            <div class="surat-body">
                <div class="surat-judul">
                    <h3>Laporan Hasil Skrining Kecemasan</h3>
                    <p>Hamilton Anxiety Rating Scale (HARS)</p>
                </div>

                <div class="info-pasien">
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?php echo htmlspecialchars($nama_user); ?></td>
                        </tr>
                        <tr>
                            <td>Usia</td>
                            <td>:</td>
                            <td><?php echo $umur_user; ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td><?php echo $jk_user; ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Pemeriksaan</td>
                            <td>:</td>
                            <td><?php echo date('d F Y, H:i', strtotime($tanggal)); ?> WIB</td>
                        </tr>
                        <tr>
                            <td>No. Konsultasi</td>
                            <td>:</td>
                            <td>#<?php echo str_pad($id_konsultasi, 5, '0', STR_PAD_LEFT); ?></td>
                        </tr>
                    </table>
                </div>

                <div class="diagnosis-box">
                    <p class="diagnosis-label">Hasil Diagnosis Tingkat Kecemasan</p>
                    <p class="diagnosis-kategori" style="color: <?php echo $badge_color; ?>;">
                        <?php echo strtoupper($kategori); ?>
                    </p>
                    <p class="diagnosis-skor">Total Skor: <strong><?php echo $total_skor; ?></strong> / <?php echo $skor_max; ?></p>
                </div>

                <p class="section-title">Detail Nilai Per Aspek HARS</p>
                <table class="tabel-aspek">
                    <thead>
                        <tr>
                            <th style="width:60px;">Kode</th>
                            <th>Aspek</th>
                            <th style="width:80px;">Nilai</th>
                            <th style="width:160px;">Visualisasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nilai_per_aspek as $kode => $data): ?>
                        <?php
                            $persen = ($data['nilai'] / 4) * 100;
                            $bar_color = '#10b981';
                            if ($data['nilai'] > 3) $bar_color = '#dc2626';
                            elseif ($data['nilai'] > 2) $bar_color = '#f97316';
                            elseif ($data['nilai'] > 1) $bar_color = '#f59e0b';
                        ?>
                        <tr>
                            <td><strong><?php echo $data['kode_aspek']; ?></strong></td>
                            <td><?php echo $data['nama_aspek']; ?></td>
                            <td><?php echo $data['nilai']; ?> / 4</td>
                            <td>
                                <div class="nilai-bar">
                                    <div class="bar-track">
                                        <div class="bar-fill" style="width:<?php echo $persen; ?>%; background:<?php echo $bar_color; ?>;"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <p class="section-title">Catatan</p>
                <div class="catatan-box">
                    <h4><i class="fas fa-sticky-note"></i> Catatan Hasil Konsultasi</h4>
                    <p><?php echo htmlspecialchars($catatan); ?></p>
                </div>

                <div class="ttd-section">
                    <div class="ttd-box">
                        <p class="tanggal">Yogyakarta, <?php echo date('d F Y', strtotime($tanggal)); ?></p>
                        <p class="nama-ttd">Psikologi Kita</p>
                        <p class="jabatan-ttd">Sistem Pakar HARS</p>
                    </div>
                </div>
            </div>

            <div class="catatan-kaki">
                <strong>Catatan:</strong> Hasil skrining ini bersifat penilaian awal dan bukan diagnosis klinis. 
                Untuk penanganan lebih lanjut, silakan konsultasikan dengan psikolog atau psikiater profesional. 
                Dokumen ini dihasilkan secara otomatis oleh sistem pakar.
            </div>
        </div>

        <div class="aksi-wrapper">
            <button class="btn-aksi btn-print" onclick="window.print();">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
            <a href="testimoni.php" class="btn-aksi btn-testimoni">
                <i class="fas fa-comment-dots"></i> Isi Testimoni
            </a>
            <a href="konsultasi.php" class="btn-aksi btn-ulang">
                <i class="fas fa-redo"></i> Tes Ulang
            </a>
            <a href="index.php" class="btn-aksi btn-beranda">
                <i class="fas fa-home"></i> Beranda
            </a>
        </div>
    </div>

<?php 
include 'footer.php'; 
?>
