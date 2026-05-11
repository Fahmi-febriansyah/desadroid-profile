<?php
session_start();
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id_konsultasi = intval($_GET['id']);
    
    // Pastiin yang liat datanya emang yang punya akun (security dikit lah)
    $extra_where = "";
    if (isset($_SESSION['id_user'])) {
        $extra_where = " AND id_user = " . intval($_SESSION['id_user']);
    }
    
    // Ambil data utamanya dari tabel konsultasi
    $konsul_q = mysqli_query($koneksi, "SELECT * FROM konsultasi WHERE id_konsultasi = $id_konsultasi $extra_where");
    if (mysqli_num_rows($konsul_q) == 0) {
        header("Location: riwayat.php");
        exit();
    }
    $konsul = mysqli_fetch_assoc($konsul_q);
    
    $total_skor = $konsul['total_skor'];
    $kategori = $konsul['kategori'];
    $tanggal = $konsul['tanggal'];
    
    // Ambil rincian nilai tiap aspeknya
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
    
    // Cari saran/catatan yang sesuai kategorinya
    $catatan = 'Tidak ada catatan';
    $rekom_q = mysqli_query($koneksi, "SELECT * FROM rekomendasi WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kategori) . "' LIMIT 1");
    if ($rek = mysqli_fetch_assoc($rekom_q)) {
        $catatan = $rek['isi'];
    }
    
    // Simulasi ulang rule forward chaining buat ditampilin di log inferensi
    $rule_terpicu = array();
    $rules = array();
    $rule_q = mysqli_query($koneksi, "SELECT * FROM rule ORDER BY id_rule ASC");
    while ($r = mysqli_fetch_assoc($rule_q)) { $rules[] = $r; }

    foreach ($rules as $rule) {
        // Cek rule aspek (R1-R14)
        if (preg_match('/^R([1-9]|1[0-4])$/', $rule['kode_rule'])) {
            $parts = explode(' ', $rule['kondisi']);
            $kode = $parts[0];
            if (isset($nilai_per_aspek[$kode])) {
                if ($nilai_per_aspek[$kode]['nilai'] >= floatval($parts[2])) {
                    $rule_terpicu[] = ['kode_rule' => $rule['kode_rule'], 'hasil' => $rule['hasil'], 'terpicu' => true];
                }
            }
        }
        // Rule itung total (R15)
        if ($rule['kode_rule'] == 'R15') {
            $rule_terpicu[] = ['kode_rule' => 'R15', 'hasil' => "Total Skor HARS: $total_skor", 'terpicu' => true];
        }
        // Rule penentuan kategori (Ambil dari rule_kategori agar sinkron dengan dashboard)
        $kat_q = mysqli_query($koneksi, "SELECT * FROM rule_kategori WHERE $total_skor >= min_skor AND $total_skor <= max_skor LIMIT 1");
        if ($k_row = mysqli_fetch_assoc($kat_q)) {
            // Cari kode rule-nya biar log inferensi cakep
            $r_k_q = mysqli_query($koneksi, "SELECT kode_rule FROM rule WHERE hasil = '" . mysqli_real_escape_string($koneksi, $k_row['kategori']) . "' LIMIT 1");
            $r_kode = 'R?';
            if ($rk = mysqli_fetch_assoc($r_k_q)) { $r_kode = $rk['kode_rule']; }
            
            $rule_terpicu[] = ['kode_rule' => $r_kode, 'hasil' => $k_row['kategori'], 'terpicu' => true];
        }
    }
} elseif (isset($_SESSION['hasil_konsultasi'])) {
    // Kalo abis ngerjain tes, datanya diambil dari session aja biar cepet
    $hasil = $_SESSION['hasil_konsultasi'];
    $id_konsultasi = $hasil['id_konsultasi'];
    $total_skor = $hasil['total_skor'];
    $kategori = $hasil['kategori'];
    $catatan = $hasil['catatan'] ?? 'Tidak ada catatan';
    $nilai_per_aspek = $hasil['nilai_per_aspek'];
    $rule_terpicu = $hasil['rule_terpicu'];
    $tanggal = $hasil['tanggal'];
} else {
    // Kalo iseng buka halaman ini tanpa data, balik ke kuesioner aja
    header("Location: kuesioner.php");
    exit();
}

// Ambil data profil usernya buat diprint di laporan
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

