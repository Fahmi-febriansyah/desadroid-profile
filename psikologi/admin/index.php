<?php
$page_title = "Dashboard Admin - Psikologi Kita";
$active_menu = "dashboard";
include '../koneksi.php';
include 'header.php';

$total_user = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM users"))[0];
$total_admin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM admin"))[0];
$total_konsul = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM konsultasi"))[0];
$total_testi = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM testimoni"))[0];

$query_konsul = "SELECT k.*, u.nama as nama_user, u.email as email_user 
                 FROM konsultasi k 
                 JOIN users u ON k.id_user = u.id_user 
                 ORDER BY k.tanggal DESC LIMIT 5";
$result_konsul = mysqli_query($koneksi, $query_konsul);

$query_testi = "SELECT t.*, u.nama as nama_user 
                FROM testimoni t 
                JOIN users u ON t.id_user = u.id_user 
                ORDER BY t.tanggal DESC LIMIT 5";
$result_testi = mysqli_query($koneksi, $query_testi);
?>

<div class="page-header">
    <div class="page-title">
        <h1>Ringkasan Sistem</h1>
        <p>Pantau aktivitas platform Psikologi Kita hari ini.</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-details">
            <h3>Total User</h3>
            <div class="stat-number"><?php echo number_format($total_user); ?></div>
            <div class="stat-trend trend-up">
                <i class="fas fa-user"></i> Pengguna Terdaftar
            </div>
        </div>
        <div class="stat-icon icon-blue">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3>Total Admin</h3>
            <div class="stat-number"><?php echo number_format($total_admin); ?></div>
            <div class="stat-trend trend-up">
                <i class="fas fa-shield-alt"></i> Pengelola Sistem
            </div>
        </div>
        <div class="stat-icon icon-emerald">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3>Total Konsultasi</h3>
            <div class="stat-number"><?php echo number_format($total_konsul); ?></div>
            <div class="stat-trend trend-up">
                <i class="fas fa-history"></i> Riwayat Sesi
            </div>
        </div>
        <div class="stat-icon icon-amber">
            <i class="fas fa-comments"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-details">
            <h3>Total Testimoni</h3>
            <div class="stat-number"><?php echo number_format($total_testi); ?></div>
            <div class="stat-trend trend-up">
                <i class="fas fa-star"></i> Ulasan User
            </div>
        </div>
        <div class="stat-icon icon-primary">
            <i class="fas fa-quote-right"></i>
        </div>
    </div>
</div>

<div class="dashboard-grid">

    <div class="card">
        <div class="card-header">
            <h2>Konsultasi Terbaru</h2>
            <a href="consultations.php" class="card-action">Lihat Semua <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Skor</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result_konsul) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result_konsul)): ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar" style="background:#f1f5f9; color: var(--primary);"><?php echo strtoupper(substr($row['nama_user'], 0, 1)); ?></div>
                                    <div class="user-info">
                                        <strong><?php echo htmlspecialchars($row['nama_user']); ?></strong>
                                        <span><?php echo htmlspecialchars($row['email_user']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo $row['total_skor']; ?></td>
                            <td>
                                <?php 
                                    $status_class = 'status-info';
                                    if(strpos(strtolower($row['kategori']), 'berat') !== false) $status_class = 'status-warning';
                                    if(strpos(strtolower($row['kategori']), 'normal') !== false) $status_class = 'status-success';
                                ?>
                                <span class="status <?php echo $status_class; ?>"><?php echo $row['kategori']; ?></span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center; padding:20px;">Belum ada data konsultasi.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Data Testimoni</h2>
            <a href="#" class="card-action">Lihat Semua <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="schedule-list">
            <?php if(mysqli_num_rows($result_testi) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result_testi)): ?>
                <div class="schedule-item">
                    <div class="schedule-time">
                        <strong><?php echo date('d', strtotime($row['tanggal'])); ?></strong>
                        <span><?php echo date('M', strtotime($row['tanggal'])); ?></span>
                    </div>
                    <div class="schedule-info">
                        <h4><?php echo htmlspecialchars($row['nama_user']); ?></h4>
                        <p>"<?php echo (strlen($row['isi']) > 60) ? substr(htmlspecialchars($row['isi']), 0, 60) . '...' : htmlspecialchars($row['isi']); ?>"</p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align:center; padding:20px; color: var(--text-light);">Belum ada testimoni.</div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
