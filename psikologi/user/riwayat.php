<?php
session_start();
include '../koneksi.php';
include 'cek_login.php';

$id_user = $_SESSION['id_user'];

// Ambil semua riwayat konsultasi user
$query = "SELECT * FROM konsultasi WHERE id_user = $id_user ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

$page_title = 'Riwayat Konsultasi - Psikologi Kita';
$extra_css = '
<style>
    .page-header {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #1e1b4b, #312e81);
        color: #fff;
        text-align: center;
    }
    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .riwayat-wrapper {
        padding: 60px 24px;
        background: #f8fafc;
        min-height: calc(100vh - 400px);
    }
    .container-riwayat {
        max-width: 850px;
        margin: 0 auto;
    }
    .riwayat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.2s;
    }
    .riwayat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .riwayat-no {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #6366f1, #7c3aed);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .riwayat-info {
        flex: 1;
    }
    .riwayat-info h4 {
        font-size: 1rem;
        color: #1e293b;
        margin: 0 0 4px;
    }
    .riwayat-info p {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }
    .riwayat-meta {
        text-align: right;
        flex-shrink: 0;
    }
    .riwayat-skor {
        font-size: 1.3rem;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .riwayat-tanggal {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    .badge-kategori {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .badge-normal { background: #d1fae5; color: #065f46; }
    .badge-ringan { background: #fef3c7; color: #92400e; }
    .badge-sedang { background: #ffedd5; color: #9a3412; }
    .badge-berat { background: #fee2e2; color: #991b1b; }
    .badge-sangat { background: #fecaca; color: #7f1d1d; }
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    .empty-state h3 {
        color: #64748b;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #94a3b8;
        margin-bottom: 24px;
    }
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 6px 14px;
        background: #eef2ff;
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-detail:hover {
        background: #6366f1;
        color: #fff;
    }
    @media (max-width: 600px) {
        .riwayat-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .riwayat-meta {
            text-align: left;
            width: 100%;
        }
        .btn-detail {
            width: 100%;
            justify-content: center;
        }
    }
</style>
';
include 'header.php';
?>

    <div class="page-header">
        <div class="section-container">
            <h1>Riwayat Konsultasi</h1>
            <p>Lihat semua hasil skrining kecemasan Anda sebelumnya</p>
        </div>
    </div>

    <div class="riwayat-wrapper">
        <div class="container-riwayat">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <?php
                    // Badge warna berdasarkan kategori
                    $badge_class = 'badge-normal';
                    if (strpos($row['kategori'], 'ringan') !== false) $badge_class = 'badge-ringan';
                    elseif (strpos($row['kategori'], 'sedang') !== false) $badge_class = 'badge-sedang';
                    elseif (strpos($row['kategori'], 'sangat') !== false) $badge_class = 'badge-sangat';
                    elseif (strpos($row['kategori'], 'berat') !== false) $badge_class = 'badge-berat';
                ?>
                <div class="riwayat-card">
                    <div class="riwayat-no"><?php echo $no; ?></div>
                    <div class="riwayat-info">
                        <h4>Skrining HARS #<?php echo str_pad($row['id_konsultasi'], 5, '0', STR_PAD_LEFT); ?></h4>
                        <p><span class="badge-kategori <?php echo $badge_class; ?>"><?php echo $row['kategori']; ?></span></p>
                    </div>
                    <div class="riwayat-meta">
                        <div class="riwayat-skor"><?php echo $row['total_skor']; ?> <span style="font-size:0.8rem; font-weight:400; color:#94a3b8;">/ 72</span></div>
                        <div class="riwayat-tanggal"><?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?></div>
                        <a href="hasil.php?id=<?php echo $row['id_konsultasi']; ?>" class="btn-detail">
                            <i class="fas fa-external-link-alt"></i> Lihat Detail & Print
                        </a>
                    </div>
                </div>
                <?php $no++; endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Belum Ada Riwayat</h3>
                    <p>Anda belum pernah melakukan skrining kecemasan.</p>
                    <a href="konsultasi.php" class="btn btn-primary">
                        <i class="fas fa-comments"></i> Mulai Konsultasi Sekarang
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include 'footer.php'; ?>
