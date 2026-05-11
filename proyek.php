<?php
require_once 'config/db.php';
$pageTitle = 'Portofolio Proyek — Desadroid IT Consultant';
$metaDescription = 'Lihat portofolio proyek digital terbaik Desadroid: website, aplikasi mobile, sistem informasi, dan solusi enterprise untuk berbagai industri.';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$baseDirUrl = $baseDir;

try {
    $projects = $pdo->query("SELECT * FROM projects ORDER BY order_num ASC, created_at DESC")->fetchAll();
} catch (Exception $e) {
    $projects = [];
}

// Collect unique categories
$categories = ['Semua'];
foreach ($projects as $p) {
    if (!empty($p['category']) && !in_array($p['category'], $categories)) {
        $categories[] = $p['category'];
    }
}

include 'partials/header.php';
?>

<!-- Page Header -->
<section class="plist-header">
    <div class="container">
        <div class="plist-header-inner">
            <div>
                <nav class="plist-breadcrumb">
                    <a href="<?= $baseDirUrl ?: '/' ?>">Beranda</a>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                    <span>Portofolio</span>
                </nav>
                <h1 class="plist-title">Portofolio <span>Proyek</span> Kami</h1>
                <p class="plist-subtitle">Hasil nyata dari dedikasi tim kami — dari perancangan arsitektur hingga produk digital yang berdampak.</p>
            </div>
            <div class="plist-stats">
                <div class="plist-stat">
                    <strong><?= count($projects) ?>+</strong>
                    <span>Proyek Selesai</span>
                </div>
                <div class="plist-stat">
                    <strong><?= count($categories) - 1 ?></strong>
                    <span>Kategori</span>
                </div>
                <div class="plist-stat">
                    <strong>5+</strong>
                    <span>Tahun Pengalaman</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Grid -->
<section class="plist-section">
    <div class="container">

        <!-- Category Filter -->
        <?php if (count($categories) > 2): ?>
        <div class="plist-filter">
            <?php foreach ($categories as $cat): ?>
            <button class="plist-filter-btn <?= $cat === 'Semua' ? 'active' : '' ?>" data-filter="<?= htmlspecialchars($cat) ?>">
                <?= htmlspecialchars($cat) ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Projects Grid -->
        <?php if (!empty($projects)): ?>
        <div class="plist-grid" id="plist-grid">
            <?php foreach ($projects as $project): ?>
            <?php
                $imgUrl = !empty($project['image_url']) ? $project['image_url'] : "https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=600&q=80";
                $progress = isset($project['progress']) ? intval($project['progress']) : 0;
                $statusClass = $progress >= 100 ? 'done' : ($progress > 0 ? 'progress' : 'plan');
                $statusLabel = $progress >= 100 ? 'Selesai' : ($progress > 0 ? 'Berjalan' : 'Direncanakan');
                $detailUrl = $baseDirUrl . '/proyek/' . rawurlencode($project['slug']);
            ?>
            <div class="plist-card" data-reveal data-cat="<?= htmlspecialchars($project['category']) ?>">
                <a href="<?= htmlspecialchars($detailUrl) ?>" class="plist-card-thumb">
                    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                    <span class="plist-card-cat"><?= htmlspecialchars($project['category']) ?></span>
                    <span class="plist-card-status <?= $statusClass ?>"><?= $statusLabel ?></span>
                </a>
                <div class="plist-card-body">
                    <h2 class="plist-card-title">
                        <a href="<?= htmlspecialchars($detailUrl) ?>"><?= htmlspecialchars($project['title']) ?></a>
                    </h2>
                    <p class="plist-card-desc"><?= htmlspecialchars(mb_strimwidth(strip_tags($project['description'] ?? ''), 0, 105, '…')) ?></p>

                    <div class="plist-progress">
                        <div class="plist-progress-head">
                            <span>Progress Proyek</span>
                            <strong><?= $progress ?>%</strong>
                        </div>
                        <div class="plist-progress-bar">
                            <div class="plist-progress-fill" style="width:<?= $progress ?>%"></div>
                        </div>
                    </div>

                    <div class="plist-card-footer">
                        <a href="<?= htmlspecialchars($detailUrl) ?>" class="plist-btn-detail">
                            Lihat Detail
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                        <?php if (!empty($project['link'])): ?>
                        <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank" rel="noopener" class="plist-btn-live" title="Lihat website">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="plist-no-result" id="plist-no-result" style="display:none;">
            <p>Tidak ada proyek dalam kategori ini.</p>
        </div>

        <?php else: ?>
        <div class="plist-empty">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
            <h3>Portofolio segera hadir</h3>
            <p>Kami sedang menyiapkan konten terbaik untuk Anda.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function(){
    const btns = document.querySelectorAll('.plist-filter-btn');
    const cards = document.querySelectorAll('.plist-card');
    const noResult = document.getElementById('plist-no-result');

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