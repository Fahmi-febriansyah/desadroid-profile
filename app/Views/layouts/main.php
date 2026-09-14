<?php
/**
 * Master Layout for Desadroid
 * Variables available:
 * - $content: rendered view content
 * - $pageTitle, $metaDescription, $metaKeywords, $metaImage, $canonical, $metaRobots, $ogType
 * - $baseDir, $baseDirUrl, $csrf_token
 */

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if (empty($pageTitle)) {
    $pageTitle = 'Desadroid — IT Consultant & Jasa Pembuatan Website Bogor';
}

if (empty($metaDescription)) {
    $metaDescription = 'Desadroid — IT consultant dan agensi pembuatan website modern terpercaya di Bogor. Merancang website berkelas tinggi, aplikasi mobile, dan solusi software scalable.';
}

if (empty($metaKeywords)) {
    $metaKeywords = 'it consultant bogor, jasa pembuatan web bogor, konsultan it bogor, web developer bogor, jasa website bogor, software house bogor, pembuatan aplikasi bogor, desadroid';
}

if (empty($canonical)) {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $canonical = $scheme . '://' . $host . $requestUri;
}

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

$robotsContent = $metaRobots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
$rootPath = dirname(__DIR__, 2);
$cssPath = $rootPath . '/src/css/style.css';
$cssVersion = file_exists($cssPath) ? filemtime($cssPath) : '1.0';
$jsPath = $rootPath . '/src/js/main.js';
$jsVersion = file_exists($jsPath) ? filemtime($jsPath) : '1.0';
$iconPath = htmlspecialchars(($baseDirUrl ?: '') . '/src/icon/icon.png');
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
    <meta name="robots" content="<?= htmlspecialchars($robotsContent) ?>">
    <meta name="googlebot" content="<?= htmlspecialchars($robotsContent) ?>">
    <meta name="bingbot" content="<?= htmlspecialchars($robotsContent) ?>">

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
    <link rel="icon" type="image/png" href="<?= $iconPath ?>">
    <link rel="shortcut icon" href="<?= $iconPath ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= $iconPath ?>">

    <!-- Performance: Preconnect & Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//embed.tawk.to">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- CSS Stylesheet -->
    <link rel="stylesheet" href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/src/css/style.css?v=' . $cssVersion) ?>">

    <!-- Structured Data: Schema.org JSON-LD -->
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
          "openingHoursSpecification": [
            {
              "@type": "OpeningHoursSpecification",
              "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
              "opens": "09:00",
              "closes": "18:00"
            }
          ],
          "sameAs": [
            "https://github.com/Fahmi-febriansyah",
            "https://www.linkedin.com/in/fahmifebriansyah/",
            "https://www.instagram.com/desadroiditconsultant/"
          ],
          "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Layanan IT & Web Development Desadroid Bogor",
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
    <!-- Navbar Header -->
    <header class="navbar">
        <div class="container">
            <div class="logo"><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/') ?>">desadroid</a></div>
            <nav class="nav-links">
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/#home') ?>">Beranda</a>
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/tentang') ?>">Tentang</a>
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>">Layanan</a>
                <a href="https://project.desadroid.shop" target="_blank" rel="noopener">Proyek</a>
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/artikel') ?>">Artikel</a>
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/kontak') ?>">Kontak</a>
            </nav>
            <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/kontak') ?>" class="btn primary hire-btn">Hubungi Kami</a>
            <button class="menu-toggle" aria-label="Buka menu"><span></span><span></span><span></span></button>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer" data-reveal>
        <div class="footer-glow"></div>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <div class="logo mb-2" style="font-size: 1.5rem;"><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/') ?>">desadroid</a></div>
                    <p>Studio Teknologi &amp; IT Consultant profesional berbasis di Bogor. Kami merancang website modern, aplikasi mobile berkinerja tinggi, dan sistem kustom yang siap mengakselerasi skala bisnis Anda.</p>
                    <div class="social">
                        <a href="https://github.com/Fahmi-febriansyah" aria-label="GitHub" class="social-link" target="_blank" rel="noopener">GitHub</a>
                        <a href="https://www.linkedin.com/in/fahmifebriansyah/" aria-label="LinkedIn" class="social-link" target="_blank" rel="noopener">LinkedIn</a>
                        <a href="https://www.instagram.com/desadroiditconsultant/" aria-label="Instagram" class="social-link" target="_blank" rel="noopener">Instagram</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h5>Perusahaan</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/tentang') ?>">Tentang Kami</a></li>
                        <li><a href="https://project.desadroid.shop" target="_blank" rel="noopener">Portofolio Proyek</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/artikel') ?>">Blog &amp; Insight</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/kontak') ?>">Hubungi Kami</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/privacy') ?>">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Layanan Unggulan</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>">Jasa Pembuatan Website</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>">Pengembangan Aplikasi Mobile</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>">Desain UI/UX &amp; Prototyping</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>">Konsultasi IT &amp; Cloud Architecture</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Lokasi &amp; Kontak</h5>
                    <ul style="color: var(--text2); font-size: 0.92rem; line-height: 1.6;">
                        <li style="margin-bottom: 0.5rem;"><strong style="color: var(--text);">Kantor:</strong> Cikeas Udik, Kabupaten Bogor, Jawa Barat</li>
                        <li style="margin-bottom: 0.5rem;"><strong style="color: var(--text);">Email:</strong> <a href="mailto:consulting@desadroid.shop">consulting@desadroid.shop</a></li>
                        <li style="margin-bottom: 0.5rem;"><strong style="color: var(--text);">WhatsApp:</strong> <a href="https://wa.me/6289669709021" target="_blank" rel="noopener">+62 896 6970 9021</a></li>
                        <li><strong style="color: var(--text);">Jam Kerja:</strong> Sen - Jum 09:00 - 18:00 WIB</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; 2026 Desadroid. All rights reserved.</div>
                <div>Mitra Inovasi Digital &amp; IT Consultant Bogor, Jawa Barat</div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= htmlspecialchars(($baseDirUrl ?: '') . '/src/js/main.js?v=' . $jsVersion) ?>"></script>

    <!-- Tawk.to Live Chat -->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/69b25f11f2d5f91c395012be/1jjgc6o5n';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
</body>
</html>
