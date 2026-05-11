<?php
require_once 'config/db.php';

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

$pageTitle = 'Desadroid - Konsultan & Layanan IT Profesional';
$metaDescription = 'Desadroid menawarkan layanan IT profesional termasuk pembuatan website, aplikasi mobile, desain UI/UX, dan pengembangan sistem untuk bisnis Anda.';
$metaImage = 'src/img/DESADROID.jpg';
include 'partials/header.php';
?>

    <!-- Hero Section -->
    <section id="home" class="hero-clean" data-reveal>
        <div class="container hero-split">
            <div class="hero-text">
                <span class="hero-label">Agensi Digital Kreatif</span>
                <h1>Transformasi Digital untuk <span>Masa Depan</span> Bisnis Anda</h1>
                <p>Kami adalah mitra teknologi yang berdedikasi menciptakan produk digital modern, cepat, dan scalable untuk membantu bisnis Anda berkembang di era digital.</p>
                <div class="hero-actions">
                    <a href="#projects" class="btn primary">Lihat Portofolio</a>
                    <a href="#services" class="btn secondary">Layanan Kami</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" alt="Tim Desadroid Bekerja" class="main-img">
                <div class="experience-badge">
                    <strong>5+</strong> Tahun<br>Pengalaman
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-clean bg-light" data-reveal>
        <div class="container">
            <div class="section-title">
                <h2>Layanan Profesional Kami</h2>
                <p>Solusi teknologi end-to-end yang disesuaikan dengan kebutuhan unik setiap klien.</p>
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
                    foreach ($services as $index => $service): 
                        $icon = $svgs[$index % count($svgs)];
                    ?>
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <?= $icon ?>
                        </div>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Default Services -->
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        </div>
                        <h3>Pengembangan Web</h3>
                        <p>Pembuatan website responsif, cepat, dan modern yang dirancang khusus untuk memenuhi kebutuhan bisnis Anda.</p>
                    </div>
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        </div>
                        <h3>Aplikasi Mobile</h3>
                        <p>Pengembangan aplikasi native dan hybrid berkualitas tinggi untuk platform iOS dan Android.</p>
                    </div>
                    <div class="service-card" data-reveal>
                        <div class="icon-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        </div>
                        <h3>Desain UI/UX</h3>
                        <p>Merancang antarmuka pengguna yang intuitif dan menarik untuk pengalaman digital terbaik.</p>
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
                    <h2 class="home-section-title">Portofolio Kami</h2>
                    <p class="home-section-sub">Solusi digital nyata yang telah kami bangun bersama klien.</p>
                </div>
                <a href="<?= htmlspecialchars($baseDir . '/proyek') ?>" class="home-view-all">Semua Proyek <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
            </div>
            <?php if (!empty($projects)): ?>
            <div class="plist-grid">
                <?php foreach ($projects as $project): ?>
                <?php
                    $imgUrl = !empty($project['image_url']) ? $project['image_url'] : 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=600&q=80';
                    $progress = isset($project['progress']) ? intval($project['progress']) : 0;
                    $statusClass = $progress >= 100 ? 'done' : ($progress > 0 ? 'progress' : 'plan');
                    $statusLabel = $progress >= 100 ? 'Selesai' : ($progress > 0 ? 'Berjalan' : 'Direncanakan');
                    $detailUrl = $baseDir . '/proyek/' . rawurlencode($project['slug']);
                ?>
                <div class="plist-card" data-reveal>
                    <a href="<?= htmlspecialchars($detailUrl) ?>" class="plist-card-thumb">
                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                        <span class="plist-card-cat"><?= htmlspecialchars($project['category']) ?></span>
                        <span class="plist-card-status <?= $statusClass ?>"><?= $statusLabel ?></span>
                    </a>
                    <div class="plist-card-body">
                        <h3 class="plist-card-title"><a href="<?= htmlspecialchars($detailUrl) ?>"><?= htmlspecialchars($project['title']) ?></a></h3>
                        <p class="plist-card-desc"><?= htmlspecialchars(mb_strimwidth(strip_tags($project['description'] ?? ''), 0, 95, '…')) ?></p>
                        <div class="plist-progress">
                            <div class="plist-progress-head"><span>Progress</span><strong><?= $progress ?>%</strong></div>
                            <div class="plist-progress-bar"><div class="plist-progress-fill" style="width:<?= $progress ?>%"></div></div>
                        </div>
                        <div class="plist-card-footer">
                            <a href="<?= htmlspecialchars($detailUrl) ?>" class="plist-btn-detail">Lihat Detail <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                            <?php if (!empty($project['link'])): ?>
                            <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank" class="plist-btn-live" title="Lihat website"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="text-align:center;color:var(--text2);padding:3rem 0;">Portofolio segera hadir.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Articles Section -->
    <section id="articles" class="home-articles-section bg-light" data-reveal>
        <div class="container">
            <div class="home-section-head">
                <div>
                    <h2 class="home-section-title">Artikel & Insight</h2>
                    <p class="home-section-sub">Teknologi, desain, dan tren bisnis digital dari tim kami.</p>
                </div>
                <a href="<?= htmlspecialchars($baseDir . '/artikel') ?>" class="home-view-all">Semua Artikel <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
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
                            <span><?= htmlspecialchars($article['read_time'] ?? '5') ?> mnt</span>
                        </div>
                        <h3 class="alist-card-title"><a href="<?= htmlspecialchars($articleUrl) ?>"><?= htmlspecialchars($article['title']) ?></a></h3>
                        <p class="alist-card-desc"><?= htmlspecialchars(mb_strimwidth($article['excerpt'] ?? '', 0, 95, '…')) ?></p>
                        <a href="<?= htmlspecialchars($articleUrl) ?>" class="alist-card-link">Baca Selengkapnya <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="text-align:center;color:var(--text2);padding:3rem 0;">Artikel segera hadir.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-clean" data-reveal>
        <div class="container contact-split">
            <div class="contact-text">
                <h2>Siap Mengembangkan Bisnis Anda?</h2>
                <p>Jangan ragu untuk menghubungi kami. Kami siap mendiskusikan kebutuhan proyek Anda dan memberikan solusi teknologi terbaik.</p>
                
                <div class="contact-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p>consulting@desadroid.shop</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <h4>Telepon / WhatsApp</h4>
                            <p>+62 896 6970 9021</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <h4>Lokasi</h4>
                            <p>Cikeas Udik, Kabupaten Bogor, Jawa Barat</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-card">
                <form method="post" action="<?= htmlspecialchars($baseDir . '/send_message.php') ?>">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon (Opsional)</label>
                        <input type="text" id="phone" name="phone" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan Anda</label>
                        <textarea id="message" name="message" rows="4" placeholder="Ceritakan kebutuhan proyek Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn primary btn-submit">Kirim Pesan Sekarang</button>
                </form>
            </div>
        </div>
    </section>

<?php include 'partials/footer.php'; ?>
