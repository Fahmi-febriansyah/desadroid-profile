<?php 
require_once 'config/db.php';

// base path
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$articleBase = $baseDir . '/artikel/';
?>

<?php include 'partials/header.php'; ?>

<section class="articles" style="padding:5rem 0;">

<div class="container">

<div class="section-title" data-reveal>
<h2>Artikel Terbaru</h2>
<p>Insight teknologi, AI, dan pengembangan software</p>
</div>

<div class="article-list">

<?php
try {
$articles_query = $pdo->query('SELECT * FROM articles WHERE status="published" ORDER BY published_date DESC');
$articles = $articles_query->fetchAll();
} catch (Exception $e) {
$articles = [];
}
?>

<?php if (!empty($articles)): ?>

<?php foreach ($articles as $article): ?>

<article class="blog-card" data-reveal>

<a href="<?= htmlspecialchars($articleBase . rawurlencode($article['slug'])) ?>" class="blog-image">

<?php if (!empty($article['featured_image'])): ?>

<?php 
$img = (preg_match('/^https?:\/\//', $article['featured_image']))
? $article['featured_image']
: $baseDir . '/' . ltrim($article['featured_image'], '/');
?>

<img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($article['title']) ?>">

<?php else: ?>

<img src="https://source.unsplash.com/400x300/?<?= urlencode(strtolower($article['category'])) ?>">

<?php endif; ?>

</a>

<div class="blog-content">

<span class="badge" style="position:static;margin-bottom:8px;">
<?= htmlspecialchars($article['category']) ?>
</span>

<h3 class="blog-title">
<a href="<?= htmlspecialchars($articleBase . rawurlencode($article['slug'])) ?>">
<?= htmlspecialchars($article['title']) ?>
</a>
</h3>

<div class="article-meta">
<?= date('d M Y', strtotime($article['published_date'])) ?>
• <?= $article['read_time'] ?> min read
</div>

<p class="blog-excerpt">
<?= htmlspecialchars($article['excerpt']) ?>
</p>

<a href="<?= htmlspecialchars($articleBase . rawurlencode($article['slug'])) ?>" class="read-more">
Baca Selengkapnya →
</a>

</div>

</article>

<?php endforeach; ?>

<?php else: ?>

<div class="empty">
<h3>Belum ada artikel</h3>
<p>Artikel baru akan muncul di sini.</p>
</div>

<?php endif; ?>

</div>
</div>
</section>

<?php include 'partials/footer.php'; ?>