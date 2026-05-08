<?php
session_start();
include '../koneksi.php';
include 'cek_login.php';

$id_user = $_SESSION['id_user'];

$query = "SELECT * FROM konsultasi WHERE id_user = $id_user ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

$page_title = 'Riwayat Konsultasi - Psikologi Kita';
$extra_css = '
<style>
    .page-header {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
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
        background: linear-gradient(135deg, #f97316, #ea580c);
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
    .badge-sangat { background: #7f1d1d; color: #fff; }
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
        color: #1e293b;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #64748b;
        margin-bottom: 24px;
    }
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 6px 14px;
        background: #f1f5f9;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-detail:hover {
        background: #e2e8f0;
        color: #1e293b;
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
            <h1>Riwayat Deteksi Kecemasan</h1>
            <p>Lihat semua hasil skrining kecemasan Anda sebelumnya dalam satu tempat</p>
        </div>
    </div>

    <div class="riwayat-wrapper">
        <div class="container-riwayat" style="max-width: 1000px;">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div style="background: #fff; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                            <thead>
                                <tr style="border-bottom: 2px solid #f1f5f9; text-align: left;">
                                    <th style="padding: 15px; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">No</th>
                                    <th style="padding: 15px; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">ID Skrining</th>
                                    <th style="padding: 15px; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Tanggal</th>
                                    <th style="padding: 15px; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Total Skor</th>
                                    <th style="padding: 15px; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Kategori Diagnosis</th>
                                    <th style="padding: 15px; color: #64748b; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                <?php
                                    $badge_class = 'badge-normal';
                                    if (strpos($row['kategori'], 'ringan') !== false) $badge_class = 'badge-ringan';
                                    elseif (strpos($row['kategori'], 'sedang') !== false) $badge_class = 'badge-sedang';
                                    elseif (strpos($row['kategori'], 'sangat') !== false) $badge_class = 'badge-sangat';
                                    elseif (strpos($row['kategori'], 'berat') !== false) $badge_class = 'badge-berat';
                                ?>
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 15px; font-weight: 600; color: #1e293b;"><?php echo $no++; ?></td>
                                    <td style="padding: 15px; color: #475569;">#<?php echo str_pad($row['id_konsultasi'], 5, '0', STR_PAD_LEFT); ?></td>
                                    <td style="padding: 15px; color: #475569;"><?php echo format_indo($row['tanggal']); ?></td>
                                    <td style="padding: 15px;"><strong style="font-size: 1.1rem; color: #f97316;"><?php echo $row['total_skor']; ?></strong> <span style="font-size: 0.75rem; color: #94a3b8;">/ 56</span></td>
                                    <td style="padding: 15px;"><span class="badge-kategori <?php echo $badge_class; ?>"><?php echo strtoupper($row['kategori']); ?></span></td>
                                    <td style="padding: 15px;">
                                        <a href="hasil.php?id=<?php echo $row['id_konsultasi']; ?>" class="btn-detail" style="margin:0;">
                                            <i class="fas fa-eye"></i> Lihat Hasil
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Belum Ada Riwayat</h3>
                    <p>Anda belum pernah melakukan skrining kecemasan.</p>
                    <a href="konsultasi.php" class="btn btn-primary">
                        <i class="fas fa-comments"></i> Mulai Deteksi Sekarang
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include 'footer.php'; ?>
