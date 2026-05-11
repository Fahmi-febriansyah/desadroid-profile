<?php
require_once '../config/db.php';

// Hapus Konsultasi
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM detail_konsultasi WHERE id_konsultasi = $id");
    $conn->query("DELETE FROM konsultasi WHERE id_konsultasi = $id");
    header("Location: konsultasi.php");
    exit;
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$where = $bulan ? "WHERE DATE_FORMAT(k.tanggal, '%Y-%m') = '$bulan'" : "";

$stmt = $conn->query("
    SELECT k.*, u.nama_lengkap, km.nama_kategori 
    FROM konsultasi k
    JOIN user u ON k.id_user = u.id_user
    JOIN kategori_mesin km ON k.id_kategori = km.id_kategori
    $where
    ORDER BY k.tanggal DESC
");
$riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch details for JSON
$details = [];
$stmt_d = $conn->query("SELECT * FROM detail_konsultasi");
while($row = $stmt_d->fetch(PDO::FETCH_ASSOC)) {
    $details[$row['id_konsultasi']][] = $row;
}

include 'header.php';
?>

<div class="print-hide" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
    <div>
        <h2 style="margin: 0 0 5px 0;">Riwayat Seluruh Konsultasi</h2>
        <p style="color: var(--text-muted); margin: 0;">Data riwayat diagnosa yang dilakukan oleh semua pengguna.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <input type="text" id="sKonsul" class="form-control" placeholder="Cari nama atau merk..." style="width: 180px;" onkeyup="filterTable('sKonsul', 'tKonsul')">
        <form method="GET" style="display: flex; gap: 5px;">
            <input type="month" name="bulan" value="<?= htmlspecialchars($bulan) ?>" class="form-control" style="width: 150px;">
            <button type="submit" class="btn btn-secondary">Filter</button>
            <?php if($bulan): ?><a href="konsultasi.php" class="btn btn-outline">Reset</a><?php endif; ?>
        </form>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Laporan</button>
    </div>
</div>

<div class="print-only" style="display: none; text-align: center; margin-bottom: 20px;">
    <h2>Laporan Rekam Medis Konsultasi - DPM Garage</h2>
    <p>Tanggal Cetak: <?= date('d M Y') ?></p>
</div>

<div class="glass-card" style="padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <table class="data-table" id="tKonsul" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">No</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Tanggal</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Nama Pengguna</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Kendaraan & Mesin</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Hasil Diagnosa</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($riwayat as $r): 
                $r['gejala'] = isset($details[$r['id_konsultasi']]) ? $details[$r['id_konsultasi']] : [];
            ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);"><?= $no++ ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 14px;"><?= date('d/m/Y H:i', strtotime($r['tanggal'])) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 500;"><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); color: var(--primary);"><?= htmlspecialchars($r['merk_mobil']) ?> <br><span style="color:var(--text-secondary); font-size: 12px;">(<?= htmlspecialchars($r['nama_kategori']) ?>)</span></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 600;"><?= htmlspecialchars($r['hasil_diagnosa']) ?> <br><span class="badge badge-primary" style="font-size: 11px;"><?= $r['nilai_cf'] ?>%</span></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='showDetail(<?= json_encode($r, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;"><i class="fas fa-eye"></i> Detail Modal</button>
                    <a href="?hapus=<?= $r['id_konsultasi'] ?>" onclick="return confirm('Hapus riwayat diagnosa ini?')" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; color: #dc3545; border-color: #dc3545; margin-left: 5px;"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($riwayat)): ?>
            <tr><td colspan="6" style="padding: 20px; text-align: center; color: var(--text-muted);">Belum ada riwayat konsultasi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="modalDetail" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <span class="modal-close" onclick="closeModal('modalDetail')">&times;</span>
        <h3 style="margin-bottom: 20px; color: var(--text-main);">Detail Diagnosa</h3>
        
        <div style="background: var(--surface); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr><td style="padding: 6px 0; width: 140px; font-weight: 600;">ID Konsultasi</td><td style="padding: 6px 0;" id="m_id"></td></tr>
                <tr><td style="padding: 6px 0; font-weight: 600;">Tanggal</td><td style="padding: 6px 0;" id="m_tgl"></td></tr>
                <tr><td style="padding: 6px 0; font-weight: 600;">Nama Pemilik</td><td style="padding: 6px 0;" id="m_nama"></td></tr>
                <tr><td style="padding: 6px 0; font-weight: 600;">Kendaraan</td><td style="padding: 6px 0;" id="m_mobil"></td></tr>
                <tr><td style="padding: 6px 0; font-weight: 600;">Hasil Kerusakan</td><td style="padding: 6px 0; font-weight: bold; color: var(--primary);" id="m_hasil"></td></tr>
            </table>
        </div>

        <h4 style="margin-bottom: 10px; font-size: 14px;">Gejala yang Dialami:</h4>
        <ul id="m_gejala" style="font-size: 13px; line-height: 1.6; color: var(--text-secondary); padding-left: 20px;"></ul>

        <div style="margin-top: 25px; text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
            <a id="m_print" href="#" target="_blank" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Print Dokumen Asli</a>
            <button class="btn btn-secondary" onclick="closeModal('modalDetail')">Tutup</button>
        </div>
    </div>
</div>

<script>
function showDetail(data) {
    document.getElementById('m_id').innerText = ": #" + String(data.id_konsultasi).padStart(5, '0');
    document.getElementById('m_tgl').innerText = ": " + data.tanggal;
    document.getElementById('m_nama').innerText = ": " + data.nama_lengkap;
    document.getElementById('m_mobil').innerText = ": " + data.merk_mobil + " (" + data.nama_kategori + ")";
    document.getElementById('m_hasil').innerText = ": " + data.hasil_diagnosa + " (CF: " + data.nilai_cf + "%)";
    
    let htmlGejala = "";
    if (data.gejala && data.gejala.length > 0) {
        data.gejala.forEach(g => {
            htmlGejala += "<li><strong>[" + g.kode_gejala + "]</strong> " + g.nama_gejala + " (CF: " + g.cf_user + ")</li>";
        });
    } else {
        htmlGejala = "<li>Tidak ada rincian gejala.</li>";
    }
    document.getElementById('m_gejala').innerHTML = htmlGejala;
    document.getElementById('m_print').href = "hasil.php?id=" + data.id_konsultasi;
    
    openModal('modalDetail');
}
</script>

<?php include 'footer.php'; ?>
