<?php
require_once '../config/db.php';

// Hapus User
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    // Hapus detail konsultasi, konsultasi, lalu user
    $conn->query("DELETE FROM detail_konsultasi WHERE id_konsultasi IN (SELECT id_konsultasi FROM konsultasi WHERE id_user = $id)");
    $conn->query("DELETE FROM konsultasi WHERE id_user = $id");
    $conn->query("DELETE FROM user WHERE id_user = $id");
    header("Location: user.php");
    exit;
}

// Tambah / Edit User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_user'])) {
    $id = $_POST['id_user'];
    $nama = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $password = $_POST['password'];

    if (empty($id)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (nama_lengkap, username, no_hp, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $username, $no_hp, $pass_hash]);
    } else {
        if (!empty($password)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET nama_lengkap=?, username=?, no_hp=?, password=? WHERE id_user=?");
            $stmt->execute([$nama, $username, $no_hp, $pass_hash, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE user SET nama_lengkap=?, username=?, no_hp=? WHERE id_user=?");
            $stmt->execute([$nama, $username, $no_hp, $id]);
        }
    }
    header("Location: user.php");
    exit;
}

$stmt = $conn->query("SELECT * FROM user ORDER BY id_user DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="print-hide" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
    <div>
        <h2 style="margin: 0 0 5px 0;">Kelola Pengguna</h2>
        <p style="color: var(--text-muted); margin: 0;">Tambah, Edit, dan Hapus pengguna sistem pakar.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <input type="text" id="searchUser" class="form-control" placeholder="Cari nama atau username..." style="width: 250px;" onkeyup="filterTable('searchUser', 'userTable')">
        <button onclick="bukaFormUser()" class="btn btn-secondary"><i class="fas fa-plus"></i> Tambah Pengguna</button>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Data</button>
    </div>
</div>

<div class="print-only" style="display: none; text-align: center; margin-bottom: 20px;">
    <h2>Laporan Data Pengguna - DPM Garage</h2>
    <p>Tanggal Cetak: <?= date('d M Y') ?></p>
</div>

<div class="glass-card" style="padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <table class="data-table" id="userTable" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">No</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Nama Lengkap</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Username</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">No HP</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($users as $u): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);"><?= $no++ ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 500;"><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); color: var(--primary);"><?= htmlspecialchars($u['username']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);"><?= htmlspecialchars($u['no_hp']) ?></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='editUser(<?= json_encode($u, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                    <a href="?hapus=<?= $u['id_user'] ?>" onclick="return confirm('Yakin hapus pengguna ini? Semua riwayat diagnosanya juga akan terhapus.')" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: #dc3545; border-color: #dc3545;"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($users)): ?>
            <tr><td colspan="5" style="padding: 20px; text-align: center; color: var(--text-muted);">Belum ada data pengguna.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form User -->
<div id="modalForm" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <span class="modal-close" onclick="closeModal('modalForm')">&times;</span>
        <h3 id="formTitle" style="margin-bottom: 20px; color: var(--text-main);">Tambah Pengguna</h3>
        <form action="" method="POST">
            <input type="hidden" name="simpan_user" value="1">
            <input type="hidden" name="id_user" id="f_id_user">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="f_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="f_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">No. Handphone</label>
                <input type="text" name="no_hp" id="f_hp" class="form-control" required>
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
function bukaFormUser() {
    document.getElementById('formTitle').innerText = "Tambah Pengguna";
    document.getElementById('f_id_user').value = "";
    document.getElementById('f_nama').value = "";
    document.getElementById('f_username').value = "";
    document.getElementById('f_hp').value = "";
    document.getElementById('f_password').required = true;
    document.getElementById('f_pass_help').style.display = "none";
    openModal('modalForm');
}
function editUser(data) {
    document.getElementById('formTitle').innerText = "Edit Pengguna";
    document.getElementById('f_id_user').value = data.id_user;
    document.getElementById('f_nama').value = data.nama_lengkap;
    document.getElementById('f_username').value = data.username;
    document.getElementById('f_hp').value = data.no_hp;
    document.getElementById('f_password').required = false;
    document.getElementById('f_pass_help').style.display = "block";
    openModal('modalForm');
}

function filterTable(inputId, tableId) {
    let filter = document.getElementById(inputId).value.toLowerCase();
    let rows = document.getElementById(tableId).getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        let text = rows[i].innerText.toLowerCase();
        rows[i].style.display = text.includes(filter) ? "" : "none";
    }
}
</script>

<?php include 'footer.php'; ?>
