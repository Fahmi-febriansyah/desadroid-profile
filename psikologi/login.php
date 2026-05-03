<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['umur'] = $user['umur'];
        $_SESSION['jenis_kelamin'] = $user['jenis_kelamin'];
        header("Location: user/index.php");
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
    <title>Login - Psikologi Kita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper">
            <!-- LEFT SIDE - BRANDING -->
            <div class="auth-left">
                <div class="auth-brand">
                    <div class="brand-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h1>Psikologi Kita</h1>
                    <p>Kesehatan Mental Adalah Prioritas</p>
                </div>
                <div class="auth-benefits">
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Konsultasi dengan Psikolog Profesional</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Rahasia & Aman Terjamin</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Layanan 24/7 Online</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Harga Terjangkau & Fleksibel</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE - LOGIN FORM -->
            <div class="auth-right">
                <div class="auth-box">
                    <h2>Selamat Datang! 👋</h2>
                    <p class="auth-subtitle">Masuk ke akun Anda untuk melanjutkan konsultasi</p>

                    <?php if (isset($error)): ?>
                    <div style="background:#fef2f2; color:#dc2626; padding:12px; border-radius:8px; margin-bottom:16px; font-size:0.9rem; text-align:center;">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Masukkan email Anda"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="password-field">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Masukkan password Anda"
                                    required
                                >
                                <button type="button" class="toggle-password" onclick="togglePassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="auth-button">Masuk</button>
                    </form>

                    <div class="auth-footer">
                        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        var input = document.getElementById('password');
        var btn = event.target.closest('.toggle-password');
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }
    </script>
</body>
</html>
