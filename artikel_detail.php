<?php
require_once 'config/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$article = null;
$latest_articles = [];

// baseDir used for building asset/URL paths
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';

if ($slug !== '') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM articles WHERE slug = ? AND status = "published"');
        $stmt->execute([$slug]);
        $article = $stmt->fetch();

        if ($article) {
            $latest_stmt = $pdo->prepare('SELECT * FROM articles WHERE status="published" AND id!=? ORDER BY published_date DESC LIMIT 5');
            $latest_stmt->execute([$article['id']]);
            $latest_articles = $latest_stmt->fetchAll();
        }

    } catch (Exception $e) {
        $article = null;
    }
}

// Prepare page title, canonical, meta description and image for header
if ($article) {
    $pageTitle = $article['title'] . ' - Desadroid';
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $canonical = $scheme . '://' . $host . $baseDir . '/artikel/' . rawurlencode($article['slug']);
    // description: prefer explicit excerpt, else trim content
    $metaDescription = !empty($article['excerpt']) ? $article['excerpt'] : mb_strimwidth(strip_tags($article['content']), 0, 160, '...');
    $metaImage = !empty($article['featured_image']) ? $article['featured_image'] : '';
} else {
    $pageTitle = 'Artikel - Desadroid';
}

?>
<?php include 'partials/header.php'; ?>

<style>

.article-section{
    padding:70px 20px;
    background:#f5f7fb;
}

.article-container{
    max-width:1200px;
    margin:auto;
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:40px;
}

.article-card{
    background:#fff;
    padding:35px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.05);
}

.article-card img{
    width:100%;
    border-radius:10px;
    margin-bottom:25px;
}

.badge-category{
    background:#0066cc;
    color:white;
    padding:6px 12px;
    font-size:13px;
    border-radius:4px;
    display:inline-block;
    margin-bottom:15px;
}

.article-title{
    font-size:32px;
    font-weight:700;
    margin-bottom:15px;
    line-height:1.3;
}

.article-meta{
    font-size:14px;
    color:#777;
    display:flex;
    gap:20px;
    margin-bottom:25px;
}

.article-content{
    font-size:18px;
    line-height:1.9;
    color:#333;
    white-space: pre-line;
}

