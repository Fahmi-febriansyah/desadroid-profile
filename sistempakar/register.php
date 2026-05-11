<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['user_auth'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $no_hp = htmlspecialchars(trim($_POST['no_hp']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->fetchColumn() > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        $stmt = $conn->prepare("INSERT INTO user (nama_lengkap, no_hp, username, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $no_hp, $username, $password]);
        
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $_SESSION['user_auth'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - DPM Expert System</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
    <div class="glass-card confirm-card" style="width: 100%; max-width: 480px; text-align: center;">
        <div class="confirm-icon"><i class="fas fa-user-plus"></i></div>
        <h2 style="margin-bottom: 6px; font-size: 24px;">Buat Akun Baru</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px; font-size: 14px;">Daftar untuk mulai menggunakan konsultasi pakar DPM.</p>
        
        <?php if(isset($error)): ?>
        <div style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; border: 1px solid #fecaca;">
            <i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i><?= $error ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST" style="text-align: left;">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-phone"></i> No. Handphone</label>
                <input type="text" name="no_hp" class="form-control" required placeholder="Contoh: 08123456789">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-at"></i> Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Buat username unik">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Buat password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 4px;">Daftar <i class="fas fa-arrow-right"></i></button>
        </form>
        <p style="margin-top: 20px; color: var(--text-muted); font-size: 14px;">Sudah punya akun? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Masuk di sini</a></p>
    </div>
</body>
</html>
