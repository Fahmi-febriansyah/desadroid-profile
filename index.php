<?php
require_once 'config/db.php';

// Get projects from datab   ase
try {
    $projects_query = $pdo->query('SELECT * FROM projects ORDER BY order_num ASC, created_at DESC');
    $projects = $projects_query->fetchAll();
} catch (Exception $e) {
    $projects = [];
}

// Get articles from database (limit to 4 for homepage — not too many, not too few)
try {
    $articles_query = $pdo->query('SELECT * FROM articles WHERE status = "published" ORDER BY published_date DESC LIMIT 4');
    $articles = $articles_query->fetchAll();
} catch (Exception $e) {
    $articles = [];
}

// Get services from database (limit to 3 for homepage)
try {
    $services_query = $pdo->query('SELECT * FROM services ORDER BY id ASC LIMIT 3');
    $services = $services_query->fetchAll();
} catch (Exception $e) {
    $services = [];
}

// Get testimonials from database
try {
    // testimonials table does not have a `status` column in this DB dump,
    // select all and order by created_at
    $testimonials_query = $pdo->query('SELECT * FROM testimonials ORDER BY created_at DESC');
    $testimonials = $testimonials_query->fetchAll();
} catch (Exception $e) {
    $testimonials = [];
}
?>
<?php
// Build canonical for homepage
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$canonical = $scheme . '://' . $host . $baseDir . '/';
?>
<?php
// Use universal header
$pageTitle = 'Desadroid - Jasa Pembuatan Website & Aplikasi Bisnis Profesional';
$metaDescription = 'Cari jasa pembuatan website dan aplikasi mobile profesional? Desadroid siap membantu transformasi digital bisnis Anda dengan solusi UI/UX inovatif. Konsultasikan di sini!';
$metaImage = 'src/img/DESADROID.jpg';
include 'partials/header.php';
?>

    <!-- hero section -->
    <section id="home" class="hero" data-aos="fade-up">
        <div class="container">
            <div class="welcome-badge">Selamat datang di desadroid</div>
            <h1>Jasa Pembuatan Website & Aplikasi Bisnis Profesional | Desadroid IT</h1>
            <p>Kami membangun produk dan pengalaman digital inovatif untuk web, mobile,
                dan lebih. Jelajahi masa depan pengembangan digital bersama kami.</p>
            <div class="cta-buttons">
                <a href="#projects" class="btn primary">Lihat Proyek</a>
                <a href="#contact" class="btn secondary">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <!-- about -->
    <section id="about" class="about" data-aos="fade-right">
        <div class="container">
            <h2>Tentang desadroid</h2>
            <p>Mitra tepercaya Anda dalam transformasi digital.</p>
                <div class="about-content">
                <?php $aboutImg = (strpos($baseDir . '/src/img/DESADROID.jpg', 'http') === 0) ? $baseDir . '/src/img/DESADROID.jpg' : $baseDir . '/src/img/DESADROID.jpg'; ?>
                <img src="<?= htmlspecialchars($aboutImg) ?>" style="width: 100%; height: 400px; object-fit: cover;" class="about-image" alt="Tim sedang bekerja" />
                <div class="text">
                    <p>Desadroid adalah studio produksi digital yang berdedikasi membantu bisnis menciptakan pengalaman digital berkelas sejak 2025. Kami mengintegrasikan kreativitas desain, keunggulan teknis, dan strategi yang tepat untuk mengakselerasi pertumbuhan serta inovasi bisnis Anda.</p>
                    <a href="#" class="btn tertiary">Ajukan Proyek</a>
                </div>
            </div>
        </div>
    </section>

    <!-- services -->
    <section id="services" class="services" data-aos="fade-left">
        <div class="container">
            <h2>Layanan Kami</h2>
            <div class="service-cards">
                <?php if (!empty($services)): ?>
                    <?php $delay = 100; foreach ($services as $service): ?>
                    <div class="card" data-aos="zoom-in" data-aos-delay="<?= $delay ?>">
                        <div class="icon" style="font-size: 2rem; margin-bottom: 1rem;">
                            <?php 
                                // Map service types to emojis
                                $icons = [
                                    'Web Development' => '🌐',
                                    'Mobile App' => '📱',
                                    'UX/UI Design' => '🎨',
                                    'Backend Development' => '⚙️',
                                    'E-Commerce' => '🛒',
                                    'Consulting' => '💼'
                                ];
                                echo $icons[$service['name']] ?? '✨';
                            ?>
                        </div>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                    <?php $delay += 100; endforeach; ?>
                <?php else: ?>
                    <div class="card" data-aos="zoom-in" data-aos-delay="100">
                        <div class="icon icon-web"></div>
                        <h3>Pengembangan Web</h3>
                        <p>Membangun situs responsif dan dapat diakses dengan teknologi modern.</p>
                    </div>
                    <div class="card" data-aos="zoom-in" data-aos-delay="200">
                        <div class="icon icon-mobile"></div>
                        <h3>Pengembangan Aplikasi Mobile</h3>
                        <p>Aplikasi native dan cross-platform dengan UI yang menyenangkan.</p>
                    </div>
                    <div class="card" data-aos="zoom-in" data-aos-delay="300">
                        <div class="icon icon-design"></div>
                        <h3>Desain UI/UX</h3>
                        <p>Solusi desain berfokus pengguna yang meningkatkan keterlibatan dan konversi.</p>
                    </div>
                    <div class="card" data-aos="zoom-in" data-aos-delay="400">
                        <div class="icon icon-backend"></div>
                        <h3>Pengembangan Backend</h3>
                        <p>Sistem server-side kuat dengan skala sebagai prioritas.</p>
                    </div>
                    <div class="card" data-aos="zoom-in" data-aos-delay="500">
                        <div class="icon icon-ecommerce"></div>
                        <h3>Solusi E-Commerce</h3>
                        <p>Implementasi toko online end-to-end dengan pembayaran aman.</p>
                    </div>
                    <div class="card" data-aos="zoom-in" data-aos-delay="600">
                        <div class="icon icon-consulting"></div>
                        <h3>Konsultasi Digital</h3>
                        <p>Strategi, riset, dan perencanaan untuk inisiatif digital Anda.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- projects -->
    <section id="projects" class="projects" data-aos="fade-up">
        <div class="container">
            <h2>Proyek Kami</h2>
            <?php if (!empty($projects)): ?>
            <div class="project-grid">
                <?php $delay = 100; foreach ($projects as $project): ?>
                <div class="project" data-aos="flip-left" data-aos-delay="<?= $delay ?>">
                    <div class="category"><?= htmlspecialchars($project['category']) ?></div>
                    <?php if ($project['image_url']): ?>
                        <img src="<?= htmlspecialchars($project['image_url']) ?>" style="width: 100%; height: 220px; object-fit: cover;" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                    <?php else: ?>
                        <img src="https://source.unsplash.com/featured/400x250/?<?= urlencode(strtolower($project['category'])) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                    <?php endif; ?>
                    <div class="project-overlay">
                        <?php if ($project['link']): ?>
                            <a href="<?= htmlspecialchars($project['link']) ?>" class="overlay-btn" target="_blank">View</a>
                        <?php endif; ?>
                        <?php if ($project['code_link']): ?>
                            <a href="<?= htmlspecialchars($project['code_link']) ?>" class="overlay-btn" target="_blank">Code</a>
                        <?php endif; ?>
                    </div>
                    <h4><?= htmlspecialchars($project['title']) ?></h4>
                    <?php $shortDesc = mb_strimwidth(trim($project['description'] ?? ''), 0, 140, '...'); ?>
                    <p style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($shortDesc) ?></p>
                    <a href="#" class="btn tertiary">Lihat Case Study</a>
                </div>
                <?php $delay += 100; endforeach; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 3rem; color: #999;">
                <p style="font-size: 1.1rem; margin-bottom: 1rem;">📋 Belum ada proyek yang ditambahkan</p>
                <p>Hubungi kami untuk melihat portofolio lengkap kami atau ajukan proyek baru.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- articles -->
    <section id="articles" class="articles" data-aos="fade-up">
        <div class="container">
            <h2>Artikel Terbaru</h2>
            <div class="article-list">
                <?php if (!empty($articles)): ?>
                    <?php $delay = 100; foreach ($articles as $article): ?>
                    <article data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <span class="badge"><?= htmlspecialchars($article['category']) ?></span>
                        <?php if (!empty($article['featured_image'])): ?>
                            <?php $img = (preg_match('/^https?:\/\//', $article['featured_image'])) ? $article['featured_image'] : $baseDir . '/' . ltrim($article['featured_image'], '/'); ?>
                            <img src="<?= htmlspecialchars($img) ?>" style="width: 100%; height: 180px; object-fit: cover;" alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
                        <?php else: ?>
                            <img src="https://source.unsplash.com/featured/350x200/?<?= urlencode(strtolower($article['category'])) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php endif; ?>
                        <div class="article-meta">
                            <span class="date"><?= date('d M Y', strtotime($article['published_date'])) ?></span>
                            <span class="read-time"><?= $article['read_time'] ?> min read</span>
                        </div>
                        <h4><?= htmlspecialchars($article['title']) ?></h4>
                        <p><?= htmlspecialchars($article['excerpt']) ?></p>
                        <?php
                        $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                        if ($baseDir === '/') $baseDir = '';
                        $articleUrl = $baseDir . '/artikel/' . rawurlencode($article['slug']);
                        ?>
                        <a href="<?= htmlspecialchars($articleUrl) ?>" class="read-more">Baca Selanjutnya →</a>
                    </article>
                    <?php $delay += 100; endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 3rem; color: #999; grid-column: 1 / -1;">
                        <p style="font-size: 1.1rem; margin-bottom: 1rem;">📝 Belum ada artikel yang dipublikasikan</p>
                        <p>Artikel baru akan ditampilkan di sini. Periksa kembali nanti untuk konten menarik dari tim kami.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- clients -->
    <section id="clients" class="clients" data-aos="fade-in">
        <div class="container">
            <h2>Klien Terpercaya Kami</h2>
            <?php if (!empty($testimonials)): ?>
            <div class="testimonials-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
                <?php foreach ($testimonials as $client): ?>
                <div class="testimonial-card" style="background:#fff;padding:1rem;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06);">
                    <div style="display:flex;gap:0.8rem;align-items:flex-start;">
                        <?php if ($client['image_url']): ?>
                            <img src="<?= htmlspecialchars($client['image_url']) ?>" alt="<?= htmlspecialchars($client['client_name']) ?>" style="width:56px;height:56px;object-fit:cover;border-radius:8px;">
                        <?php else: ?>
                            <div style="width:56px;height:56px;background:#e9eef8;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#0070c9;font-weight:700;"><?= strtoupper(substr($client['client_name'],0,1)) ?></div>
                        <?php endif; ?>
                        <div>
                            <div style="font-weight:700;color:#0066cc;"><?= htmlspecialchars($client['client_name']) ?></div>
                            <?php if ($client['company']): ?><div style="font-size:0.85rem;color:#777;"><?= htmlspecialchars($client['company']) ?></div><?php endif; ?>
                            <div style="margin-top:0.5rem;color:#333;font-size:0.95rem;"><?= htmlspecialchars(mb_strimwidth($client['message'],0,140,'...')) ?></div>
                            <?php if ($client['rating']): ?>
                                <div style="margin-top:0.5rem;color:#ffb400;">
                                    <?php for ($i=0;$i<5;$i++): ?>
                                        <?= $i < intval($client['rating']) ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 3rem; color: #999;">
                <p style="font-size: 1.1rem; margin-bottom: 1rem;">🤝 Belum ada testimonial klien</p>
                <p>Jadilah klien kami dan bagikan pengalaman Anda.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- workflow -->
    <section id="workflow" class="workflow" data-aos="fade-up">
        <div class="container">
            <h2>Bagaimana Kami Bekerja</h2>
            <div class="steps">
                <div class="step" data-aos="fade-right" data-aos-delay="100">
                    <div class="icon">💡</div>
                    <h4>Riset & Discovery</h4>
                    <p>Memahami kebutuhan, target pasar, dan visi Anda untuk solusi terbaik.</p>
                </div>
                <div class="step" data-aos="fade-right" data-aos-delay="200">
                    <div class="icon">🎨</div>
                    <h4>Desain & Strategi</h4>
                    <p>Merancang solusi yang inovatif dan sesuai dengan brand Anda.</p>
                </div>
                <div class="step" data-aos="fade-right" data-aos-delay="300">
                    <div class="icon">⚡</div>
                    <h4>Eksekusi & Pengembangan</h4>
                    <p>Membangun dengan teknologi terkini dan best practices industri.</p>
                </div>
                <div class="step" data-aos="fade-right" data-aos-delay="400">
                    <div class="icon">🚀</div>
                    <h4>Peluncuran & Support</h4>
                    <p>Meluncurkan produk dan memberikan dukungan berkelanjutan.</p>
                </div>
            </div>
            <a href="#contact" class="btn primary">Mulai Proyek Anda</a>
        </div>
    </section>

    <!-- contact -->
    <section id="contact" class="contact" data-aos="fade-up">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Ayo Bicara</h3>
                    <p>Kami selalu bersemangat mendengar tentang proyek dan peluang baru. Hubungi kami melalui channel manapun.</p>
                    <div class="contact-item">
                        <div class="contact-icon email-icon"></div>
                        <div>
                            <div class="contact-label">Email</div>
                            <div class="contact-value">consulting@desadroid.shop</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon phone-icon"></div>
                        <div>
                            <div class="contact-label">Telepon</div>
                            <div class="contact-value">+6289669709021</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon location-icon"></div>
                        <div>
                            <div class="contact-label">Kantor</div>
                            <div class="contact-value">JW7P+2P Cikeas Udik, Kabupaten Bogor, Jawa Barat</div>
                        </div>
                    </div>
                    <a href="https://wa.me/6289669709021" class="btn whatsapp-btn" target="_blank">Chat on WhatsApp</a>
                </div>
                <form class="contact-form" method="post" action="<?= htmlspecialchars($baseDir . '/send_message.php') ?>">
                    <input type="text" name="name" placeholder="Nama lengkap" required>
                    <input type="email" name="email" placeholder="Alamat Email" required>
                    <input type="text" name="phone" placeholder="Telepon (opsional)">
                    <textarea name="message" placeholder="Pesan" rows="5" required></textarea>
                    <button type="submit" class="btn primary">Kirim Pesan</button>
                </form>
            </div>
            <div class="map">
                <!-- Placeholder map -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d991.2628275958328!2d106.9363426!3d-6.3873803!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699592e27d4a99%3A0xd951adc24903faa!2sPojok%20cell!5e0!3m2!1sid!2sid!4v1773116030996!5m2!1sid!2sid"
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

<?php include 'partials/footer.php'; ?>
