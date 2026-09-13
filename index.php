<?php
require_once 'config/db.php';

// Generate CSRF token for contact form if not present
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
$form_flash = $_SESSION['contact_flash'] ?? null;
unset($_SESSION['contact_flash']);

// Get projects from database
try {
    $projects_query = $pdo->query('SELECT * FROM projects ORDER BY order_num ASC, created_at DESC LIMIT 3');
    $projects = $projects_query->fetchAll();
} catch (Exception $e) {
    $projects = [];
}

// Get articles from database
try {
    $articles_query = $pdo->query('SELECT * FROM articles WHERE status = "published" ORDER BY published_date DESC LIMIT 3');
    $articles = $articles_query->fetchAll();
} catch (Exception $e) {
    $articles = [];
}

// Get services from database
try {
    $services_query = $pdo->query('SELECT * FROM services ORDER BY id ASC LIMIT 6');
    $services = $services_query->fetchAll();
} catch (Exception $e) {
    $services = [];
}
?>
<?php
// Build canonical for homepage
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$canonical = $scheme . '://' . $host . $baseDir . '/';

$pageTitle = 'Desadroid - Konsultan IT & Transformasi Digital Terpercaya';
$metaDescription = 'Desadroid menawarkan layanan IT profesional termasuk pembuatan website, aplikasi mobile, desain UI/UX, dan pengembangan sistem untuk bisnis Anda.';
$metaImage = 'src/img/DESADROID.jpg';
include 'partials/header.php';
?>

    <!-- Hero Section -->
    <section id="home" class="hero-clean" data-reveal>
        <div class="container hero-split">
            <div class="hero-text">
                <div class="hero-badge-tag">
                    <span class="hero-badge-dot"></span>
                    <span>Tentang Desadroid · Inovasi Digital Sejak 2025</span>
                </div>
                <h1>Membangun <span class="gradient-text">Ekosistem Digital</span> yang Berdampak Nyata</h1>
                <p>Sejak 2025, kami membantu perusahaan dan pelaku bisnis mengubah ide brilian menjadi produk digital yang kuat, andal, dan scalable. Kami bukan sekadar agensi, melainkan mitra inovasi teknologi Anda.</p>
                <div class="hero-actions">
                    <a href="#contact" class="btn primary">
                        <span>Konsultasi Proyek Gratis</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <a href="#projects" class="btn secondary">
                        <span>Lihat Portofolio</span>
                    </a>
                    <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/tentang' : $baseDirUrl . '/tentang')) ?>" class="btn tertiary">
                        <span>Profil Kami</span>
                    </a>
                </div>
                
                <!-- Quick Trust Metrics Bar (from tentang.php: 100%, 15+, 2025) -->
                <div class="hero-trust-bar">
                    <div class="trust-item">
                        <strong>100<span>%</span></strong>
                        <span class="trust-label">Kepuasan Klien</span>
                    </div>
                    <div class="trust-item">
                        <strong>15<span>+</span></strong>
                        <span class="trust-label">Proyek Sukses</span>
                    </div>
                    <div class="trust-item">
                        <strong>2025</strong>
                        <span class="trust-label">Tahun Berdiri</span>
                    </div>
                </div>
            </div>

            <!-- Hero Image: Photo from Tentang (DESADROID.jpg) with Quality Badge -->
            <div class="hero-image">
                <?php $imgSrc = (!empty($baseDirUrl) ? $baseDirUrl . '/src/img/DESADROID.jpg' : '/src/img/DESADROID.jpg'); ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Tim Desadroid" class="main-img">
                <div class="story-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    <div class="card-text">
                        <strong>Fokus pada Kualitas</strong>
                        <span>Kode bersih & Desain intuitif</span>
                    </div>
                </div>
                <div class="hero-float-badge hero-float-badge-top">
                    <div class="float-badge-icon badge-icon-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="float-badge-text">
                        <strong>15+ Solusi Sukses</strong>
                        <span>Kemitraan Jangka Panjang</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Strip -->
    <section class="tech-strip">
        <div class="container">
            <div class="tech-strip-inner">
                <span class="tech-strip-label">Teknologi Modern yang Kami Kuasai</span>
                <div class="tech-badges-list">
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                        PHP 8 / Laravel
                    </span>
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="m10 15 5-3-5-3v6Z"></path></svg>
                        React & Vue.js
                    </span>
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        Flutter / Mobile Apps
                    </span>
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14.5h-2V13h2zm0-4.5h-2V7h2z"></path></svg>
                        Python & Integrasi AI
                    </span>
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        MySQL & Database
                    </span>
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        Figma & UI/UX Design
                    </span>
                    <span class="tech-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        Cloud & Web Security
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-us-section" data-reveal>
        <div class="container">
            <div class="section-title">
                <h2>Mengapa Memilih Desadroid?</h2>
                <p>Standar kualitas tinggi dan dedikasi penuh di setiap fase pengembangan digital Anda.</p>
            </div>
            
            <div class="why-us-grid">
                <div class="why-us-card" data-reveal>
                    <div class="why-us-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    </div>
                    <h3>Cepat & Skalabel</h3>
                    <p>Arsitektur sistem dibangun dengan fondasi kokoh dan kode bersih yang siap menampung pertumbuhan pesat pengguna dan data bisnis Anda.</p>
                </div>
                
                <div class="why-us-card" data-reveal>
                    <div class="why-us-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <h3>Keamanan Berlapis</h3>
                    <p>Kami menerapkan prinsip security-first di setiap baris kode, mencakup mitigasi CSRF, SQL Injection, XSS, dan enkripsi data sensitif.</p>
                </div>

                <div class="why-us-card" data-reveal>
                    <div class="why-us-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </div>
                    <h3>UI/UX Berkelas & Intuitif</h3>
                    <p>Desain visual yang memikat mata dan ramah pengguna, dirancang khusus untuk menciptakan pengalaman terbaik dan meningkatkan konversi.</p>
                </div>

                <div class="why-us-card" data-reveal>
                    <div class="why-us-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <h3>Garansi & Kemitraan</h3>
                    <p>Kami mendampingi proses bisnis Anda pasca-peluncuran dengan jaminan garansi bug, pemeliharaan sistem, dan konsultasi berkala.</p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 2.5rem;" data-reveal>
                <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/tentang' : $baseDirUrl . '/tentang')) ?>" class="btn secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span>Pelajari Visi & Profil Desadroid Selengkapnya</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>
        </div>
    </section>


    <!-- Services Section -->
    <section id="services" class="services-clean bg-light" data-reveal>
        <div class="container">
            <div class="section-title">
                <h2>Layanan Profesional Kami</h2>
                <p>Solusi teknologi end-to-end yang disesuaikan secara presisi dengan kebutuhan bisnis Anda.</p>
            </div>
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
                    $featurePills = [
                        ['Responsif & Cepat', 'SEO & Analitik Terintegrasi', 'Panel Admin Kustom'],
                        ['Android & iOS Native/Hybrid', 'Performa Mulus', 'Integrasi Notifikasi & API'],
                        ['Wireframe & Prototyping', 'Design System Khusus', 'Fokus Pengalaman Pengguna'],
                        ['Arsitektur Database Kokoh', 'RESTful API Aman', 'Kecepatan & Skalabilitas'],
                        ['Integrasi Payment Gateway', 'Manajemen Stok & Pesanan', 'Sistem Checkout Aman'],
                        ['Audit Sistem & Keamanan', 'Perencanaan Arsitektur IT', 'Konsultasi Solusi Digital']
                    ];
                    foreach ($services as $index => $service): 
                        $icon = $svgs[$index % count($svgs)];
                        $feats = $featurePills[$index % count($featurePills)];
                    ?>
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <?= $icon ?>
                        </div>
                        <span class="service-card-tag">Solusi IT</span>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <ul class="service-features">
                            <?php foreach ($feats as $feat): ?>
                            <li>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span><?= htmlspecialchars($feat) ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default Services -->
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                        <span class="service-card-tag">Web Solution</span>
                        <h3>Pengembangan Web</h3>
                        <p>Pembuatan website responsif, cepat, dan modern yang dirancang khusus untuk memenuhi kebutuhan bisnis Anda.</p>
                        <ul class="service-features">
                            <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Responsif & Mobile First</span></li>
                            <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Optimasi SEO & Kecepatan</span></li>
                        </ul>
                    </div>
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        </div>
                        <span class="service-card-tag">Mobile Solution</span>
                        <h3>Aplikasi Mobile</h3>
                        <p>Pengembangan aplikasi native dan hybrid berkualitas tinggi untuk platform iOS dan Android.</p>
                        <ul class="service-features">
                            <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Cross-Platform Flutter/Native</span></li>
                            <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Performa Mulus & Handal</span></li>
                        </ul>
                    </div>
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </div>
                        <span class="service-card-tag">UI/UX Experience</span>
                        <h3>Desain UI/UX</h3>
                        <p>Merancang antarmuka pengguna yang intuitif dan menarik untuk pengalaman digital terbaik.</p>
                        <ul class="service-features">
                            <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Prototipe Interaktif Figma</span></li>
                            <li><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Riset & User Journey</span></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="home-projects-section" data-reveal>
        <div class="container">
            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Portofolio <span>Unggulan</span></h2>
                    <p class="home-section-sub">Produk dan sistem digital nyata yang telah kami bangun dengan standar rekayasa tertinggi.</p>
                </div>
                <a href="https://project.desadroid.shop" target="_blank" class="home-view-all">
                    <span>Lihat Semua Proyek</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <?php if (!empty($projects)): ?>
            <div class="plist-grid">
                <?php foreach ($projects as $project): ?>
                <?php
                    $imgUrl = !empty($project['image_url']) ? $project['image_url'] : 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=600&q=80';
                    $progress = isset($project['progress']) ? intval($project['progress']) : 0;
                    $statusClass = $progress >= 100 ? 'done' : ($progress > 0 ? 'progress' : 'plan');
                    $statusLabel = $progress >= 100 ? 'Selesai' : ($progress > 0 ? 'Berjalan' : 'Direncanakan');
                    $detailUrl = 'https://project.desadroid.shop/project/' . rawurlencode($project['slug']);
                ?>
                <div class="plist-card" data-reveal>
                    <a href="<?= htmlspecialchars($detailUrl) ?>" target="_blank" class="plist-card-thumb">
                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                        <span class="plist-card-cat"><?= htmlspecialchars($project['category']) ?></span>
                        <span class="plist-card-status <?= $statusClass ?>"><?= $statusLabel ?></span>
                    </a>
                    <div class="plist-card-body">
                        <h3 class="plist-card-title"><a href="<?= htmlspecialchars($detailUrl) ?>" target="_blank"><?= htmlspecialchars($project['title']) ?></a></h3>
                        <p class="plist-card-desc"><?= htmlspecialchars(mb_strimwidth(strip_tags($project['description'] ?? ''), 0, 95, '…')) ?></p>
                        <div class="plist-progress">
                            <div class="plist-progress-head"><span>Progress Pengerjaan</span><strong><?= $progress ?>%</strong></div>
                            <div class="plist-progress-bar"><div class="plist-progress-fill" style="width:<?= $progress ?>%"></div></div>
                        </div>
                        <div class="plist-card-footer">
                            <a href="<?= htmlspecialchars($detailUrl) ?>" target="_blank" class="plist-btn-detail">
                                <span>Lihat Detail</span>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                            <?php if (!empty($project['link'])): ?>
                            <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank" class="plist-btn-live" title="Kunjungi Website Langsung">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <p>Portofolio proyek sedang diperbarui.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- 4-Step Process Section -->
    <section class="workflow-home" data-reveal>
        <div class="container">
            <div class="section-title">
                <h2>Alur Kerja Kolaboratif</h2>
                <p>Metode pengembangan terstruktur untuk menjamin transparansi dan kesuksesan proyek Anda.</p>
            </div>

            <div class="workflow-cards-grid">
                <div class="process-card-item" data-reveal>
                    <span class="process-step-num">01</span>
                    <h3>Discovery & Analisis</h3>
                    <p>Kami mendengarkan visi bisnis Anda, menganalisis tantangan, dan menyusun spesifikasi teknis yang tepat sasaran.</p>
                </div>

                <div class="process-card-item" data-reveal>
                    <span class="process-step-num">02</span>
                    <h3>Desain & Arsitektur</h3>
                    <p>Merancang alur pengguna (UI/UX), prototipe interaktif, dan arsitektur database yang aman serta efisien.</p>
                </div>

                <div class="process-card-item" data-reveal>
                    <span class="process-step-num">03</span>
                    <h3>Development & QC</h3>
                    <p>Pengembangan gesit (agile) dengan sprint terukur, pengujian fungsionalitas, serta audit performa dan keamanan.</p>
                </div>

                <div class="process-card-item" data-reveal>
                    <span class="process-step-num">04</span>
                    <h3>Peluncuran & Garansi</h3>
                    <p>Deployment ke server produksi, dokumentasi lengkap, pelatihan tim, serta dukungan pemeliharaan berkelanjutan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Section -->
    <section id="articles" class="home-articles-section" data-reveal>
        <div class="container">
            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Artikel & <span>Wawasan</span></h2>
                    <p class="home-section-sub">Eksplorasi tren teknologi, arsitektur software, dan strategi transformasi digital.</p>
                </div>
                <a href="<?= htmlspecialchars($baseDir . '/artikel') ?>" class="home-view-all">
                    <span>Semua Artikel</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
            <?php if (!empty($articles)): ?>
            <div class="alist-grid">
                <?php foreach ($articles as $article): ?>
                <?php
                    $articleUrl = $baseDir . '/artikel/' . rawurlencode($article['slug']);
                    $aImg = !empty($article['featured_image'])
                          ? (preg_match('/^https?:\/\//', $article['featured_image']) ? $article['featured_image'] : $baseDir . '/' . ltrim($article['featured_image'], '/'))
                          : 'https://images.unsplash.com/photo-1542435503-ec7b0f197a62?w=600&q=80';
                ?>
                <article class="alist-card" data-reveal>
                    <a href="<?= htmlspecialchars($articleUrl) ?>" class="alist-card-thumb">
                        <img src="<?= htmlspecialchars($aImg) ?>" alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
                        <span class="alist-card-cat"><?= htmlspecialchars($article['category']) ?></span>
                    </a>
                    <div class="alist-card-body">
                        <div class="alist-card-meta">
                            <span><?= date('d M Y', strtotime($article['published_date'])) ?></span>
                            <span class="dot">·</span>
                            <span><?= htmlspecialchars($article['read_time'] ?? '5') ?> mnt baca</span>
                        </div>
                        <h3 class="alist-card-title"><a href="<?= htmlspecialchars($articleUrl) ?>"><?= htmlspecialchars($article['title']) ?></a></h3>
                        <p class="alist-card-desc"><?= htmlspecialchars(mb_strimwidth($article['excerpt'] ?? '', 0, 95, '…')) ?></p>
                        <a href="<?= htmlspecialchars($articleUrl) ?>" class="alist-card-link">
                            <span>Baca Selengkapnya</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <p>Artikel dan wawasan teknologi segera hadir.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FAQ Accordion Section -->
    <section class="faq-section bg-light" data-reveal>
        <div class="container">
            <div class="section-title">
                <h2>Pertanyaan yang Sering Diajukan</h2>
                <p>Segala hal yang perlu Anda ketahui sebelum memulai kolaborasi proyek bersama kami.</p>
            </div>

            <div class="faq-wrap">
                <div class="faq-item active">
                    <button class="faq-question" type="button">
                        <span>Berapa lama estimasi waktu pengerjaan sebuah proyek?</span>
                        <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Durasi pengerjaan bergantung pada kompleksitas sistem. Website profil bisnis biasanya memakan waktu 1-2 minggu, sedangkan sistem informasi terintegrasi atau aplikasi kustom membutuhkan 3-8 minggu dengan milestone progress berkala.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <span>Apakah sistem yang dibuat dapat disesuaikan (custom) dengan alur bisnis kami?</span>
                        <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Tentu saja. Desadroid fokus pada solusi kustom (bespoke software). Kami merancang alur kerja, struktur basis data, dan antarmuka secara spesifik sesuai operasional dan tujuan bisnis Anda.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <span>Bagaimana standar keamanan aplikasi yang dibangun oleh Desadroid?</span>
                        <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Keamanan adalah prioritas utama kami. Kami menerapkan standar OWASP, proteksi serangan CSRF dan XSS, enkripsi data sensitif (SSL/TLS), sanitasi parameter database (anti-SQL Injection), serta konfigurasi server yang kokoh.
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <span>Apakah ada garansi pemeliharaan setelah aplikasi diluncurkan?</span>
                        <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="faq-answer">
                        Ya, kami memberikan jaminan garansi bug-free serta pendampingan teknis gratis pasca-peluncuran, disertai opsi layanan maintenance berkala untuk pembaruan fitur dan monitoring sistem.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Secured Contact Section -->
    <section id="contact" class="contact-clean" data-reveal>
        <div class="container contact-split">
            <div class="contact-text">
                <h2>Siap Mengembangkan Bisnis Anda?</h2>
                <p>Konsultasikan kebutuhan digital Anda dengan tim kami. Kami siap mendengarkan rencana proyek Anda dan memberikan solusi teknologi terbaik yang efisien dan bernilai tinggi.</p>
                
                <div class="contact-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div>
                            <h4>Email Resmi</h4>
                            <p>consulting@desadroid.shop</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <h4>Telepon / WhatsApp</h4>
                            <p>+62 896 6970 9021</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <h4>Lokasi Kantor</h4>
                            <p>Cikeas Udik, Kabupaten Bogor, Jawa Barat</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-card">
                <?php if ($form_flash): ?>
                <div class="form-alert <?= $form_flash['type'] === 'success' ? 'form-alert-success' : 'form-alert-error' ?>">
                    <div class="form-alert-icon">
                        <?php if ($form_flash['type'] === 'success'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <?php else: ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($form_flash['title'] ?? '') ?></strong>
                        <p><?= htmlspecialchars($form_flash['message'] ?? '') ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <form method="post" action="<?= htmlspecialchars($baseDir . '/send_message.php') ?>">
                    <!-- CSRF Security Token -->
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <!-- Form Timestamp for Bot Time-gate -->
                    <input type="hidden" name="form_load_time" value="<?= time() ?>">
                    <!-- Honeypot Anti-Spam Trap Field (Invisible to real users) -->
                    <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                        <label for="website_hp">Security Check</label>
                        <input type="text" id="website_hp" name="website_hp" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" placeholder="nama@email.com" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon / WhatsApp (Opsional)</label>
                        <input type="text" id="phone" name="phone" placeholder="Contoh: 081234567890" maxlength="25">
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan / Kebutuhan Proyek</label>
                        <textarea id="message" name="message" rows="4" placeholder="Ceritakan secara singkat ide atau kebutuhan sistem Anda" required maxlength="3000"></textarea>
                    </div>

                    <div class="form-security-badge">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Enkripsi SSL 256-bit & Proteksi Anti-Spam CSRF Aktif</span>
                    </div>

                    <button type="submit" class="btn primary btn-submit">
                        <span>Kirim Pesan Sekarang</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

<?php include 'partials/footer.php'; ?>
