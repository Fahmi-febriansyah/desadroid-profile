<?php
// Dynamic header: accepts optional $pageTitle and $canonical variables.
// If not provided, build reasonable defaults from the current request.

// Compute request base URL and base directory for assets/links
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';

// Build URL-encoded base path for use in hrefs (handles spaces and special chars)
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; });
$baseDirUrl = '';
if (!empty($baseDirSegments)) {
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments));
}
// Default title
if (empty($pageTitle)) {
    $pageTitle = 'Desadroid - Keunggulan Digital';
}

// Canonical: prefer explicitly set $canonical, else build from request.
if (empty($canonical)) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $canonical = $scheme . '://' . $host . $requestUri;
}

// Meta description and image (optional)
if (empty($metaDescription)) {
    $metaDescription = 'Desadroid — studio produksi digital, layanan pengembangan web, mobile, dan desain UI/UX.';
}

// Compute absolute meta image if provided
$fullMetaImage = '';
if (!empty($metaImage)) {
    if (strpos($metaImage, 'http') === 0) {
        $fullMetaImage = $metaImage;
    } else {
        $fullMetaImage = $scheme . '://' . $host . $baseDir . '/' . ltrim($metaImage, '/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $iconPath = htmlspecialchars($baseDir . '/src/icon/icon.png'); ?>
    <link rel="icon" type="image/png" href="<?= $iconPath ?>">
    <link rel="shortcut icon" href="<?= $iconPath ?>" type="image/png">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php if (!empty($fullMetaImage)): ?>
    <meta property="og:image" content="<?= htmlspecialchars($fullMetaImage) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php if (!empty($fullMetaImage)): ?>
    <meta name="twitter:image" content="<?= htmlspecialchars($fullMetaImage) ?>">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <?php $cssVersion = file_exists(dirname(__DIR__) . '/src/css/style.css') ? filemtime(dirname(__DIR__) . '/src/css/style.css') : '1.0'; ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(($baseDirUrl === '' ? '' : $baseDirUrl) . '/src/css/style.css?v=' . $cssVersion) ?>">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo"><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/' : $baseDirUrl . '/')) ?>">desadroid</a></div>
            <nav class="nav-links">
                <?php $homeHref = $baseDir === '' ? '#home' : ($baseDirUrl . '/#home'); ?>
                <a href="<?= htmlspecialchars($homeHref) ?>">Beranda</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/tentang' : $baseDirUrl . '/tentang')) ?>">Tentang</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Layanan</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/proyek' : $baseDirUrl . '/proyek')) ?>">Proyek</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/artikel' : $baseDirUrl . '/artikel')) ?>">Artikel</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>">Kontak</a>
            </nav>
            <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn primary hire-btn">Hubungi Kami</a>
            <button class="menu-toggle" aria-label="Buka menu"><span></span><span></span><span></span></button>
        </div>
    </header>
