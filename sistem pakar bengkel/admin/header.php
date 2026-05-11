<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Untuk mempermudah test saat ini, kita bypass login admin.
if (!isset($_SESSION['admin_auth'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DPM Expert System</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --admin-bg: #f8fafc;
        }
        body { background-color: var(--admin-bg); margin: 0; padding: 0; display: flex; min-height: 100vh; }
        
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-menu {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
        }
        .sidebar-menu ul { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu li a i { width: 24px; font-size: 16px; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
            width: calc(100% - var(--sidebar-width));
        }
        
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: #fff;
            padding: 15px 25px;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-sm);
        }
        
        .stat-card {
            background: #fff;
            padding: 24px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .stat-info h4 { margin: 0; font-size: 14px; color: var(--text-muted); font-weight: 500; }
        .stat-info .num { font-size: 28px; font-weight: 800; color: var(--text-main); margin-top: 4px; }
        
        /* Modal Styles */
        .modal {
            display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;
        }
        .modal.show { display: flex; }
        .modal-content {
            background-color: #fff; border-radius: var(--radius); padding: 30px;
            width: 100%; max-width: 600px; position: relative; max-height: 90vh; overflow-y: auto;
        }
        .modal-close {
            position: absolute; right: 20px; top: 20px; font-size: 20px; cursor: pointer; color: var(--text-muted);
        }
        
        @media print {
            .sidebar, .topbar, .print-hide, button, a { display: none !important; }
            .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            body { background: #fff !important; }
            .glass-card { box-shadow: none !important; border: none !important; }
            table th, table td { border: 1px solid #000 !important; color: #000 !important; }
            table th { background: #eee !important; -webkit-print-color-adjust: exact; }
            @page { size: A4; margin: 15mm; }
        }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../assets/logo.png" alt="Logo" style="height: 30px;">
            <div style="font-weight: 800; font-size: 18px;">DPM <span style="color: var(--primary);">Admin</span></div>
        </div>
        <div class="sidebar-menu">
            <?php $cur = basename($_SERVER['PHP_SELF']); ?>
            <ul>
                <li><a href="index.php" class="<?= $cur=='index.php'?'active':'' ?>"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="user.php" class="<?= $cur=='user.php'?'active':'' ?>"><i class="fas fa-users"></i> Kelola Pengguna</a></li>
                <li><a href="admin.php" class="<?= $cur=='admin.php'?'active':'' ?>"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
                <li><a href="konsultasi.php" class="<?= $cur=='konsultasi.php'?'active':'' ?>"><i class="fas fa-history"></i> Riwayat Konsultasi</a></li>
                <li><a href="knowledge.php" class="<?= $cur=='knowledge.php'?'active':'' ?>"><i class="fas fa-database"></i> Basis Pengetahuan</a></li>
                <li><a href="logout.php" style="color: #dc3545; border-top: 1px solid var(--border); margin-top: 10px;"><i class="fas fa-sign-out-alt"></i> Keluar (Logout)</a></li>
                <li><a href="../index.php" style="margin-top: 30px; color: var(--text-muted);"><i class="fas fa-external-link-alt"></i> Ke Web Pengguna</a></li>
            </ul>
        </div>
    </div>
    
    <div class="main-content">
        <div class="topbar print-hide">
            <div style="font-size: 20px; font-weight: 700;">Panel Kontrol Administrasi</div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="text-align: right;">
                    <div style="font-weight: 600; font-size: 14px;">Administrator</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Sistem Pakar DPM</div>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-user-cog"></i>
                </div>
            </div>
        </div>
