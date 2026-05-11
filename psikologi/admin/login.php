<?php
session_start();
include '../koneksi.php';

if (isset($_SESSION['id_admin'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id_admin'] = $row['id_admin'];
        $_SESSION['nama_admin'] = $row['nama'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Psikologi Kita</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --accent: #fff7ed;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f97316 0%, #fb923c 50%, #ea580c 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Dynamic Mesh Background */
        .mesh-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .mesh-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.6;
            animation: moveAround 25s infinite alternate;
        }

        .circle-1 { width: 600px; height: 600px; background: #fdba74; top: -200px; right: -100px; }
        .circle-2 { width: 500px; height: 500px; background: #fed7aa; bottom: -150px; left: -100px; animation-delay: -5s; }
        .circle-3 { width: 400px; height: 400px; background: #ffffff; top: 30%; left: 20%; opacity: 0.2; animation-duration: 30s; }

        @keyframes moveAround {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 100px) scale(1.2); }
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-radius: 40px;
            padding: 50px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
            text-align: center;
            transform: translateY(0);
            animation: cardAppear 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(50px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand-section {
            margin-bottom: 40px;
        }

        .logo-box {
            width: 100px;
            height: 100px;
            background: #fff;
            margin: 0 auto 24px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(249, 115, 22, 0.15);
            padding: 15px;
            transition: transform 0.3s ease;
        }

        .logo-box:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .logo-box img {
            max-width: 100%;
            height: auto;
        }

        .brand-section h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .brand-section p {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
        }

        .input-group {
            margin-bottom: 24px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
            padding-left: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-control {
            position: relative;
        }

        .input-control i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .input-control input {
            width: 100%;
            background: #f8fafc;
            border: 2px solid transparent;
            padding: 16px 20px 16px 55px;
            border-radius: 20px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--text-main);
            transition: all 0.3s;
            outline: none;
        }

        .input-control input:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 10px 20px rgba(249, 115, 22, 0.08);
        }

        .input-control input:focus + i {
            transform: translateY(-50%) scale(1.1);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #fff;
            border: none;
            padding: 18px;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 15px 30px rgba(234, 88, 12, 0.3);
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(234, 88, 12, 0.4);
            filter: brightness(1.05);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .error-alert {
            background: #fef2f2;
            color: #ef4444;
            padding: 15px 20px;
            border-radius: 18px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #fee2e2;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .footer-nav {
            margin-top: 35px;
        }

        .footer-nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .footer-nav a:hover {
            color: var(--primary);
            transform: translateX(-5px);
        }

        .pass-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
        }

        .pass-toggle:hover {
            color: var(--primary);
        }

        /* Floating decoration objects */
        .decor {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            z-index: 5;
            animation: float 6s infinite ease-in-out;
        }

        .decor-1 { width: 80px; height: 80px; top: 15%; right: 15%; animation-delay: 0s; }
        .decor-2 { width: 60px; height: 60px; bottom: 20%; left: 15%; animation-delay: -2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
    </style>
</head>
<body>

    <div class="mesh-bg">
        <div class="mesh-circle circle-1"></div>
        <div class="mesh-circle circle-2"></div>
        <div class="mesh-circle circle-3"></div>
    </div>

    <div class="decor decor-1"></div>
    <div class="decor decor-2"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-section">
                <div class="logo-box">
                    <img src="../logo.png" alt="Logo">
                </div>
                <h1>Admin Portal</h1>
                <p>Silakan masuk ke panel pengelolaan</p>
            </div>

            <?php if ($error): ?>
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label>Email Address</label>
                    <div class="input-control">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Masukkan email admin" required autocomplete="email">
                    </div>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <div class="input-control">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••" required>
                        <button type="button" class="pass-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-submit">
                    Masuk ke Sistem <i class="fas fa-sign-in-alt" style="margin-left: 8px;"></i>
                </button>
            </form>

            <div class="footer-nav">
                <a href="../index.php">
                    <i class="fas fa-long-arrow-alt-left"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
