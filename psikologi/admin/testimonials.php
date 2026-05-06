<?php
$page_title = "Data Testimoni - Admin Psikologi";
$active_menu = "testimoni";
include '../koneksi.php';

if (isset($_GET['status_change'])) {
    $id = intval($_GET['id']);
    $new_status = mysqli_real_escape_string($koneksi, $_GET['status_change']);
    mysqli_query($koneksi, "UPDATE testimoni SET status = '$new_status' WHERE id_testimoni = $id");
    header("Location: testimonials.php?status=updated");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM testimoni WHERE id_testimoni = $id");
    header("Location: testimonials.php?status=deleted");
    exit();
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$where = $search ? "WHERE u.nama LIKE '%$search%' OR t.isi LIKE '%$search%'" : "";

$query = "SELECT t.*, u.nama as nama_user, u.email as email_user 
          FROM testimoni t 
          JOIN users u ON t.id_user = u.id_user 
          $where 
          ORDER BY t.tanggal DESC";
$result = mysqli_query($koneksi, $query);

include 'header.php';
?>

<style>
    .action-btns { display: flex; gap: 8px; }
    .btn-delete { background: #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-delete:hover { background: #ef4444; color: #fff; }
    
    .btn-status { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
    .btn-approve { background: #d1fae5; color: #065f46; }
    .btn-approve:hover { background: #065f46; color: #fff; }
    .btn-hide { background: #f1f5f9; color: #64748b; }
    .btn-hide:hover { background: #64748b; color: #fff; }
    
    .search-box { position: relative; width: 300px; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; }
    .search-box input { width: 100%; padding: 10px 12px 10px 38px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; outline: none; }
    
    .testi-content { font-size: 0.9rem; color: #1e293b; line-height: 1.5; max-width: 400px; }
</style>

<?php if(isset($_GET['status'])): ?>
    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
        Data testimoni berhasil <?php echo $_GET['status'] == 'updated' ? 'diperbarui' : 'dihapus'; ?>!
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <h2>Kelola Testimoni Pengguna</h2>
            <a href="print_data.php?type=testimonials" target="_blank" class="btn-status" style="background: #1e293b; color: #fff; text-decoration: none; padding: 10px 20px; font-size: 0.9rem;">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
        <div class="search-box">
            <form action="" method="GET">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari nama atau isi..." value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengguna</th>
                    <th>Isi Testimoni</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; if(mysqli_num_rows($result) > 0): while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar" style="background:#f1f5f9; color: #6366f1;"><?php echo strtoupper(substr($row['nama_user'], 0, 1)); ?></div>
                            <div class="user-info">
                                <strong><?php echo htmlspecialchars($row['nama_user']); ?></strong>
                            </div>
                        </div>
                    </td>
                    <td><div class="testi-content">"<?php echo htmlspecialchars($row['isi']); ?>"</div></td>
                    <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                    <td>
                        <?php if($row['status'] == 'tampil'): ?>
                            <span class="status status-success"><i class="fas fa-check-circle"></i> Tampil</span>
                        <?php else: ?>
                            <span class="status status-info"><i class="fas fa-clock"></i> Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-btns">
                            <?php if($row['status'] == 'pending'): ?>
                                <a href="?status_change=tampil&id=<?php echo $row['id_testimoni']; ?>" class="btn-status btn-approve" title="Tampilkan di Landing Page">
                                    <i class="fas fa-check"></i> Setujui
                                </a>
                            <?php else: ?>
                                <a href="?status_change=pending&id=<?php echo $row['id_testimoni']; ?>" class="btn-status btn-hide" title="Sembunyikan dari Landing Page">
                                    <i class="fas fa-eye-slash"></i> Sembunyikan
                                </a>
                            <?php endif; ?>
                            
                            <a href="?hapus=<?php echo $row['id_testimoni']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus testimoni ini?')" title="Hapus Permanen">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-quote-left"></i><p>Tidak ada data testimoni ditemukan.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
