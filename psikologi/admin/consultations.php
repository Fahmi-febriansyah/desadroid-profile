<?php
$page_title = "Data Konsultasi - Admin Psikologi";
$active_menu = "consultations";
include '../koneksi.php';

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM jawaban_user WHERE id_consultation = $id");
    mysqli_query($koneksi, "DELETE FROM hasil_aspek WHERE id_consultation = $id");
    mysqli_query($koneksi, "DELETE FROM konsultasi WHERE id_konsultasi = $id");
    header("Location: consultations.php?status=deleted");
    exit();
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$where = $search ? "WHERE u.nama LIKE '%$search%' OR k.kategori LIKE '%$search%'" : "";

$query = "SELECT k.*, u.nama as nama_user, u.email as email_user 
          FROM konsultasi k 
          JOIN users u ON k.id_user = u.id_user 
          $where 
          ORDER BY k.tanggal DESC";
$result = mysqli_query($koneksi, $query);

include 'header.php';
?>

<style>
    .action-btns { display: flex; gap: 8px; }
    .btn-delete { background: #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-delete:hover { background: #ef4444; color: #fff; }
    .btn-detail { background: #fff; border: 1px solid #e2e8f0; color: #475569; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
    .btn-detail:hover { background: #f8fafc; border-color: #6366f1; color: #6366f1; }

    .search-box { position: relative; width: 300px; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; }
    .search-box input { width: 100%; padding: 10px 12px 10px 38px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; outline: none; }

    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
    .modal.show { display: flex; }
    .modal-content { background: #fff; padding: 32px; border-radius: 16px; width: 90%; max-width: 600px; animation: modalSlideUp 0.3s; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    @keyframes modalSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-header { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .modal-header h2 { font-size: 1.25rem; font-weight: 700; }
    .close-modal { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }

    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .detail-item label { display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 4px; }
    .detail-item p { font-weight: 600; color: #1e293b; }

    .aspect-list { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .aspect-item { display: flex; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; }
    .aspect-item:last-child { border-bottom: none; }
    .aspect-name { font-weight: 500; font-size: 0.9rem; }
    .aspect-value { font-weight: 700; color: #6366f1; }
</style>

<?php if(isset($_GET['status'])): ?>
    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
        Data konsultasi berhasil dihapus!
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2>Riwayat Konsultasi Pengguna</h2>
        <div class="search-box">
            <form action="" method="GET">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari nama atau kategori..." value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengguna</th>
                    <th>Tanggal</th>
                    <th>Total Skor</th>
                    <th>Kategori</th>
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
                                <span><?php echo htmlspecialchars($row['email_user']); ?></span>
                            </div>
                        </div>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['tanggal'])); ?></td>
                    <td><strong style="font-size: 1.1rem;"><?php echo $row['total_skor']; ?></strong></td>
                    <td>
                        <?php 
                            $status_class = 'status-info';
                            if(strpos(strtolower($row['kategori']), 'berat') !== false) $status_class = 'status-warning';
                            if(strpos(strtolower($row['kategori']), 'normal') !== false) $status_class = 'status-success';
                        ?>
                        <span class="status <?php echo $status_class; ?>"><?php echo $row['kategori']; ?></span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-detail" onclick="showDetail(<?php echo $row['id_konsultasi']; ?>)">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                            <a href="print_hasil.php?id=<?php echo $row['id_konsultasi']; ?>" target="_blank" class="btn-detail" style="background: #1e293b; color: #fff; border: none;">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                            <a href="?hapus=<?php echo $row['id_konsultasi']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data konsultasi ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-calendar-times"></i><p>Tidak ada data konsultasi ditemukan.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="detailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detail Hasil Konsultasi</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div id="detailBody">
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #6366f1;"></i>
                <p>Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('detailModal');

    function showDetail(id) {
        modal.classList.add('show');
        const detailBody = document.getElementById('detailBody');

        fetch('get_detail_konsultasi.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                detailBody.innerHTML = data;
            })
            .catch(error => {
                detailBody.innerHTML = '<p style="color:red">Terjadi kesalahan saat memuat data.</p>';
            });
    }

    function closeModal() {
        modal.classList.remove('show');
    }

    window.onclick = function(event) {
        if (event.target == modal) closeModal();
    }
</script>

<?php include 'footer.php'; ?>
