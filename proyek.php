<?php
require_once 'config/db.php';
$pageTitle = 'Portofolio Proyek - Desadroid';
$metaDescription = 'Lihat berbagai proyek sukses yang telah diselesaikan oleh Desadroid. Dari website e-commerce hingga aplikasi mobile enterprise.';
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
    $projects_query = $pdo->query("SELECT * FROM projects ORDER BY order_num ASC, created_at DESC");
    $projects = $projects_query->fetchAll();
} catch (Exception $e) {
    $projects = [];
}
?>

<!-- Projects Hero -->
<section class="page-hero text-center" data-reveal>
    <div class="container">
        <span class="hero-label">Karya Kami</span>
        <h1>Portofolio <span class="text-gradient">Terbaik</span> Kami</h1>
        <p class="hero-subtitle mx-auto">Setiap baris kode dan setiap piksel desain kami buat dengan dedikasi tinggi untuk menghasilkan produk digital berkualitas.</p>
    </div>
</section>

<section class="projects-page bg-light" data-reveal>
    <div class="container">
        <?php if (!empty($projects)): ?>
        <div class="project-grid-clean">
            <?php foreach ($projects as $project): ?>
            <div class="project-card-clean" data-reveal>
                <div class="project-img">
                    <?php if ($project['image_url']): ?>
                        <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                    <?php else: ?>
                        <img src="https://source.unsplash.com/featured/600x400/?<?= urlencode(strtolower($project['category'])) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                    <?php endif; ?>
                    <div class="project-overlay">
                        <?php
                            $hasLink = !empty($project['link']);
                            $viewHref = $hasLink ? htmlspecialchars($project['link']) : '#';
                            $viewTarget = $hasLink ? ' target="_blank"' : '';
                        ?>
                        <a href="<?= $viewHref ?>"<?= $viewTarget ?> class="btn-overlay"><?= $hasLink ? 'Lihat Website' : 'Info' ?></a>
                        <?php if (!empty($project['code_link'])): ?>
                            <a href="<?= htmlspecialchars($project['code_link']) ?>" target="_blank" class="btn-overlay" style="margin-left:10px;">Source Code</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="project-info">
                    <span class="project-category"><?= htmlspecialchars($project['category']) ?></span>
                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                    <?php $short = mb_strimwidth(trim($project['description'] ?? ''), 0, 100, '...'); ?>
                    <p><?= htmlspecialchars($short) ?></p>
                    
                    <div class="project-progress mt-3">
                        <?php $progress = isset($project['progress']) ? intval($project['progress']) : 0; ?>
                        <div class="progress-info">
                            <span>Status Proyek</span>
                            <strong><?= $progress ?>% Selesai</strong>
                        </div>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width: <?= $progress ?>%;"></div>
                        </div>
                    </div>
                    
                    <a href="<?= htmlspecialchars($baseDirUrl . '/proyek/' . rawurlencode($project['slug'])) ?>" class="btn secondary" style="width:100%; margin-top:1.5rem;">Lihat Case Study</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>📋 Belum ada proyek yang ditambahkan.</p>
            <p style="font-size: 0.95rem; margin-top: 0.5rem; color: var(--text2);">Hubungi kami untuk melihat portofolio lengkap atau mengajukan proyek baru.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'partials/footer.php'; ?>