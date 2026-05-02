<?php
require_once 'config/db.php';
$pageTitle = 'Layanan - Desadroid';
$metaDescription = 'Temukan solusi digital terbaik dari Desadroid, mulai dari Web Development, Mobile Apps, hingga desain UI/UX kelas dunia.';
include 'partials/header.php'; 
?>
<?php 
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
if ($baseDir === '/') $baseDir = ''; 
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; }); 
$baseDirUrl = ''; 
if (!empty($baseDirSegments)) { 
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments)); 
} 

try {
    $services_query = $pdo->query('SELECT * FROM services ORDER BY id ASC');
    $services = $services_query->fetchAll();
} catch (Exception $e) {
    $services = [];
}
?>

<!-- Services Hero -->
<section class="page-hero text-center" data-reveal>
    <div class="container">
        <span class="hero-label">Keahlian Kami</span>
        <h1>Solusi Digital untuk <span class="text-gradient">Skala Bisnis</span> Anda</h1>
        <p class="hero-subtitle mx-auto">Kami mengombinasikan kreativitas desain dan keandalan teknologi untuk membangun produk digital yang inovatif dan efektif.</p>
    </div>
</section>

<!-- Services Grid -->
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
                <div class="empty-state" style="grid-column: 1 / -1;">Belum ada layanan yang ditambahkan.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Workflow / Proses Kerja -->
<section class="workflow-section" data-reveal>
    <div class="container">
        <div class="section-title text-center">
            <h2>Bagaimana Kami Bekerja?</h2>
            <p>Pendekatan terstruktur kami memastikan setiap proyek selesai tepat waktu dengan standar kualitas tertinggi.</p>
        </div>
        
        <div class="workflow-grid">
            <div class="workflow-step" data-reveal>
                <div class="step-number">01</div>
                <h3>Discovery</h3>
                <p>Kami mendengarkan visi Anda, menganalisis pasar, dan merumuskan strategi teknis yang paling tepat untuk mencapai tujuan bisnis.</p>
            </div>
            <div class="workflow-step" data-reveal>
                <div class="step-number">02</div>
                <h3>Design & Prototype</h3>
                <p>Membuat kerangka kerja UI/UX dan interaksi desain interaktif sebelum menulis satu baris kode pun, memastikan arah desain yang benar.</p>
            </div>
            <div class="workflow-step" data-reveal>
                <div class="step-number">03</div>
                <h3>Development</h3>
                <p>Proses coding yang agile, membangun frontend interaktif dan backend scalable dengan standar keamanan yang ketat.</p>
            </div>
            <div class="workflow-step" data-reveal>
                <div class="step-number">04</div>
                <h3>Launch & Support</h3>
                <p>Peluncuran produk yang mulus dan pemeliharaan berkelanjutan. Kami memastikan produk Anda selalu relevan dan up-to-date.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="about-cta bg-light" data-reveal>
    <div class="container text-center">
        <h2>Punya Ide Brilian?</h2>
        <p class="mb-4 text-muted mx-auto" style="max-width:500px;">Jadikan ide Anda kenyataan. Tim kami siap memberikan konsultasi gratis.</p>
        <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn primary">Mulai Proyek Bersama</a>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
