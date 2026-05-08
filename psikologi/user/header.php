<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'cek_login.php';

$current_page = basename($_SERVER['PHP_SELF']);
$page_title = isset($page_title) ? $page_title : 'Psikologi Kita - Konsultasi Psikologi Online';

$user_nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$user_initial = strtoupper(substr($user_nama, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="Platform konsultasi psikologi online terpercaya. Dapatkan bantuan dari psikolog profesional untuk kesehatan mental Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__.'/assets/css/style.css'); ?>">
    <?php if(isset($extra_css)) echo $extra_css; ?>
</head>
<body>
    <nav class="navbar" id="navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-logo">
                <img src="../logo.png" alt="Logo">
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Beranda</a></li>
                <li><a href="konsultasi.php" class="nav-link <?php echo ($current_page == 'konsultasi.php' || $current_page == 'kuesioner.php') ? 'active' : ''; ?>">Konsultasi</a></li>
                <li><a href="riwayat.php" class="nav-link <?php echo ($current_page == 'riwayat.php') ? 'active' : ''; ?>">Riwayat Deteksi</a></li>
                <li><a href="psikologi.php" class="nav-link <?php echo ($current_page == 'psikologi.php') ? 'active' : ''; ?>">Psikolog</a></li>
                <li><a href="testimoni.php" class="nav-link <?php echo ($current_page == 'testimoni.php') ? 'active' : ''; ?>">Testimoni</a></li>
                <li><a href="index.php#kontak" class="nav-link">Kontak</a></li>
            </ul>
            <div class="nav-right">
                <div class="profile-dropdown" id="profileDropdown">
                    <button class="profile-btn" id="profileBtn">
                        <div class="profile-avatar" id="profileAvatar"><?php echo $user_initial; ?></div>
                        <span class="profile-name" id="profileName"><?php echo htmlspecialchars($user_nama); ?></span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <div class="dropdown-header">
                            <div class="header-avatar"><?php echo $user_initial; ?></div>
                            <div class="header-info">
                                <p class="header-name" id="headerName"><?php echo htmlspecialchars($user_nama); ?></p>
                                <p class="header-email" id="headerEmail"><?php echo htmlspecialchars($user_email); ?></p>
                            </div>
                        </div>
                        <div class="dropdown-body">
                            <a href="profile.php" class="dropdown-item">
                                <i class="fas fa-user-edit"></i><span>Edit Profil</span>
                            </a>
                        </div>
                        <div class="dropdown-footer">
                            <a href="logout.php" class="dropdown-item logout" id="logoutBtn">
                                <i class="fas fa-sign-out-alt"></i><span>Keluar</span>
                            </a>
                        </div>
                    </div>
                </div>
                <button class="hamburger" id="hamburger">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>