// Atur warna status biar keren diliat dosen
$badge_color = '#10b981'; 
if (strpos($kategori, 'ringan') !== false) $badge_color = '#f59e0b';
elseif (strpos($kategori, 'sedang') !== false) $badge_color = '#f97316';
elseif (strpos($kategori, 'berat') !== false) $badge_color = '#ef4444';

$page_title = 'Hasil Konsultasi HARS - Psikologi Kita';
$extra_css = '
<style>
    /* Styling buat layout laporannya biar kayak surat medis beneran */
    .hasil-wrapper { padding: 100px 24px 60px; background: #f1f5f9; min-height: 100vh; }
    .surat-medis { max-width: 800px; margin: 0 auto; background: #fff; border: 1px solid #d1d5db; position: relative; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
    .kop-surat { padding: 30px 40px 20px; border-bottom: 3px solid #1e293b; display: flex; align-items: center; gap: 20px; }
    .kop-logo { width: 60px; height: 60px; border-radius: 12px; background: #6366f1; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; flex-shrink: 0; }
    .kop-info h2 { font-size: 1.4rem; color: #1e293b; margin: 0; }
    .kop-info p { font-size: 0.85rem; color: #64748b; margin: 0; }
    .surat-body { padding: 30px 40px; }
    .surat-judul { text-align: center; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; }
    .surat-judul h3 { font-size: 1.2rem; margin: 0; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
    .info-pasien { margin-bottom: 25px; }
    .info-pasien table { width: 100%; }
    .info-pasien td { padding: 4px 0; font-size: 0.9rem; }
    .info-pasien td:first-child { width: 150px; font-weight: 600; color: #64748b; }
    .diagnosis-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 30px; text-align: center; }
    .diagnosis-kategori { font-size: 1.5rem; font-weight: 800; margin-bottom: 4px; }
    .section-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 30px 0 15px; padding-bottom: 8px; border-bottom: 2px solid #6366f1; display: inline-block; }
    .tabel-aspek { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 0.9rem; }
    .tabel-aspek th { background: #f8fafc; padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
    .tabel-aspek td { padding: 12px; border-bottom: 1px solid #f1f5f9; }
    .nilai-bar { height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-top: 4px; }
    .bar-fill { height: 100%; border-radius: 4px; }
    .catatan-box { background: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 16px 20px; border-radius: 0 8px 8px 0; margin-bottom: 25px; }
    .catatan-box h4 { font-size: 0.9rem; margin: 0 0 4px; color: #0369a1; }
    .rule-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px; font-size: 0.85rem; }
    .rule-badge { background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; }
    .ttd-section { display: flex; justify-content: flex-end; margin-top: 40px; }
    .ttd-box { text-align: center; width: 200px; }
    .ttd-box p { margin: 0; font-size: 0.9rem; }
    .aksi-wrapper { max-width: 800px; margin: 24px auto 0; display: flex; gap: 12px; }
    .btn-aksi { flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border-radius: 8px; font-weight: 600; text-decoration: none; transition: 0.2s; }
    .btn-print { background: #1e293b; color: #fff; }
    .btn-ulang { background: #fff; border: 1px solid #cbd5e1; color: #475569; }
    @media print { 
        .navbar, .footer, .aksi-wrapper { display: none !important; } 
        .hasil-wrapper { padding: 0; background: #fff; } 
        .surat-medis { border: none; box-shadow: none; width: 100%; max-width: 100%; font-size: 14px; }
        .surat-body { padding: 20px 0; }
        .diagnosis-box { padding: 15px; margin-bottom: 20px; }
        .diagnosis-kategori { font-size: 1.4rem; }
        .section-title { margin: 15px 0 10px; }
        .kop-surat { padding: 15px 0; }
        @page { size: A4; margin: 1.5cm; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>
';
include 'header.php';
?>

<div class="hasil-wrapper">
    <div class="surat-medis">
        <!-- Bagian Kop Surat -->
        <div class="kop-surat">
            <div class="kop-logo" style="background: transparent;">
                <img src="../logo.png" alt="Logo" style="height: 50px; width: auto;">
            </div>
            <div class="kop-info">
                <h2 style="color: #1e293b;">PSIKOLOGI KITA</h2>
                <p>Lembaga Konsultan dan terapi Psikologi</p>
            </div>
        </div>

        <div class="surat-body">
            <div class="surat-judul">
                <h3><?php echo isset($_GET['id']) ? 'Arsip Hasil Deteksi Kecemasan' : 'Hasil Skrining Kecemasan (HARS)'; ?></h3>
                <p>No. Konsultasi: #<?php echo str_pad($id_konsultasi, 5, '0', STR_PAD_LEFT); ?></p>
            </div>

            <!-- Tabel Identitas User -->
            <div class="info-pasien">
                <table>
                    <tr><td>Nama Pasien</td><td>: <?php echo htmlspecialchars($nama_user); ?></td></tr>
                    <tr><td>Usia</td><td>: <?php echo $umur_user; ?></td></tr>
                    <tr><td>Jenis Kelamin</td><td>: <?php echo $jk_user; ?></td></tr>
                    <tr><td>Tanggal Periksa</td><td>: <?php echo format_indo($tanggal, 'd F Y, H:i'); ?></td></tr>
                </table>
            </div>

            <!-- Kotak Diagnosa Utama -->
            <div class="diagnosis-box">
                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 8px;">Kesimpulan Diagnosis</p>
                <p class="diagnosis-kategori" style="color: <?php echo $badge_color; ?>;"><?php echo strtoupper($kategori); ?></p>
                <p style="font-size: 1rem; color: #1e293b;">Total Skor: <strong><?php echo $total_skor; ?></strong> / 56</p>
            </div>

            <!-- Aspek Terindikasi Tinggi -->
            <?php
            // Ambil semua aspek yang terindikasi (nilai >= 2)
            usort($nilai_per_aspek, function($a, $b) { return $b['nilai'] - $a['nilai']; });
            $aspek_tinggi = array_filter($nilai_per_aspek, function($asp) { return $asp['nilai'] >= 2; });
            if (!empty($aspek_tinggi)):
            ?>
            <p class="section-title">Aspek Terindikasi Tinggi</p>
            <table class="tabel-aspek" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 30px;">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Aspek / Gejala</th>
                        <th style="width: 150px; text-align: center;">Skor Keparahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($aspek_tinggi as $asp): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 6px;"></i> <?php echo htmlspecialchars($asp['nama_aspek']); ?></td>
                        <td style="text-align: center; color: #ef4444; font-weight: bold;"><?php echo $asp['nilai']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>



            <!-- Rekomendasi/Saran Psikolog -->
            <p class="section-title">Rekomendasi Psikolog</p>
            <div class="catatan-box">
                <h4><i class="fas fa-info-circle"></i> Saran Tindak Lanjut:</h4>
                <p><?php echo htmlspecialchars($catatan); ?></p>
            </div>

            <!-- Tanda Tangan buat Laporan -->
            <div class="ttd-section" style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 50px;">
                <div class="ttd-box" style="text-align: center; width: 200px;">
                    <p style="color: #64748b; margin-bottom: 60px;">Klien Psikologi Kita</p>
                    <p style="font-weight: 700; border-top: 1px solid #1e293b; padding-top: 4px;"><?php echo htmlspecialchars($nama_user); ?></p>
                </div>
                <div class="ttd-box">
                    <p style="color: #64748b; margin-bottom: 60px;">Jakarta, <?php echo format_indo($tanggal, 'd F Y'); ?></p>
                    <p style="font-weight: 700; border-top: 1px solid #1e293b; padding-top: 4px;">PSIKOLOGI KITA</p>
                    <p style="font-size: 0.8rem; color: #64748b;">Layanan Konsultasi Psikologi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol aksi di bawah laporan -->
    <div class="aksi-wrapper">
        <button class="btn-aksi btn-print" onclick="window.print();"><i class="fas fa-print"></i> Cetak PDF</button>
        <a href="konsultasi.php" class="btn-aksi btn-ulang"><i class="fas fa-redo"></i> Tes Ulang</a>
        <a href="index.php" class="btn-aksi btn-ulang" style="background:#6366f1; color:#fff; border:none;"><i class="fas fa-home"></i> Beranda</a>
    </div>
</div>

<?php if(isset($_GET['print']) && $_GET['print'] == 1): ?>
<script>
    window.onload = function() {
        window.print();
    }
</script>
<?php endif; ?>

<?php include 'footer.php'; ?>
