<?php 
require_once 'config/db.php';

// base path
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$articleBase = $baseDir . '/artikel/';
?>

<?php include 'partials/header.php'; ?>

<!-- Page Hero -->
<section class="al-hero">
    <div class="al-hero-bg"></div>
    <div class="container text-center" style="position:relative; z-index:2;">
        <span class="al-hero-label">Blog & Insight</span>
        <h1 class="al-hero-title">Jelajahi <span class="text-gradient">Wawasan</span> Baru</h1>
        <p class="al-hero-subtitle mx-auto">Temukan artikel terbaru, panduan, dan tren seputar teknologi, desain, dan pengembangan perangkat lunak dari tim ahli kami.</p>
    </div>
</section>

<!-- Articles Grid Section -->
<section class="al-section">
    <div class="container">
        <?php
        try {
            $articles_query = $pdo->query('SELECT * FROM articles WHERE status="published" ORDER BY published_date DESC');
            $articles = $articles_query->fetchAll();
        } catch (Exception $e) {
            $articles = [];
        }
        ?>

        <?php if (!empty($articles)): ?>
        <div class="al-grid">
            <?php foreach ($articles as $article): ?>
            <?php
                $articleUrl = $baseDir . '/artikel/' . rawurlencode($article['slug']);
                $img = (!empty($article['featured_image']) && preg_match('/^https?:\/\//', $article['featured_image'])) ? $article['featured_image'] : (empty($article['featured_image']) ? "https://source.unsplash.com/featured/600x400/?" . urlencode(strtolower($article['category'])) : $baseDir . '/' . ltrim($article['featured_image'], '/'));
            ?>
            <article class="al-card" data-reveal>
                <a href="<?= htmlspecialchars($articleUrl) ?>" class="al-card-img-link">
                    <div class="al-card-img">
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
                        <span class="al-card-cat"><?= htmlspecialchars($article['category']) ?></span>
                    </div>
                </a>
                <div class="al-card-body">
                    <div class="al-card-meta">
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <?= date('d M Y', strtotime($article['published_date'])) ?></span>
                        <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> <?= htmlspecialchars($article['read_time']) ?> mnt</span>
                    </div>
                    <h3 class="al-card-title">
                        <a href="<?= htmlspecialchars($articleUrl) ?>"><?= htmlspecialchars($article['title']) ?></a>
                    </h3>
                    <p class="al-card-desc"><?= htmlspecialchars(mb_strimwidth($article['excerpt'] ?? '', 0, 110, '...')) ?></p>
                    <a href="<?= htmlspecialchars($articleUrl) ?>" class="al-card-read">Baca Selengkapnya <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="al-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <h3>Belum ada artikel</h3>
            <p>Artikel terbaru akan segera hadir di sini.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'partials/footer.php'; ?>