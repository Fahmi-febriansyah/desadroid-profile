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

<!-- Page Hero -->
<section class="pg-hero">
    <div class="pg-hero-bg"></div>
    <div class="container text-center" style="position:relative; z-index:2;">
        <span class="pg-hero-label">Karya Kami</span>
        <h1 class="pg-hero-title">Portofolio <span class="text-gradient">Terbaik</span> Kami</h1>
        <p class="pg-hero-subtitle mx-auto">Jelajahi inovasi digital yang telah kami bangun untuk berbagai bisnis. Dari ide hingga implementasi, setiap proyek adalah komitmen kami pada kualitas.</p>
    </div>
</section>

<!-- Projects Grid Section -->
<section class="pg-section">
    <div class="container">
        <?php if (!empty($projects)): ?>
        <div class="pg-grid">
            <?php foreach ($projects as $project): ?>
            <?php 
                $imgUrl = $project['image_url'] ? $project['image_url'] : "https://source.unsplash.com/featured/600x400/?" . urlencode(strtolower($project['category']));
                $progress = isset($project['progress']) ? intval($project['progress']) : 0;
            ?>
            <div class="pg-card" data-reveal>
                <div class="pg-card-img">
                    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                    <div class="pg-card-cat"><?= htmlspecialchars($project['category']) ?></div>
                    <div class="pg-card-overlay">
                        <a href="<?= htmlspecialchars($baseDirUrl . '/proyek/' . rawurlencode($project['slug'])) ?>" class="pg-btn-view">Lihat Detail <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                    </div>
                </div>
                <div class="pg-card-body">
                    <h3 class="pg-card-title"><a href="<?= htmlspecialchars($baseDirUrl . '/proyek/' . rawurlencode($project['slug'])) ?>"><?= htmlspecialchars($project['title']) ?></a></h3>
                    <p class="pg-card-desc"><?= htmlspecialchars(mb_strimwidth(trim($project['description'] ?? ''), 0, 110, '...')) ?></p>
                    
                    <div class="pg-progress-wrap">
                        <div class="pg-progress-header">
                            <span>Progress</span>
                            <strong><?= $progress ?>%</strong>
                        </div>
                        <div class="pg-progress-track">
                            <div class="pg-progress-fill" style="width: <?= $progress ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="pg-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
            <h3>Belum ada proyek</h3>
            <p>Portofolio proyek kami akan segera hadir di sini.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'partials/footer.php'; ?>