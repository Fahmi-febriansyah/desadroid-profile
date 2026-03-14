<?php 
require_once 'config/db.php';

// base path
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$articleBase = $baseDir . '/artikel/';
?>

<?php include 'partials/header.php'; ?>

<style>

.articles{
padding:80px 20px;
background:#f6f8fc;
}

.container{
max-width:1200px;
margin:auto;
}

.section-title{
text-align:center;
margin-bottom:40px;
}

.section-title h2{
font-size:32px;
font-weight:700;
color:#222;
margin-bottom:10px;
}

.section-title p{
color:#666;
font-size:15px;
}

/* GRID */

.article-list{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:28px;
}

/* CARD */

.blog-card{
display:flex;
gap:20px;
background:#fff;
border-radius:14px;
overflow:hidden;
box-shadow:0 6px 18px rgba(0,0,0,0.06);
transition:all .3s ease;
padding:18px;
}

.blog-card:hover{
transform:translateY(-6px);
box-shadow:0 14px 40px rgba(0,0,0,0.08);
}

/* IMAGE */

.blog-image{
width:150px;
height:120px;
border-radius:10px;
overflow:hidden;
flex-shrink:0;
}

.blog-image img{
width:100%;
height:100%;
object-fit:cover;
}

/* CONTENT */

.blog-content{
flex:1;
display:flex;
flex-direction:column;
}

.badge{
display:inline-block;
background:#0d6efd;
color:#fff;
font-size:12px;
padding:4px 10px;
border-radius:20px;
margin-bottom:8px;
}

.blog-title{
font-size:18px;
font-weight:700;
line-height:1.4;
margin-bottom:6px;
}

.blog-title a{
color:#222;
text-decoration:none;
}

.blog-title a:hover{
color:#0d6efd;
}

.article-meta{
font-size:13px;
color:#888;
margin-bottom:8px;
}

.blog-excerpt{
font-size:14px;
color:#555;
line-height:1.6;
margin-bottom:10px;

display:-webkit-box;
-webkit-line-clamp:3;
-webkit-box-orient:vertical;
overflow:hidden;
}

.read-more{
font-size:14px;
font-weight:600;
color:#0d6efd;
text-decoration:none;
margin-top:auto;
}

.read-more:hover{
text-decoration:underline;
}

/* EMPTY STATE */

.empty{
text-align:center;
padding:60px;
color:#888;
grid-column:1/-1;
}

/* RESPONSIVE */

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


<section class="articles">

<div class="container">

<div class="section-title">
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

<article class="blog-card">

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

<span class="badge">
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