.article-hero{
    display:flex;
    gap:28px;
    align-items:flex-start;
    margin-bottom:18px;
}
.article-hero img{
    width:100%;
    max-width:460px;
    height:260px; 
    border-radius:12px;
    object-fit:cover;
}
.article-tags{margin-top:18px}
.article-tags .tag{display:inline-block;background:#eef6ff;color:#0066cc;padding:6px 10px;border-radius:999px;margin-right:8px;font-size:13px}
.author-box{display:flex;gap:12px;align-items:center;margin:18px 0;padding:12px;border-radius:10px;background:#fff;box-shadow:0 6px 18px rgba(0,0,0,0.04)}
.author-box img{width:56px;height:56px;object-fit:cover;border-radius:50%}
.share-buttons{display:flex;gap:8px}
.share-buttons a{display:inline-block;padding:8px 10px;border-radius:8px;background:#f1f5f9;color:#111;text-decoration:none;font-weight:700}

.sidebar .latest-item{display:flex;gap:10px;align-items:center;margin-bottom:12px}
.sidebar .latest-item img{width:72px;height:56px;object-fit:cover;border-radius:6px}
.sidebar .latest-item .meta{font-size:12px;color:#777}

.back-btn{
    display:inline-block;
    margin-bottom:25px;
    padding:8px 16px;
    background:#eee;
    border-radius:6px;
    text-decoration:none;
    color:#333;
    font-size:14px;
    transition:0.2s;
}

.back-btn:hover{
    background:#0066cc;
    color:white;
}

.sidebar{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.05);
    height:fit-content;
}

.sidebar h4{
    margin-bottom:15px;
    color:#0066cc;
}

.sidebar ul{
    list-style:none;
    padding:0;
    margin:0;
}

.sidebar li{
    margin-bottom:15px;
}

.sidebar a{
    text-decoration:none;
    font-weight:600;
    color:#333;
    transition:.2s;
}

.sidebar a:hover{
    color:#0066cc;
}

.sidebar small{
    display:block;
    color:#999;
    font-size:12px;
}

/* RESPONSIVE */

@media(max-width:900px){

.article-container{
grid-template-columns:1fr;
}

.article-title{
font-size:24px;
}

.article-card{
padding:25px;
}

.article-content{
font-size:16px;
}

}

@media(max-width:900px){
    .article-hero{flex-direction:column;}
    .article-hero img{max-width:100%;height:auto}
    .author-box{flex-direction:row;gap:12px}
}

</style>

<section class="article-section" data-aos="fade-up">



<div class="article-container">

<!-- MAIN CONTENT -->
<div>

<a href="artikel.php" class="back-btn">← Kembali ke Artikel</a>

<?php if ($article): ?>

<div class="article-card">

<div class="article-hero">
    <?php if (!empty($article['featured_image'])): ?>
        <?php $heroImg = (preg_match('/^https?:\/\//', $article['featured_image'])) ? $article['featured_image'] : $baseDir . '/' . ltrim($article['featured_image'], '/'); ?>
        <img src="<?= htmlspecialchars($heroImg) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
    <?php endif; ?>
    <div style="flex:1">
        <span class="badge-category"><?= htmlspecialchars($article['category']) ?></span>
        <h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>
        <div class="article-meta">
            <span>📅 <?= date('d M Y', strtotime($article['published_date'])) ?></span>
            <span>👤 <?= htmlspecialchars($article['author'] ?? 'Tim Kami') ?></span>
            <span>⏱ <?= $article['read_time'] ?> min read</span>
        </div>

        <div class="author-box">
            <img src="<?= htmlspecialchars($article['author_avatar'] ?? ($baseDir . '/src/img/default-author.png')) ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" alt="Author">
            <div>
                <div style="font-weight:700;color:#0066cc"><?= htmlspecialchars($article['author'] ?? 'Tim Kami') ?></div>
                <div style="font-size:13px;color:#666">Penulis</div>
            </div>
            <div style="margin-left:auto" class="share-buttons">
                <?php $shareUrl = rawurlencode($canonical); ?>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank">FB</a>
                <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= rawurlencode($article['title']) ?>" target="_blank">TW</a>
                <a href="https://wa.me/?text=<?= $shareUrl ?>" target="_blank">WA</a>
            </div>
        </div>

        <?php if (!empty($article['tags'])): ?>
            <div class="article-tags">
                <?php foreach (explode(',', $article['tags']) as $t): ?>
                    <span class="tag"><?= htmlspecialchars(trim($t)) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="article-content">
    <?= $article['content'] ?>
</div>

</div>

<?php else: ?>

<div class="article-card" style="text-align:center">
<h3>Artikel tidak ditemukan</h3>
<p>Artikel belum tersedia atau sudah dihapus.</p>
<a href="artikel.php" class="back-btn">← Kembali</a>
</div>

<?php endif; ?>

</div>


<!-- SIDEBAR -->
<aside class="sidebar">

<h4>Artikel Lainnya</h4>

<?php if (!empty($latest_articles)): ?>
    <?php foreach ($latest_articles as $latest): ?>
        <?php
            $latestUrl = $baseDir . '/artikel/' . rawurlencode($latest['slug']);
        ?>
        <div class="latest-item">
            <?php if (!empty($latest['featured_image'])): ?>
                <?php $limg = (preg_match('/^https?:\/\//', $latest['featured_image'])) ? $latest['featured_image'] : $baseDir . '/' . ltrim($latest['featured_image'], '/'); ?>
                <img src="<?= htmlspecialchars($limg) ?>" alt="<?= htmlspecialchars($latest['title']) ?>">
            <?php else: ?>
                <img src="https://source.unsplash.com/160x120/?<?= urlencode(strtolower($latest['category'])) ?>" alt="<?= htmlspecialchars($latest['title']) ?>">
            <?php endif; ?>
            <div>
                <a href="<?= htmlspecialchars($latestUrl) ?>"><?= htmlspecialchars($latest['title']) ?></a>
                <div class="meta"><?= date('d M Y', strtotime($latest['published_date'])) ?></div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Tidak ada artikel lainnya</p>
<?php endif; ?>

</aside>

</div>
</section>

<?php include 'partials/footer.php'; ?>