<?php
// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get current page for active menu - determine if in subfolders
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$is_admin_root = $current_dir === 'admin';
$is_artikel = $current_dir === 'artikel';
$is_project = $current_dir === 'project';
$is_client = $current_dir === 'client';
$is_admins = $current_dir === 'admins';
$is_letters = $current_dir === 'outgoing_letters';

// Generate correct absolute site root and admin base for pretty admin URLs
// If the script is under /admin/*, take the part before /admin as site root.
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$pos = strpos($script, '/admin');
if ($pos !== false) {
    $siteRoot = substr($script, 0, $pos);
} else {
    $siteRoot = rtrim(dirname($script), '/');
}
if ($siteRoot === '/' || $siteRoot === '') $siteRoot = '';
$adminBase = $siteRoot . '/admin';
// keep $prefix for legacy relative links if needed
$prefix = $is_admin_root ? './' : '../';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Desadroid Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #333;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
        }
        .sidebar-header h2 {
            font-size: 1.4rem;
            margin-bottom: 0.25rem;
        }
        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li {
            margin: 0;
        }
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-left h1 {
            font-size: 1.5rem;
            color: #0066cc;
        }
        .topbar-right {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        /* mobile sidebar toggle */
        #sidebarToggle { display:none; }
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .logout-btn {
            padding: 0.5rem 1rem;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h2 {
            color: #0066cc;
            font-size: 1.8rem;
        }
        .btn {
            display: inline-block;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #0066cc;
            color: white;
        }
        .btn-primary:hover {
            background: #0052a3;
        }
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-weight: 500;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
        /* Make tables horizontally scrollable on small screens */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 1rem;
        }
        /* Ensure tables that need more space can scroll instead of breaking layout */
        .table-responsive table {
            min-width: 700px;
        }
        th {
            background: #f5f6fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
            color: #0066cc;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 180px;
                padding: 1rem 0;
            }
            .sidebar-header h2 {
                font-size: 1.2rem;
            }
            .sidebar-header p {
                font-size: 0.75rem;
            }
            .main-content {
                margin-left: 180px;
            }
            .sidebar-menu a {
                padding: 0.6rem 0.8rem;
                font-size: 0.9rem;
            }
            .topbar {
                flex-direction: column;
                gap: 0.5rem;
                padding: 0.75rem 1rem;
            }

            /* hide sidebar by default on small screens, toggle with .open */
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; }
            .sidebar.open { transform: translateX(0); z-index: 2000; }
            #sidebarToggle { display: inline-flex; padding:0.4rem 0.6rem; font-size:1.1rem; background:#0066cc;color:#fff;border-radius:4px;border:none;cursor:pointer; }
            .main-content { margin-left: 0; }
            }
            .topbar-left h1 {
                font-size: 1.2rem;
            }
            .page-header {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }
            .page-header h2 {
                font-size: 1.4rem;
            }
            .content {
                padding: 1rem;
            }
            table {
                font-size: 0.85rem;
            }
            th, td {
                padding: 0.5rem;
            }
            .action-btns {
                flex-direction: column;
                gap: 0.25rem;
            }
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .btn-sm {
                padding: 0.35rem 0.6rem;
                font-size: 0.75rem;
            }
        }
        @media (max-width: 480px) {
            .sidebar {
                width: 160px;
            }
            .main-content {
                margin-left: 160px;
            }
            .sidebar-menu a {
                padding: 0.5rem 0.6rem;
                font-size: 0.8rem;
            }
            .topbar-left h1 {
                font-size: 1rem;
            }
            .page-header h2 {
                font-size: 1.2rem;
            }
            .content {
                padding: 0.75rem;
            }
            table {
                font-size: 0.75rem;
            }
            th, td {
                padding: 0.4rem;
            }
        }
    </style>
    
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>desadroid</h2>
                <p>Admin Panel</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?= htmlspecialchars($adminBase) ?>" class="<?= $is_admin_root && $current_page === 'index.php' ? 'active' : '' ?>">📊 Beranda</a></li>
                <li><a href="<?= htmlspecialchars($adminBase . '/artikel/list') ?>" class="<?= $is_artikel ? 'active' : '' ?>">📝 Artikel</a></li>
                <li><a href="https://project.desadroid.shop/admin" target="_blank">🎯 Proyek</a></li>
                <li><a href="<?= htmlspecialchars($adminBase . '/client/list') ?>" class="<?= $is_client ? 'active' : '' ?>">⭐ Klien</a></li>
                    <li><a href="<?= htmlspecialchars($adminBase . '/outgoing_letters/list') ?>" class="<?= $is_letters ? 'active' : '' ?>">✉️ Surat Keluar</a></li>
                <li><a href="<?= htmlspecialchars($adminBase . '/admins/list') ?>" class="<?= $is_admins ? 'active' : '' ?>">👥 Admin</a></li>
                <li><a href="<?= htmlspecialchars($adminBase . '/logout') ?>">🚪 Logout</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <h1><?= isset($page_title) ? $page_title : 'Dashboard' ?></h1>
                </div>
                <div class="topbar-right">
                    <div class="user-info">
                        <span>👤 <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
                    </div>
                    <button id="sidebarToggle" aria-label="Toggle sidebar">☰</button>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content">
