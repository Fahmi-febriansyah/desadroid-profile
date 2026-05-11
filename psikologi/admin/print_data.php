<?php
session_start();
include '../koneksi.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'users';
$title = "Laporan Data";
$subtitle = "";

if ($type == 'users') {
    $title = "LAPORAN DATA PENGGUNA";
    $subtitle = "Daftar pengguna terdaftar di platform Psikologi Kita";
    $query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY nama ASC");
} elseif ($type == 'admins') {
    $title = "LAPORAN DATA ADMINISTRATOR";
    $subtitle = "Daftar pengelola sistem Psikologi Kita";
    $query = mysqli_query($koneksi, "SELECT * FROM admin ORDER BY nama ASC");
} elseif ($type == 'testimonials') {
    $title = "LAPORAN DATA TESTIMONI";
    $subtitle = "Ulasan dan masukan dari pengguna layanan";
    $query = mysqli_query($koneksi, "SELECT t.*, u.nama FROM testimoni t JOIN users u ON t.id_user = u.id_user ORDER BY t.tanggal DESC");
} elseif ($type == 'knowledge' || $type == 'knowledge_aspek' || $type == 'knowledge_rules' || $type == 'knowledge_results') {
    $title = "LAPORAN BASIS PENGETAHUAN";
    if ($type == 'knowledge_aspek') $subtitle = "Data Aspek HARS dan Indikator Gejala";
    elseif ($type == 'knowledge_rules') $subtitle = "Aturan Keputusan (Forward Chaining)";
    elseif ($type == 'knowledge_results') $subtitle = "Kategori Kecemasan dan Rekomendasi Hasil";
    else $subtitle = "Laporan Lengkap Sistem Pakar";
    $query_aspek = mysqli_query($koneksi, "SELECT * FROM aspek_hars ORDER BY kode_aspek ASC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
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
            <h3><?php echo $title; ?></h3>
            <p><?php echo $subtitle; ?></p>
        </div>

        <?php if ($type == 'users'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Umur</th>
                        <th>Gender</th>
                        <th>Tgl Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['umur']; ?> Thn</td>
                        <td><?php echo ($row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['created_at'] ?? 'now')); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($type == 'admins'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Admin</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($type == 'testimonials'): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Isi Testimoni</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                        <td>"<?php echo htmlspecialchars($row['isi']); ?>"</td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($type == 'knowledge' || $type == 'knowledge_aspek' || $type == 'knowledge_rules' || $type == 'knowledge_results'): ?>
            
            <?php if ($type == 'knowledge' || $type == 'knowledge_aspek'): ?>
            <div style="border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #6366f1;">I. ASPEK & INDIKATOR GEJALA</h4>
            </div>
            <?php while($aspek = mysqli_fetch_assoc($query_aspek)): ?>
                <div style="margin-bottom: 25px; page-break-inside: avoid;">
                    <div style="background: #f1f5f9; padding: 8px 12px; border-left: 4px solid #6366f1; margin-bottom: 8px;">
                        <strong style="font-size: 13px;"><?php echo $aspek['kode_aspek']; ?> - <?php echo htmlspecialchars($aspek['nama_aspek']); ?></strong>
                    </div>
                    <table class="table">
                        <thead>
                            <tr><th style="width: 40px;">No</th><th>Indikator Gejala / Pertanyaan</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            $id_asp = $aspek['id_aspek'];
                            $ind_q = mysqli_query($koneksi, "SELECT * FROM indikator WHERE id_aspek = $id_asp");
                            $no_ind = 1;
                            while($ind = mysqli_fetch_assoc($ind_q)):
                            ?>
                            <tr>
                                <td><?php echo $no_ind++; ?></td>
                                <td><?php echo htmlspecialchars($ind['pertanyaan']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endwhile; ?>
            <?php endif; ?>

            <?php if ($type == 'knowledge' || $type == 'knowledge_rules'): ?>
            <div style="border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-top: 20px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #6366f1;">II. ATURAN KEPUTUSAN (FORWARD CHAINING)</h4>
            </div>
            
            <p style="font-weight: 700; margin-bottom: 10px; color: #475569;">II.a Aturan Identifikasi Aspek Gejala (R1 - R14)</p>
            <table class="table">
                <thead>
                    <tr><th style="width: 60px;">Kode</th><th>Kondisi (IF)</th><th>Kesimpulan (THEN)</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $rules_q1 = mysqli_query($koneksi, "SELECT * FROM rule WHERE kode_rule REGEXP '^R([1-9]|1[0-4])$' ORDER BY id_rule ASC");
                    while($rule = mysqli_fetch_assoc($rules_q1)):
                    ?>
                    <tr>
                        <td><strong><?php echo $rule['kode_rule']; ?></strong></td>
                        <td><code>IF <?php echo htmlspecialchars($rule['kondisi']); ?></code></td>
                        <td><?php echo htmlspecialchars($rule['hasil']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <p style="font-weight: 700; margin-top: 25px; margin-bottom: 10px; color: #475569;">II.b Aturan Klasifikasi Tingkat Kecemasan (R16 - R20)</p>
            <table class="table">
                <thead>
                    <tr><th style="width: 60px;">Kode</th><th>Kondisi (IF)</th><th>Kesimpulan (THEN)</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $rules_q2 = mysqli_query($koneksi, "SELECT * FROM rule WHERE kode_rule REGEXP '^R(1[6-9]|20)$' ORDER BY id_rule ASC");
                    while($rule = mysqli_fetch_assoc($rules_q2)):
                    ?>
                    <tr>
                        <td><strong><?php echo $rule['kode_rule']; ?></strong></td>
                        <td><code>IF <?php echo htmlspecialchars($rule['kondisi']); ?></code></td>
                        <td><?php echo htmlspecialchars($rule['hasil']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>

            <?php if ($type == 'knowledge' || $type == 'knowledge_results'): ?>
            <div style="border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-top: 40px; margin-bottom: 20px;">
                <h4 style="margin: 0; color: #6366f1;">III. AMBANG SKOR & REKOMENDASI HASIL</h4>
            </div>
            <table class="table">
                <thead>
                    <tr><th>Kategori Hasil</th><th style="width: 120px;">Range Skor</th><th>Saran / Rekomendasi</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $kat_q = mysqli_query($koneksi, "SELECT rk.*, r.isi FROM rule_kategori rk LEFT JOIN rekomendasi r ON rk.kategori = r.kategori ORDER BY rk.min_skor ASC");
                    while($kat = mysqli_fetch_assoc($kat_q)):
                    ?>
                    <tr>
                        <td><strong><?php echo $kat['kategori']; ?></strong></td>
                        <td><?php echo $kat['min_skor']; ?> - <?php echo $kat['max_skor']; ?></td>
                        <td style="font-size: 12px;"><?php echo htmlspecialchars($kat['isi'] ?? '-'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php endif; ?>

        <?php endif; ?>

        <div class="footer">
            <div class="ttd">
                <p>Jakarta, <?php echo date('d F Y'); ?></p>
                <p style="margin-bottom: 70px;">Administrator,</p>
                <p><strong>PSIKOLOGI KITA</strong></p>
                <p style="font-size: 11px; color: #64748b;">Laporan Otomatis Sistem Pakar</p>
            </div>
        </div>
    </div>

</body>
</html>
