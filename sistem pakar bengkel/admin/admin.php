<?php
require_once '../config/db.php';

// Hapus Admin
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM admin WHERE id_admin = $id");
    header("Location: admin.php");
    exit;
}

// Tambah / Edit Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_admin'])) {
    $id = $_POST['id_admin'];
    $nama = htmlspecialchars($_POST['nama']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if (empty($id)) {
        // Tambah
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO admin (nama_admin, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $username, $pass_hash]);
    } else {
        // Edit
        if (!empty($password)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin SET nama_admin=?, username=?, password=? WHERE id_admin=?");
            $stmt->execute([$nama, $username, $pass_hash, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE admin SET nama_admin=?, username=? WHERE id_admin=?");
            $stmt->execute([$nama, $username, $id]);
        }
    }
    header("Location: admin.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM admin ORDER BY id_admin ASC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="print-hide" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
    <div>
        <h2 style="margin: 0 0 5px 0;">Kelola Administrator</h2>
        <p style="color: var(--text-muted); margin: 0;">Tambah, Edit, dan Hapus pengurus sistem pakar DPM.</p>
    </div>
    <div>
        <button onclick="bukaFormAdmin()" class="btn btn-secondary" style="margin-right: 8px;"><i class="fas fa-plus"></i> Tambah Admin</button>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Data</button>
    </div>
</div>

<div class="print-only" style="display: none; text-align: center; margin-bottom: 20px;">
    <h2>Laporan Data Administrator - DPM Garage</h2>
    <p>Tanggal Cetak: <?= date('d M Y') ?></p>
</div>

<div class="glass-card" style="padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <table class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">No</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Nama Admin</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Username</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($admins as $a): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);"><?= $no++ ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 500;"><?= htmlspecialchars($a['nama_admin']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); color: var(--primary);"><?= htmlspecialchars($a['username']) ?></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='editAdmin(<?= json_encode($a, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;"><i class="fas fa-edit"></i> Edit</button>
                    <a href="?hapus=<?= $a['id_admin'] ?>" onclick="return confirm('Hapus admin ini?')" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; color: #dc3545; border-color: #dc3545;"><i class="fas fa-trash"></i> Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($admins)): ?>
            <tr><td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">Belum ada data admin. Pastikan melakukan seeder database.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Admin -->
<div id="modalForm" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <span class="modal-close" onclick="closeModal('modalForm')">&times;</span>
        <h3 id="formTitle" style="margin-bottom: 20px; color: var(--text-main);">Tambah Admin</h3>
        <form action="" method="POST">
            <input type="hidden" name="simpan_admin" value="1">
            <input type="hidden" name="id_admin" id="f_id_admin">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="f_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="f_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="f_password" class="form-control">
                <small id="f_pass_help" style="color:var(--text-muted); display:none;">Kosongkan jika tidak ingin ganti password.</small>
            </div>
            <div style="text-align: right; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalForm')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaFormAdmin() {
    document.getElementById('formTitle').innerText = "Tambah Admin";
    document.getElementById('f_id_admin').value = "";
    document.getElementById('f_nama').value = "";
    document.getElementById('f_username').value = "";
    document.getElementById('f_password').required = true;
    document.getElementById('f_pass_help').style.display = "none";
    openModal('modalForm');
}
function editAdmin(data) {
    document.getElementById('formTitle').innerText = "Edit Admin";
    document.getElementById('f_id_admin').value = data.id_admin;
    document.getElementById('f_nama').value = data.nama_admin;
    document.getElementById('f_username').value = data.username;
    document.getElementById('f_password').required = false;
    document.getElementById('f_pass_help').style.display = "block";
    openModal('modalForm');
}
</script>

<?php include 'footer.php'; ?>
