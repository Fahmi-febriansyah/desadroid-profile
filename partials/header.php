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
    $metaDescription = 'Desadroid — IT consultant dan agensi pembuatan website modern terpercaya di Bogor. Merancang website berkelas tinggi, aplikasi mobile, dan solusi software scalable.';
}

// Meta keywords (targeting IT Consultant Bogor & Jasa Pembuatan Web Bogor)
if (empty($metaKeywords)) {
    $metaKeywords = 'it consultant bogor, jasa pembuatan web bogor, konsultan it bogor, web developer bogor, jasa website bogor, software house bogor, pembuatan aplikasi bogor, desadroid';
}

// Compute absolute meta image if provided
$fullMetaImage = '';
if (!empty($metaImage)) {
    if (strpos($metaImage, 'http') === 0) {
        $fullMetaImage = $metaImage;
    } else {
        $fullMetaImage = $scheme . '://' . $host . $baseDir . '/' . ltrim($metaImage, '/');
    }
} else {
    $fullMetaImage = $scheme . '://' . $host . $baseDir . '/src/img/DESADROID.jpg';
}
?>
<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Primary SEO Meta Tags -->
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($metaKeywords) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">

    <!-- Search Bot Directives -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- Regional & Local SEO Tags (Bogor, Jawa Barat) -->
    <meta name="geo.region" content="ID-JB">
    <meta name="geo.placename" content="Bogor">
    <meta name="geo.position" content="-6.38738;106.93634">
    <meta name="ICBM" content="-6.38738, 106.93634">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="Desadroid">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical) ?>">
    <meta property="og:type" content="<?= htmlspecialchars($ogType ?? 'website') ?>">
    <meta property="og:locale" content="id_ID">
    <?php if (!empty($fullMetaImage)): ?>
    <meta property="og:image" content="<?= htmlspecialchars($fullMetaImage) ?>">
    <?php if (strpos($fullMetaImage, 'https') === 0): ?>
    <meta property="og:image:secure_url" content="<?= htmlspecialchars($fullMetaImage) ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription) ?>">
    <?php if (!empty($fullMetaImage)): ?>
    <meta name="twitter:image" content="<?= htmlspecialchars($fullMetaImage) ?>">
    <?php endif; ?>

    <!-- Favicons -->
    <?php $iconPath = htmlspecialchars($baseDir . '/src/icon/icon.png'); ?>
    <link rel="icon" type="image/png" href="<?= $iconPath ?>">
    <link rel="shortcut icon" href="<?= $iconPath ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= $iconPath ?>">

    <!-- Performance: Preconnect & Preload Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//embed.tawk.to">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- CSS Stylesheet with Cache Busting -->
    <?php $cssVersion = file_exists(dirname(__DIR__) . '/src/css/style.css') ? filemtime(dirname(__DIR__) . '/src/css/style.css') : '1.0'; ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(($baseDirUrl === '' ? '' : $baseDirUrl) . '/src/css/style.css?v=' . $cssVersion) ?>">

    <!-- Structured Data: Schema.org JSON-LD (LocalBusiness, IT Consultant, Web Development Bogor) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": ["ProfessionalService", "LocalBusiness", "Organization"],
          "@id": "https://desadroid.shop/#organization",
          "name": "Desadroid",
          "alternateName": [
            "Desadroid IT Consultant",
            "Desadroid IT Consultant Bogor",
            "Desadroid Web Development Bogor"
          ],
          "url": "https://desadroid.shop/",
          "logo": "https://desadroid.shop/src/icon/icon.png",
          "image": "https://desadroid.shop/src/img/DESADROID.jpg",
          "description": "Konsultan IT profesional dan penyedia jasa pembuatan website modern, aplikasi mobile, serta arsitektur sistem digital scalable di Bogor.",
          "telephone": "+6289669709021",
          "email": "consulting@desadroid.shop",
          "priceRange": "$$",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Cikeas Udik",
            "addressLocality": "Bogor",
            "addressRegion": "Jawa Barat",
            "postalCode": "16966",
            "addressCountry": "ID"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": -6.38738,
            "longitude": 106.93634
          },
          "areaServed": [
            {
              "@type": "City",
              "name": "Bogor"
            },
            {
              "@type": "AdministrativeArea",
              "name": "Kabupaten Bogor"
            },
            {
              "@type": "AdministrativeArea",
              "name": "Kota Bogor"
            },
            {
              "@type": "AdministrativeArea",
              "name": "Jabodetabek"
            },
            {
              "@type": "Country",
              "name": "Indonesia"
            }
          ],
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "09:00",
            "closes": "18:00"
          },
          "sameAs": [
            "https://github.com/Fahmi-febriansyah",
            "https://www.linkedin.com/in/fahmifebriansyah/",
            "https://www.instagram.com/desadroiditconsultant/",
            "https://project.desadroid.shop"
          ],
          "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Layanan IT & Digital Desadroid",
            "itemListElement": [
              {
                "@type": "Offer",
                "itemOffered": {
                  "@type": "Service",
                  "name": "IT Consultant Bogor",
                  "description": "Layanan konsultasi arsitektur IT, audit sistem, dan perencanaan transformasi digital bisnis terpercaya di Bogor."
                }
              },
              {
                "@type": "Offer",
                "itemOffered": {
                  "@type": "Service",
                  "name": "Jasa Pembuatan Web Bogor",
                  "description": "Jasa pembuatan website modern, company profile, web app scalable, dan sistem e-commerce responsif di Bogor."
                }
              },
              {
                "@type": "Offer",
                "itemOffered": {
                  "@type": "Service",
                  "name": "Pengembangan Aplikasi Mobile",
                  "description": "Pembuatan aplikasi Android dan iOS native maupun cross-platform dengan kinerja optimal dan UX intuitif."
                }
              },
              {
                "@type": "Offer",
                "itemOffered": {
                  "@type": "Service",
                  "name": "UI/UX & Desain Sistem Digital",
                  "description": "Perancangan antarmuka pengguna (UI/UX) berbasis riset mendalam untuk memaksimalkan konversi bisnis."
                }
              }
            ]
          }
        },
        {
          "@type": "WebSite",
          "@id": "https://desadroid.shop/#website",
          "url": "https://desadroid.shop/",
          "name": "Desadroid",
          "publisher": {
            "@id": "https://desadroid.shop/#organization"
          },
          "inLanguage": "id-ID"
        }
      ]
    }
    </script>
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
                <a href="https://project.desadroid.shop" target="_blank">Proyek</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/artikel' : $baseDirUrl . '/artikel')) ?>">Artikel</a>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>">Kontak</a>
            </nav>
            <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn primary hire-btn">Hubungi Kami</a>
            <button class="menu-toggle" aria-label="Buka menu"><span></span><span></span><span></span></button>
        </div>
    </header>
