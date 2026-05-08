<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $umur = intval($_POST['umur']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);

    $cek = mysqli_query($koneksi, "SELECT id_user FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar! Silakan gunakan email lain.";
    } else {
        $query = "INSERT INTO users (nama, email, password, umur, jenis_kelamin) 
                  VALUES ('$nama', '$email', '$password', $umur, '$jenis_kelamin')";
        if (mysqli_query($koneksi, $query)) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan, silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Psikologi Kita</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/auth.css?v=<?php echo filemtime(__DIR__.'/assets/css/auth.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper register-wrapper">

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
                        <span>Daftar Gratis & Mudah</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Akses Langsung ke Psikolog</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Privasi Data Terjamin</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Mulai Konsultasi Hari Ini</span>
                    </div>
                </div>
            </div>

            <div class="auth-right">
                <div class="auth-box">
                    <h2>Buat Akun Baru </h2>
                    <p class="auth-subtitle">Daftar dan mulai konsultasi sekarang</p>

                    <?php if ($error): ?>
                    <div style="background:#fef2f2; color:#dc2626; padding:12px; border-radius:8px; margin-bottom:16px; font-size:0.9rem; text-align:center;">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                    <div style="background:#d1fae5; color:#065f46; padding:12px; border-radius:8px; margin-bottom:16px; font-size:0.9rem; text-align:center;">
                        <?php echo $success; ?> <a href="login.php" style="color:#6366f1; font-weight:700;">Login sekarang</a>
                    </div>
                    <?php endif; ?>

                    <form action="register.php" method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" 
                                value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="regEmail">Email</label>
                            <input type="email" id="regEmail" name="email" placeholder="Masukkan email Anda"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="umur">Umur</label>
                                <input type="number" id="umur" name="umur" placeholder="Umur Anda" min="10" max="100"
                                    value="<?php echo isset($_POST['umur']) ? htmlspecialchars($_POST['umur']) : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label>
                                <select id="gender" name="jenis_kelamin" required>
                                    <option value="">Pilih</option>
                                    <option value="L" <?php echo (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?php echo (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="regPassword">Password</label>
                            <div class="password-field">
                                <input type="password" id="regPassword" name="password" placeholder="Buat password" required>
                                <button type="button" class="toggle-password" onclick="toggleRegPassword()">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="auth-button">Daftar</button>
                    </form>

                    <div class="auth-footer">
                        <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleRegPassword() {
        var input = document.getElementById('regPassword');
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
