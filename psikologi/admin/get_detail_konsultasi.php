<?php
include '../koneksi.php';

if (!isset($_GET['id'])) {
    exit('ID tidak valid');
}

$id = intval($_GET['id']);

$query = "SELECT k.*, u.nama, u.email, u.umur, u.jenis_kelamin 
          FROM konsultasi k 
          JOIN users u ON k.id_user = u.id_user 
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
?>

<div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
    <a href="print_hasil.php?id=<?php echo $id; ?>" target="_blank" class="btn" style="background: #1e293b; color: #fff; font-size: 0.8rem; padding: 6px 12px; border-radius: 6px; text-decoration: none;">
        <i class="fas fa-print"></i> Cetak Laporan PDF
    </a>
</div>

<div class="detail-grid">
    <div class="detail-item">
        <label>Nama Pengguna</label>
        <p><?php echo htmlspecialchars($data['nama']); ?></p>
    </div>
    <div class="detail-item">
        <label>Email</label>
        <p><?php echo htmlspecialchars($data['email']); ?></p>
    </div>
    <div class="detail-item">
        <label>Umur / Gender</label>
        <p><?php echo $data['umur']; ?> Thn / <?php echo ($data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'); ?></p>
    </div>
    <div class="detail-item">
        <label>Tanggal Konsultasi</label>
        <p><?php echo date('d M Y, H:i', strtotime($data['tanggal'])); ?></p>
    </div>
    <div class="detail-item">
        <label>Total Skor</label>
        <p style="font-size: 1.5rem; color: var(--primary);"><?php echo $data['total_skor']; ?></p>
    </div>
    <div class="detail-item">
        <label>Kategori Hasil</label>
        <p><?php echo $data['kategori']; ?></p>
    </div>
</div>

<h3 style="font-size: 1rem; margin-bottom: 12px; font-weight: 700;">Breakdown Nilai per Aspek</h3>
<div class="aspect-list">
    <?php while($asp = mysqli_fetch_assoc($result_aspek)): ?>
    <div class="aspect-item">
        <div class="aspect-name">
            <span style="color: var(--text-light); font-weight: 600;"><?php echo $asp['kode_aspek']; ?></span> - 
            <?php echo htmlspecialchars($asp['nama_aspek']); ?>
        </div>
        <div class="aspect-value"><?php echo $asp['nilai_aspek']; ?></div>
    </div>
    <?php endwhile; ?>
</div>

<div style="margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 8px;">
    <p style="font-size: 0.85rem; color: var(--text-light); line-height: 1.5;">
        * Nilai di atas adalah rata-rata skor dari indikator dalam setiap aspek gangguan kecemasan (HARS).
    </p>
</div>
