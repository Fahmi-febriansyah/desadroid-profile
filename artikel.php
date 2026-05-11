<?php
require_once 'config/db.php';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';

$pageTitle = 'Blog & Artikel — Desadroid IT Consultant';
$metaDescription = 'Baca artikel teknologi terbaru dari tim Desadroid: tips pengembangan web, mobile, desain UI/UX, dan tren industri digital.';

try {
    $articles = $pdo->query('SELECT * FROM articles WHERE status="published" ORDER BY published_date DESC')->fetchAll();
} catch (Exception $e) {
    $articles = [];
}

// Unique categories
$categories = ['Semua'];
foreach ($articles as $a) {
    if (!empty($a['category']) && !in_array($a['category'], $categories)) {
        $categories[] = $a['category'];
    }
}

// Featured = first article
$featured = !empty($articles) ? $articles[0] : null;
$rest = !empty($articles) ? array_slice($articles, 1) : [];

include 'partials/header.php';
?>

<!-- Page Header -->
<section class="alist-header">
    <div class="container">
        <nav class="alist-breadcrumb">
            <a href="<?= $baseDir ?: '/' ?>">Beranda</a>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Artikel</span>
        </nav>
        <div class="alist-header-inner">
            <div>
                <h1 class="alist-title">Blog <span>&</span> Artikel</h1>
                <p class="alist-subtitle">Panduan, insight, dan perspektif dari tim Desadroid tentang dunia teknologi dan pengembangan digital.</p>
            </div>
            <div class="alist-header-meta">
                <div class="alist-meta-item">
                    <strong><?= count($articles) ?></strong>
                    <span>Artikel</span>
                </div>
                <div class="alist-meta-item">
                    <strong><?= count($categories) - 1 ?></strong>
                    <span>Topik</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($featured): ?>
<?php
    $fUrl  = $baseDir . '/artikel/' . rawurlencode($featured['slug']);
    $fImg  = !empty($featured['featured_image'])
           ? (preg_match('/^https?:\/\//', $featured['featured_image']) ? $featured['featured_image'] : $baseDir . '/' . ltrim($featured['featured_image'], '/'))
           : "https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200&q=80";
?>
<!-- Featured Article -->
<section class="alist-featured-wrap">
    <div class="container">
        <div class="alist-featured">
            <a href="<?= htmlspecialchars($fUrl) ?>" class="alist-featured-img">
                <img src="<?= htmlspecialchars($fImg) ?>" alt="<?= htmlspecialchars($featured['title']) ?>" loading="lazy">
                <span class="alist-featured-badge">Artikel Unggulan</span>
            </a>
            <div class="alist-featured-body">
                <span class="alist-card-cat"><?= htmlspecialchars($featured['category']) ?></span>
                <h2 class="alist-featured-title">
                    <a href="<?= htmlspecialchars($fUrl) ?>"><?= htmlspecialchars($featured['title']) ?></a>
                </h2>
                <p class="alist-featured-desc"><?= htmlspecialchars(mb_strimwidth($featured['excerpt'] ?? '', 0, 180, '…')) ?></p>
                <div class="alist-card-meta">
                    <span><?= date('d M Y', strtotime($featured['published_date'])) ?></span>
                    <span class="dot">·</span>
                    <span><?= htmlspecialchars($featured['read_time'] ?? '5') ?> menit baca</span>
                </div>
                <a href="<?= htmlspecialchars($fUrl) ?>" class="alist-btn-read">
                    Baca Artikel
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Article Grid -->
<section class="alist-section">
    <div class="container">

        <!-- Category Filter -->
        <?php if (count($categories) > 2): ?>
        <div class="alist-filter">
            <?php foreach ($categories as $cat): ?>
            <button class="alist-filter-btn <?= $cat === 'Semua' ? 'active' : '' ?>" data-filter="<?= htmlspecialchars($cat) ?>">
                <?= htmlspecialchars($cat) ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($rest)): ?>
        <div class="alist-grid" id="alist-grid">
            <?php foreach ($rest as $article): ?>
            <?php
                $aUrl = $baseDir . '/artikel/' . rawurlencode($article['slug']);
                $aImg = !empty($article['featured_image'])
                      ? (preg_match('/^https?:\/\//', $article['featured_image']) ? $article['featured_image'] : $baseDir . '/' . ltrim($article['featured_image'], '/'))
                      : "https://images.unsplash.com/photo-1542435503-ec7b0f197a62?w=600&q=80";
            ?>
            <article class="alist-card" data-reveal data-cat="<?= htmlspecialchars($article['category']) ?>">
                <a href="<?= htmlspecialchars($aUrl) ?>" class="alist-card-thumb">
                    <img src="<?= htmlspecialchars($aImg) ?>" alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
                    <span class="alist-card-cat"><?= htmlspecialchars($article['category']) ?></span>
                </a>
                <div class="alist-card-body">
                    <div class="alist-card-meta">
                        <span><?= date('d M Y', strtotime($article['published_date'])) ?></span>
                        <span class="dot">·</span>
                        <span><?= htmlspecialchars($article['read_time'] ?? '5') ?> mnt</span>
                    </div>
                    <h3 class="alist-card-title">
                        <a href="<?= htmlspecialchars($aUrl) ?>"><?= htmlspecialchars($article['title']) ?></a>
                    </h3>
                    <p class="alist-card-desc"><?= htmlspecialchars(mb_strimwidth($article['excerpt'] ?? '', 0, 100, '…')) ?></p>
                    <a href="<?= htmlspecialchars($aUrl) ?>" class="alist-card-link">
                        Baca Selengkapnya
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="alist-no-result" id="alist-no-result" style="display:none;">
            <p>Tidak ada artikel dalam kategori ini.</p>
        </div>

        <?php elseif (empty($articles)): ?>
        <div class="alist-empty">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <h3>Belum ada artikel</h3>
            <p>Artikel terbaru akan segera kami terbitkan.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function(){
    const btns = document.querySelectorAll('.alist-filter-btn');
    const cards = document.querySelectorAll('.alist-card');
    const noResult = document.getElementById('alist-no-result');

    btns.forEach(btn => {
        btn.addEventListener('click', function() {
            btns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.dataset.filter;
            let visible = 0;
            cards.forEach(card => {
                if (filter === 'Semua' || card.dataset.cat === filter) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });
            if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
        });
    });
})();
</script>

<?php include 'partials/footer.php'; ?>