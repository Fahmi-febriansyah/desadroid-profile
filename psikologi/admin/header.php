<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['id_admin'])) { 
    header("Location: login.php"); 
    exit; 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin - Psikologi Kita'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">
                <div class="icon-box"><i class="fas fa-shield-halved"></i></div>
                <span>AdminPanel</span>
            </a>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo ($active_menu == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link <?php echo ($active_menu == 'users') ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>Data User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admins.php" class="nav-link <?php echo ($active_menu == 'admins') ? 'active' : ''; ?>">
                        <i class="fas fa-user-shield"></i>
                        <span>Data Admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="consultations.php" class="nav-link <?php echo ($active_menu == 'consultations') ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-check"></i>
                        <span>Data Konsultasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="testimonials.php" class="nav-link <?php echo ($active_menu == 'testimoni') ? 'active' : ''; ?>">
                        <i class="fas fa-comment-alt"></i>
                        <span>Data Testimoni</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="knowledge_base.php" class="nav-link <?php echo ($active_menu == 'system') ? 'active' : ''; ?>">
                        <i class="fas fa-brain"></i>
                        <span>Basis Pengetahuan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <a href="logout.php" class="nav-link" style="color: #ef4444;">
                <i class="fas fa-sign-out-alt" style="color: #ef4444;"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <main class="main-wrapper">
        
        <header class="topbar">
            <div class="topbar-left">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari data...">
                </div>
            </div>

            <div class="topbar-right">
                <div class="admin-profile">
                    <div class="admin-avatar"><?php echo isset($_SESSION['nama_admin']) ? strtoupper(substr($_SESSION['nama_admin'], 0, 2)) : 'AD'; ?></div>
                    <div class="admin-info">
                        <span class="admin-name"><?php echo isset($_SESSION['nama_admin']) ? htmlspecialchars($_SESSION['nama_admin']) : 'Administrator'; ?></span>
                        <span class="admin-role">Super Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
