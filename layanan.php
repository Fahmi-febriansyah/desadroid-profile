<?php
require_once 'config/db.php';
$pageTitle = 'Layanan IT & Jasa Pembuatan Web Bogor — Desadroid';
$metaDescription = 'Solusi teknologi terpadu dari Desadroid di Bogor: Jasa Pembuatan Website, Aplikasi Mobile, UI/UX Design, Arsitektur Sistem, dan Konsultasi IT Profesional.';
$metaKeywords = 'layanan it bogor, jasa pembuatan web bogor, it consultant bogor, bikin website bogor, developer aplikasi bogor, web developer bogor';
$metaImage = 'src/img/DESADROID.jpg';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
if ($baseDir === '/') $baseDir = ''; 
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; }); 
$baseDirUrl = ''; 
if (!empty($baseDirSegments)) { 
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments)); 
} 

include 'partials/header.php'; 

try {
    $services_query = $pdo->query('SELECT * FROM services ORDER BY id ASC');
    $services = $services_query->fetchAll();
} catch (Exception $e) {
    $services = [];
}

$serviceFeatures = [
    ['Desain Responsif & Modern', 'SEO On-Page & Kecepatan Tinggi', 'Panel Admin / CMS Kustom'],
    ['Android & iOS (Flutter / Native)', 'UI Interaktif & Performa Mulus', 'Integrasi API & Push Notifikasi'],
    ['Riset UX & User Persona', 'Figma Design System Komprehensif', 'Prototipe Interaktif Siap Uji'],
    ['RESTful API Berkeamanan Ketat', 'Database Scalable & Teroptimasi', 'Proteksi CSRF, XSS & SQL Injection'],
    ['Integrasi Payment Gateway Aman', 'Sistem Order & Manajemen Stok', 'Fitur Diskon, Voucher & Laporan'],
    ['Audit Sistem & Analisis Keamanan', 'Roadmap Arsitektur Teknologi', 'Rekomendasi Efisiensi Server Cloud']
];
?>

<!-- Services Hero -->
<section class="page-hero text-center" data-reveal>
    <div class="container">
        <div class="hero-badge-tag">
            <span class="hero-badge-dot"></span>
            <span>Keahlian &amp; Solusi Teknologi</span>
        </div>
        <h1>Solusi Digital Terpadu untuk <span class="gradient-text">Akselerasi Bisnis</span> Anda</h1>
        <p class="hero-subtitle mx-auto">Kami menggabungkan ketelitian rekayasa piranti lunak dan desain visual berkelas dunia untuk menghasilkan produk digital yang cepat, aman, dan mendatangkan return nyata.</p>
    </div>
</section>

<!-- Services Grid Section -->
<section class="services-list bg-light" data-reveal>
    <div class="container">
        <div class="service-grid-clean">
            <?php if (!empty($services)): ?>
                <?php 
                $svgs = [
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>'
                ];
                foreach ($services as $index => $service): 
                    $icon = $svgs[$index % count($svgs)];
                    $pills = $serviceFeatures[$index] ?? ['Kualitas Kode Teruji', 'Dukungan Berkelanjutan', 'Konsultasi Intensif'];
                ?>
                <div class="service-card" data-reveal>
                    <div class="icon-box">
                        <?= $icon ?>
                    </div>
                    <h3><?= htmlspecialchars($service['name']) ?></h3>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                    
                    <ul class="service-features">
                        <?php foreach ($pills as $pill): ?>
                        <li>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span><?= htmlspecialchars($pill) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                        <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="service-link-cta">
                            <span>Konsultasikan Kebutuhan Ini</span>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1 / -1;">Belum ada layanan yang ditambahkan.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Workflow / Proses Kerja -->
<section class="workflow-home" data-reveal>
    <div class="container">
        <div class="section-title text-center">
            <h2>Metodologi &amp; Alur Kerja Terstruktur</h2>
            <p>Standar kerja transparan yang menjamin setiap fase proyek selesai tepat waktu dan berstandar enterprise.</p>
        </div>
        
        <div class="workflow-cards-grid">
            <div class="process-card-item" data-reveal>
                <span class="process-step-num">01</span>
                <h3>Discovery &amp; Strategy</h3>
                <p>Kami membedah visi bisnis, menganalisis audiens target, dan merumuskan spesifikasi teknis paling efisien.</p>
            </div>
            <div class="process-card-item" data-reveal>
                <span class="process-step-num">02</span>
                <h3>UI/UX &amp; Architecture</h3>
                <p>Menyusun wireframe, design system, dan blueprint arsitektur sistem sebelum implementasi kode dimulai.</p>
            </div>
            <div class="process-card-item" data-reveal>
                <span class="process-step-num">03</span>
                <h3>Agile Development</h3>
                <p>Pengembangan kode bersih dengan pengujian berkala, memastikan performa tinggi dan keamanan tanpa celah.</p>
            </div>
            <div class="process-card-item" data-reveal>
                <span class="process-step-num">04</span>
                <h3>Deployment &amp; Growth</h3>
                <p>Peluncuran sistem ke server produksi disertai monitoring berkala, panduan penggunaan, dan garansi pemeliharaan.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="about-cta bg-light" data-reveal>
    <div class="container text-center">
        <h2>Punya Rencana Proyek Digital yang Ingin Diwujudkan?</h2>
        <p class="mb-4 text-muted mx-auto" style="max-width: 540px;">Kami siap memberikan konsultasi gratis dan rancangan estimasi biaya yang transparan untuk kebutuhan bisnis Anda.</p>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn primary">
                <span>Mulai Konsultasi Gratis</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
            <a href="https://wa.me/6289669709021" target="_blank" class="btn secondary">
                <span>Chat via WhatsApp</span>
            </a>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
