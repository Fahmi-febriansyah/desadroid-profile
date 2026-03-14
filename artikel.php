<?php
require_once 'config/db.php';
?>
<?php
// prepare base path for pretty URLs
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$articleBase = $baseDir . '/artikel/';
?>
<?php include 'partials/header.php'; ?>

<style>

.articles{
padding:70px 20px;
background:#f5f7fb;
}

.container{
max-width:1200px;
margin:auto;
}

.article-list{
display:grid;
grid-template-columns:1fr 1fr;
gap:25px;
margin-top:40px;
}

.blog-card{
display:flex;
gap:18px;
background:#fff;
border-radius:12px;
overflow:hidden;
box-shadow:0 8px 25px rgba(0,0,0,0.05);
transition:0.3s;
padding:15px;
}

.blog-card:hover{
transform:translateY(-4px);
box-shadow:0 15px 35px rgba(0,0,0,0.08);
}

.blog-image{
width:140px;
height:140px;
flex-shrink:0;
border-radius:8px;
overflow:hidden;
}

.blog-image img{
width:100%;
height:100%;
object-fit:cover;
}

.blog-content{
flex:1;
}

.badge{
background:#0066cc;
color:#fff;
font-size:12px;
padding:4px 10px;
border-radius:4px;
}

.blog-title{
font-size:18px;
font-weight:700;
margin:8px 0;
line-height:1.4;
}

.blog-title a{
text-decoration:none;
color:#222;
}

.blog-title a:hover{
color:#0066cc;
}

.article-meta{
font-size:13px;
color:#888;
margin-bottom:8px;
}

.blog-excerpt{
color:#555;
font-size:14px;
line-height:1.6;
margin-bottom:8px;
}

.read-more{
color:#0066cc;
font-weight:600;
text-decoration:none;
font-size:14px;
}

.read-more:hover{
text-decoration:underline;
}

/* MOBILE */

@media(max-width:900px){

.article-list{
grid-template-columns:1fr;
}

.blog-card{
flex-direction:column;
}

.blog-image{
width:100%;
height:200px;
}

}

</style>


<section class="articles" data-aos="fade-up">
<div class="container">

<h2>Artikel</h2>

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

<?php $delay = 100; foreach ($articles as $article): ?>

<article class="blog-card" data-aos="fade-up" data-aos-delay="<?= $delay ?>">

<a href="<?= htmlspecialchars($articleBase . rawurlencode($article['slug'])) ?>" class="blog-image">

<?php if (!empty($article['featured_image'])): ?>
	<?php $img = (preg_match('/^https?:\/\//', $article['featured_image'])) ? $article['featured_image'] : $baseDir . '/' . ltrim($article['featured_image'], '/'); ?>
	<img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
<?php else: ?>
	<img src="https://source.unsplash.com/400x300/?<?= urlencode(strtolower($article['category'])) ?>">
<?php endif; ?>

</a>

<div class="blog-content">

<span class="badge"><?= htmlspecialchars($article['category']) ?></span>

<h4 class="blog-title">
<a href="<?= htmlspecialchars($articleBase . rawurlencode($article['slug'])) ?>">
<?= htmlspecialchars($article['title']) ?>
</a>
</h4>

<div class="article-meta">
<?= date('d M Y', strtotime($article['published_date'])) ?> • <?= $article['read_time'] ?> min read
</div>

<p class="blog-excerpt">
<?= htmlspecialchars($article['excerpt']) ?>
</p>

<a href="<?= htmlspecialchars($articleBase . rawurlencode($article['slug'])) ?>" class="read-more">
Baca Selengkapnya →
</a>

</div>

</article>

<?php $delay += 100; endforeach; ?>

<?php else: ?>

<div style="text-align:center;padding:60px;color:#999;grid-column:1/-1">
<h3>Belum ada artikel</h3>
<p>Artikel baru akan muncul di sini.</p>
</div>

<?php endif; ?>

</div>
</div>
</section>

<?php include 'partials/footer.php'; ?>