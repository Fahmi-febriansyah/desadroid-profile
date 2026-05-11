<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DPM Expert System - Diagnosa Kendaraan</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1000;
            overflow: hidden;
            border: 1px solid var(--border);
        }
        .dropdown-content a {
            color: var(--text-main);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: 0.2s;
        }
        .dropdown-content a i {
            margin-right: 8px;
            color: var(--primary);
            width: 16px;
            text-align: center;
        }
        .dropdown-content a:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <header class="print-hide">
        <div class="container header-inner">
            <a href="index.php" class="logo-container">
                <img src="assets/logo.png" alt="DPM Logo">
                <div class="logo-text">DPM <span>Expert</span></div>
            </a>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="pilih_mobil.php"><i class="fas fa-stethoscope"></i> Konsultasi</a></li>
                    <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="tentang.php"><i class="fas fa-info-circle"></i> Tentang DPM</a></li>
                </ul>
                
                <?php if(isset($_SESSION['user_auth'])): ?>
                <div class="user-menu dropdown">
                    <span style="font-weight: 500; color: var(--text-secondary); font-size: 14px; cursor: pointer; padding: 10px 0;">
                        <i class="fas fa-user-circle" style="color: var(--primary); font-size: 18px; vertical-align: text-bottom; margin-right: 4px;"></i>
                        <?= htmlspecialchars($_SESSION['user_auth']['nama_lengkap']) ?>
                        <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 4px;"></i>
                    </span>
                    <div class="dropdown-content">
                        <a href="profil.php"><i class="fas fa-user-edit"></i> Edit Profil</a>
                        <a href="logout.php" style="color: #dc3545; border-top: 1px solid var(--border);"><i class="fas fa-sign-out-alt" style="color: #dc3545;"></i> Keluar</a>
                    </div>
                </div>
                <?php else: ?>
                <div class="user-menu">
                    <a href="login.php" class="btn btn-primary" style="padding: 8px 18px; font-size: 14px;"><i class="fas fa-sign-in-alt"></i> Masuk</a>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Cookie Consent Banner -->
    <div class="cookie-banner print-hide" id="cookieBanner">
        <div class="cookie-banner-inner">
            <p><i class="fas fa-cookie-bite" style="color: var(--primary); margin-right: 8px;"></i>Website ini menggunakan cookies untuk menyimpan progress konsultasi Anda.</p>
            <div class="cookie-btns">
                <button class="btn btn-primary" style="padding: 8px 20px; font-size: 14px;" onclick="acceptCookies()">Terima</button>
                <button class="btn btn-secondary" style="padding: 8px 20px; font-size: 14px;" onclick="declineCookies()">Tolak</button>
            </div>
        </div>
    </div>

    <script>
        function acceptCookies() {
            localStorage.setItem('dpm_cookie_consent', 'accepted');
            document.getElementById('cookieBanner').classList.remove('show');
        }
        function declineCookies() {
            localStorage.setItem('dpm_cookie_consent', 'declined');
            document.getElementById('cookieBanner').classList.remove('show');
        }
        if (!localStorage.getItem('dpm_cookie_consent')) {
            document.getElementById('cookieBanner').classList.add('show');
        }
    </script>
