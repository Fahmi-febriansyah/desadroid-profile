<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['admin_auth'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_auth'] = [
            'id_admin' => $admin['id_admin'],
            'nama_admin' => $admin['nama_admin'],
            'username' => $admin['username']
        ];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password Admin salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DPM Garage</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: #fff;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header img {
            height: 60px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));
        }
        .login-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .login-header p {
            color: #94a3b8;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.1);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(230,81,0,0.2);
        }
        .form-label {
            color: #cbd5e1;
        }
        .btn-primary {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, #c24400 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230,81,0,0.4);
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }
        .back-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-header">
            <img src="../assets/logo.png" alt="DPM Logo">
            <h2>DPM ADMIN PORTAL</h2>
            <p>Sistem Pakar Diagnosa Kerusakan Mobil</p>
        </div>

        <?php if($error): ?>
        <div style="background: rgba(220, 53, 69, 0.2); color: #f87171; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid rgba(220,53,69,0.3); text-align: center;">
            <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary">MASUK SISTEM <i class="fas fa-sign-in-alt" style="margin-left: 8px;"></i></button>
        </form>

        <a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Web Pengguna</a>
    </div>

</body>
</html>
