<?php
include '../koneksi.php';

if (!isset($_GET['id'])) {
    exit('ID tidak valid');
}

$id = intval($_GET['id']);

$query = "SELECT k.*, u.nama, u.email, u.umur, u.jenis_kelamin, r.isi as rekomendasi
          FROM konsultasi k 
          JOIN users u ON k.id_user = u.id_user 
          LEFT JOIN rekomendasi r ON k.kategori = r.kategori
          WHERE k.id_konsultasi = $id";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    exit('Data tidak ditemukan');
}

$query_aspek = "SELECT ha.*, a.nama_aspek, a.kode_aspek 
                FROM hasil_aspek ha 
                JOIN aspek_hars a ON ha.id_aspek = a.id_aspek 
                WHERE ha.id_konsultasi = $id 
                ORDER BY a.id_aspek ASC";
$result_aspek = mysqli_query($koneksi, $query_aspek);

// Get indicated aspects (Skor >= 3)
$indicated = [];
$indicated_q = mysqli_query($koneksi, "SELECT a.nama_aspek, ha.nilai_aspek FROM hasil_aspek ha JOIN aspek_hars a ON ha.id_aspek = a.id_aspek WHERE ha.id_konsultasi = $id AND ha.nilai_aspek >= 3 ORDER BY ha.nilai_aspek DESC");
while($asp = mysqli_fetch_assoc($indicated_q)) {
    $indicated[] = $asp;
}
mysqli_data_seek($result_aspek, 0);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
    <div>
        <h4 style="margin:0; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">ID Konsultasi</h4>
        <span style="font-weight: 700; color: #1e293b;">#<?php echo str_pad($id, 5, '0', STR_PAD_LEFT); ?></span>
    </div>
    <a href="print_hasil.php?id=<?php echo $id; ?>" target="_blank" class="btn" style="background: #1e293b; color: #fff; font-size: 0.85rem; padding: 8px 16px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: 0.2s;">
        <i class="fas fa-print"></i> Cetak Laporan PDF
    </a>
</div>

<div class="detail-grid">
    <div class="detail-item">
        <label>Nama Pasien</label>
        <p><?php echo htmlspecialchars($data['nama']); ?></p>
    </div>
    <div class="detail-item">
        <label>Email & Kontak</label>
        <p><?php echo htmlspecialchars($data['email']); ?></p>
    </div>
    <div class="detail-item">
        <label>Profil Klinis</label>
        <p><?php echo $data['umur']; ?> Thn | <?php echo ($data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'); ?></p>
    </div>
    <div class="detail-item">
        <label>Waktu Deteksi</label>
        <p><?php echo date('d M Y, H:i', strtotime($data['tanggal'])); ?></p>
    </div>
</div>

<div style="margin: 25px 0; padding: 20px; background: #fff; border: 2px solid #e2e8f0; border-radius: 16px; display: flex; align-items: center; gap: 20px;">
    <div style="width: 80px; height: 80px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800; color: #6366f1; border: 4px solid #e0e7ff;">
        <?php echo $data['total_skor']; ?>
    </div>
    <div>
        <label style="display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 4px; font-weight: 600;">KESIMPULAN DIAGNOSIS</label>
        <h3 style="margin: 0; color: #1e293b; font-size: 1.4rem; font-weight: 800;"><?php echo strtoupper($data['kategori']); ?></h3>
        <p style="margin: 4px 0 0; font-size: 0.85rem; color: #6366f1; font-weight: 600;">Berdasarkan Hamilton Anxiety Rating Scale (HARS)</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 30px;">
    <div>
        <h3 style="font-size: 1rem; margin-bottom: 15px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-list-check" style="color: #6366f1;"></i> Breakdown Aspek
        </h3>
        <div class="aspect-list">
            <?php while($asp = mysqli_fetch_assoc($result_aspek)): ?>
            <div class="aspect-item" style="<?php echo $asp['nilai_aspek'] >= 3 ? 'background: #fff1f2;' : ''; ?>">
                <div class="aspect-name">
                    <span style="color: #64748b; font-weight: 600; width: 30px; display: inline-block;"><?php echo $asp['kode_aspek']; ?></span>
                    <?php echo htmlspecialchars($asp['nama_aspek']); ?>
                </div>
                <div class="aspect-value" style="<?php echo $asp['nilai_aspek'] >= 3 ? 'color: #ef4444;' : ''; ?>"><?php echo $asp['nilai_aspek']; ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <div>
        <h3 style="font-size: 1rem; margin-bottom: 15px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Analisis Pakar
        </h3>
        
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
            <label style="display: block; font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Aspek Dominan Terindikasi:</label>
            <?php if(!empty($indicated)): ?>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <?php foreach($indicated as $ind): ?>
                        <div style="background: #fef2f2; border: 1px solid #fee2e2; padding: 6px 12px; border-radius: 50px; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                            <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 0.7rem;"></i>
                            <span style="font-size: 0.75rem; font-weight: 600; color: #991b1b;"><?php echo htmlspecialchars($ind['nama_aspek']); ?> <span style="opacity: 0.7; font-weight: 400;">(Skor: <?php echo $ind['nilai_aspek']; ?>)</span></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="font-size: 0.85rem; color: #94a3b8; font-style: italic;">Tidak ada aspek yang mencapai ambang batas tinggi.</p>
            <?php endif; ?>
        </div>

        <div style="background: #f0f9ff; border-radius: 12px; padding: 20px; border: 1px solid #bae6fd;">
            <label style="display: block; font-size: 0.75rem; color: #0369a1; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Rekomendasi Tindakan:</label>
            <p style="font-size: 0.9rem; color: #0c4a6e; line-height: 1.6; margin: 0;">
                <?php echo $data['rekomendasi'] ? nl2br(htmlspecialchars($data['rekomendasi'])) : 'Belum ada rekomendasi tersimpan untuk kategori ini.'; ?>
            </p>
        </div>
    </div>
</div>

<div style="margin-top: 30px; padding: 15px; background: #fffbeb; border-radius: 8px; border: 1px solid #fef3c7;">
    <p style="font-size: 0.8rem; color: #92400e; margin: 0; line-height: 1.5;">
        <strong>Catatan Admin:</strong> Laporan ini dihasilkan secara otomatis oleh sistem pakar menggunakan metode Forward Chaining. Hasil ini bersifat skrining awal dan disarankan untuk dikonsultasikan lebih lanjut dengan tenaga profesional psikologi jika diperlukan.
    </p>
</div>

