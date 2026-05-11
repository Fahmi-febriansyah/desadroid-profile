<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['user_auth'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_auth'] = $user;

        if (!empty($user['draft_merk_mobil'])) {
            $_SESSION['active_konsultasi'] = [
                'merk_mobil' => $user['draft_merk_mobil']
            ];
            header("Location: konsultasi.php");
            exit;
        }

        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - DPM Expert System</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
    <div class="glass-card confirm-card" style="width: 100%; max-width: 420px; text-align: center;">
        <div class="confirm-icon"><i class="fas fa-user-lock"></i></div>
        <h2 style="margin-bottom: 6px; font-size: 24px;">Masuk Akun</h2>
        <p style="color: var(--text-muted); margin-bottom: 24px; font-size: 14px;">Masuk untuk menggunakan konsultasi pakar.</p>
        
        <?php if(isset($error)): ?>
        <div style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; border: 1px solid #fecaca;">
            <i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i><?= $error ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST" style="text-align: left;">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 4px;">Masuk <i class="fas fa-arrow-right"></i></button>
        </form>
        <p style="margin-top: 20px; color: var(--text-muted); font-size: 14px;">Belum punya akun? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Daftar di sini</a></p>
    </div>
</body>
</html>
