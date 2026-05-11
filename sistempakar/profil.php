<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $password = $_POST['password'];

    // Cek apakah username dipakai orang lain
    $cek = $conn->prepare("SELECT id_user FROM user WHERE username = ? AND id_user != ?");
    $cek->execute([$username, $id_user]);
    
    if ($cek->rowCount() > 0) {
        $error = "Username sudah digunakan oleh pengguna lain.";
    } else {
        if (!empty($password)) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET nama_lengkap=?, username=?, no_hp=?, password=? WHERE id_user=?");
            $stmt->execute([$nama, $username, $no_hp, $pass_hash, $id_user]);
        } else {
            $stmt = $conn->prepare("UPDATE user SET nama_lengkap=?, username=?, no_hp=? WHERE id_user=?");
            $stmt->execute([$nama, $username, $no_hp, $id_user]);
        }
        
        // Update session
        $_SESSION['user']['nama_lengkap'] = $nama;
        $_SESSION['user']['username'] = $username;
        $success = "Profil berhasil diperbarui!";
    }
}

$stmt = $conn->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="container" style="padding: 60px 0;">
    <div style="max-width: 600px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 80px; height: 80px; background: var(--primary-light); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 15px;">
                <i class="fas fa-user-circle"></i>
            </div>
            <h2>Pengaturan Profil</h2>
            <p style="color: var(--text-muted);">Perbarui informasi pribadi dan keamanan akun Anda.</p>
        </div>

        <?php if($success): ?>
            <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <i class="fas fa-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="glass-card" style="padding: 30px;">
            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user_data['nama_lengkap']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user_data['username']) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Handphone (WhatsApp)</label>
                    <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($user_data['no_hp']) ?>" required>
                </div>
                
                <hr style="border:0; border-top: 1px solid var(--border); margin: 30px 0;">
                <h4 style="margin-bottom: 15px; color: var(--text-main);">Ubah Keamanan</h4>
                
                <div class="form-group">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                    <small style="color: var(--text-muted); display: block; margin-top: 5px;">Hanya isi jika Anda ingin mengganti password.</small>
                </div>
                
                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <a href="riwayat.php" class="btn btn-outline" style="flex: 1; text-align: center;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="flex: 2;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<?php include 'footer.php'; ?>
