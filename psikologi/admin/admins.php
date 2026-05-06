<?php
$page_title = "Data Admin - Admin Psikologi";
$active_menu = "admins";
include '../koneksi.php';

if (isset($_POST['add_admin'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query_add = "INSERT INTO admin (nama, email, password) VALUES ('$nama', '$email', '$password')";
    if (mysqli_query($koneksi, $query_add)) {
        header("Location: admins.php?status=added");
        exit();
    }
}

if (isset($_POST['update_admin'])) {
    $id_admin = intval($_POST['id_admin']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $update_pass = "";
    if (!empty($password)) {
        $update_pass = ", password='$password'";
    }

    $query_update = "UPDATE admin SET nama='$nama', email='$email' $update_pass WHERE id_admin=$id_admin";
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: admins.php?status=updated");
        exit();
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM admin WHERE id_admin = $id");
    header("Location: admins.php?status=deleted");
    exit();
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$where = $search ? "WHERE nama LIKE '%$search%' OR email LIKE '%$search%'" : "";

$query = "SELECT * FROM admin $where ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);

include 'header.php';
?>

<style>
    .action-btns { display: flex; gap: 8px; }
    .btn-delete { background: #fee2e2; color: #ef4444; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-delete:hover { background: #ef4444; color: #fff; }
    .btn-edit { background: #e0e7ff; color: #6366f1; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; cursor: pointer; }
    .btn-edit:hover { background: #6366f1; color: #fff; }

    .search-box { position: relative; width: 300px; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; }
    .search-box input { width: 100%; padding: 10px 12px 10px 38px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; outline: none; }

    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
    .modal.show { display: flex; }
    .modal-content { background: #fff; padding: 32px; border-radius: 16px; width: 90%; max-width: 500px; animation: modalSlideUp 0.3s; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
    @keyframes modalSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-header { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h2 { font-size: 1.25rem; font-weight: 700; }
    .close-modal { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem; }
    .form-group input { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; box-sizing: border-box; }
    .modal-footer { margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; }
    .btn-cancel { padding: 10px 20px; border-radius: 8px; border: none; background: #f1f5f9; color: #475569; cursor: pointer; font-weight: 600; }
    .btn-save { padding: 10px 20px; border-radius: 8px; border: none; background: #6366f1; color: #fff; cursor: pointer; font-weight: 600; }
    .btn-add { background: #6366f1; color: #fff; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
</style>

<?php if(isset($_GET['status'])): ?>
    <div style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
        Operasi berhasil: Data telah <?php 
            if($_GET['status'] == 'added') echo 'ditambahkan';
            elseif($_GET['status'] == 'updated') echo 'diperbarui';
            else echo 'dihapus';
        ?>!
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 15px;">
            <h2>Daftar Administrator</h2>
            <button class="btn-add" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Tambah Admin
            </button>
            <a href="print_data.php?type=admins" target="_blank" class="btn-add" style="background: #1e293b; text-decoration: none;">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>
        <div class="search-box">
            <form action="" method="GET">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari admin..." value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Administrator</th>
                    <th>Email</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; if(mysqli_num_rows($result) > 0): while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar" style="background:#f1f5f9; color: #6366f1;"><?php echo strtoupper(substr($row['nama'], 0, 1)); ?></div>
                            <div class="user-info">
                                <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-edit" 
                                onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?hapus=<?php echo $row['id_admin']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus admin ini?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-user-shield"></i><p>Tidak ada data administrator.</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Administrator Baru</h2>
            <button class="close-modal" onclick="closeAddModal()">&times;</button>
        </div>
        <form action="admins.php" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAddModal()">Batal</button>
                <button type="submit" name="add_admin" class="btn-save">Tambah Admin</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data Administrator</h2>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form action="admins.php" method="POST">
            <input type="hidden" name="id_admin" id="edit_id_admin">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" id="edit_nama" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Password Baru (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="password">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" name="update_admin" class="btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');

    function openAddModal() { addModal.classList.add('show'); }
    function closeAddModal() { addModal.classList.remove('show'); }

    function openEditModal(admin) {
        document.getElementById('edit_id_admin').value = admin.id_admin;
        document.getElementById('edit_nama').value = admin.nama;
        document.getElementById('edit_email').value = admin.email;
        editModal.classList.add('show');
    }
    function closeEditModal() { editModal.classList.remove('show'); }

    window.onclick = function(event) {
        if (event.target == addModal) closeAddModal();
        if (event.target == editModal) closeEditModal();
    }
</script>

<?php include 'footer.php'; ?>
