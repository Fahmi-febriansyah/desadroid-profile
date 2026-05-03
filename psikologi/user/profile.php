<?php
session_start();
include '../koneksi.php';
include 'cek_login.php';

// Ambil data user terbaru dari database
$id_user = $_SESSION['id_user'];
$query = "SELECT * FROM users WHERE id_user = $id_user LIMIT 1";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

// Proses update profil
$pesan = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $umur = intval($_POST['umur']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $password_baru = $_POST['password_baru'];

    // Update query
    $update = "UPDATE users SET nama = '$nama', umur = $umur, jenis_kelamin = '$jenis_kelamin'";
    
    // Jika password diisi, update juga
    if (!empty($password_baru)) {
        $update .= ", password = '$password_baru'";
    }
    
    $update .= " WHERE id_user = $id_user";

    if (mysqli_query($koneksi, $update)) {
        // Update session
        $_SESSION['nama'] = $nama;
        $_SESSION['umur'] = $umur;
        $_SESSION['jenis_kelamin'] = $jenis_kelamin;
        $pesan = "Profil berhasil diperbarui!";
        
        // Refresh data
        $result = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user LIMIT 1");
        $user = mysqli_fetch_assoc($result);
    } else {
        $error = "Gagal memperbarui profil.";
    }
}

$page_title = 'Edit Profil - Psikologi Kita';
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
    .profile-wrapper {
        padding: 60px 24px;
        background: #f8fafc;
        min-height: calc(100vh - 400px);
    }
    .container-profile {
        max-width: 650px;
        margin: 0 auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
    }
    .avatar-section {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e2e8f0;
    }
    .avatar-big {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #7c3aed);
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }
    .avatar-section p {
        color: #64748b;
        font-size: 0.9rem;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1e293b;
        font-size: 0.95rem;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
        transition: all 0.3s;
        box-sizing: border-box;
        background: #f8fafc;
    }
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        background: #fff;
    }
    .form-control[readonly] {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-hint {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 4px;
    }
    .btn-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
    .alert-error {
        background: #fef2f2;
        color: #dc2626;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
    select.form-control {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2.5\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 40px;
    }
    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
        .btn-actions { flex-direction: column; }
    }
</style>
';
include 'header.php';
?>

    <div class="page-header">
        <div class="section-container">
            <h1>Edit Profil</h1>
            <p>Perbarui informasi pribadi Anda</p>
        </div>
    </div>

    <div class="profile-wrapper">
        <div class="container-profile">
            <div class="avatar-section">
                <div class="avatar-big"><?php echo strtoupper(substr($user['nama'], 0, 1)); ?></div>
                <p>ID Pasien: #<?php echo str_pad($user['id_user'], 5, '0', STR_PAD_LEFT); ?></p>
            </div>

            <?php if ($pesan): ?>
                <div class="alert-success"><?php echo $pesan; ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="profile.php" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    <p class="form-hint">Email tidak dapat diubah</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="umur">Umur</label>
                        <input type="number" name="umur" id="umur" class="form-control" value="<?php echo $user['umur']; ?>" min="10" max="100" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                            <option value="L" <?php echo ($user['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?php echo ($user['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_baru">Password Baru</label>
                    <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                    <p class="form-hint">Isi hanya jika ingin mengganti password</p>
                </div>

                <div class="btn-actions">
                    <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="index.php" class="btn btn-outline" style="flex:1; justify-content:center; color:#475569; border-color:#cbd5e1; background:#f1f5f9;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

<?php include 'footer.php'; ?>
