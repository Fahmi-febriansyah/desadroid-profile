<?php
require_once 'config/db.php';

$slug = $_GET['slug'] ?? '';
$article = null;
$related = [];
$allArticles = [];

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';

if ($slug) {

$stmt = $pdo->prepare("SELECT * FROM articles WHERE slug=? AND status='published'");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if ($article) {

$stmt = $pdo->prepare("
SELECT * FROM articles
WHERE status='published'
AND id!=?
ORDER BY published_date DESC
LIMIT 6
");
$stmt->execute([$article['id']]);
$related = $stmt->fetchAll();

$allArticles = $pdo->query("
SELECT title,slug
FROM articles
WHERE status='published'
")->fetchAll();

}

}

if (!$article) {
echo "Artikel tidak ditemukan";
exit;
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

$canonical = $scheme.'://'.$host.$baseDir.'/artikel/'.rawurlencode($article['slug']);

$heroImg = !empty($article['featured_image'])
? $scheme.'://'.$host.'/'.$article['featured_image']
: 'https://source.unsplash.com/1200x800/?technology';

?>
<?php include 'partials/header.php'; ?>


<!-- SEO META -->

<meta name="description" content="<?=htmlspecialchars($article['excerpt'] ?? '')?>">

<meta property="og:type" content="article">
<meta property="og:title" content="<?=htmlspecialchars($article['title'])?>">
<meta property="og:description" content="<?=htmlspecialchars($article['excerpt'] ?? '')?>">
<meta property="og:image" content="<?=$heroImg?>">
<meta property="og:url" content="<?=$canonical?>">

<meta name="twitter:card" content="summary_large_image">


<style>

.article-wrap{
max-width:900px;
margin:auto;
padding:70px 20px;
}

.article-title{
font-size:38px;
font-weight:700;
line-height:1.3;
margin-bottom:15px;
}

.article-meta{
display:flex;
gap:15px;
color:#666;
font-size:14px;
margin-bottom:25px;
}

.hero-img{
width:100%;
aspect-ratio:16/9;
overflow:hidden;
border-radius:12px;
}

.hero-img img{
width:100%;
height:100%;
object-fit:cover;
}

.img-caption{
font-size:12px;
color:#777;
margin-top:6px;
}

.share-bar{
display:flex;
gap:12px;
overflow-x:auto;
padding:10px 0;
margin:20px 0;
}

.share-btn{
display:flex;
align-items:center;
gap:6px;
padding:8px 12px;
background:#f1f5f9;
border-radius:6px;
font-size:14px;
cursor:pointer;
white-space:nowrap;
}

.share-btn img{
width:16px;
}

.article-content{
font-size:19px;
line-height:1.9;
color:#333;
margin-top:20px;
}

.article-content p{
margin-bottom:22px;
}

.article-content a{
color:#0066cc;
font-weight:600;
text-decoration:none;
}

.article-content a:hover{
text-decoration:underline;
}

.read-more-box{
border-left:4px solid #0066cc;
background:#f8fafc;
padding:14px;
margin:30px 0;
}

.related{
margin-top:60px;
}

.related-grid{
display:grid;
grid-template-columns:1fr 1fr 1fr;
gap:20px;
}

.related-card{
background:#fff;
border-radius:10px;
overflow:hidden;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.related-img{
width:100%;
aspect-ratio:16/9;
overflow:hidden;
}

.related-img img{
width:100%;
height:100%;
object-fit:cover;
}

.related-body{
padding:12px;
}

.related-title{
font-weight:600;
font-size:14px;
line-height:1.4;
}

.related-title a{
text-decoration:none;
color:#111;
}

.related-title a:hover{
color:#0066cc;
}

@media(max-width:900px){

.related-grid{
grid-template-columns:1fr;
}

.article-title{
font-size:28px;
}

}

</style>


<section class="article-wrap">


<h1 class="article-title">
<?=htmlspecialchars($article['title'])?>
</h1>

<div class="article-meta">

<span>👤 <?=htmlspecialchars($article['author'] ?? 'Tim Kami')?></span>

<span>📅 <?=date('d M Y',strtotime($article['published_date']))?></span>

</div>


<div class="hero-img">
<img src="<?=$heroImg?>">
</div>

<div class="img-caption">
Ilustrasi teknologi Desadroid
</div>


<div class="share-bar">

<div class="share-btn" onclick="copyLink()">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/link.svg">
Copy
</div>

<a class="share-btn"
href="https://wa.me/?text=<?=urlencode($canonical)?>"
target="_blank">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/whatsapp.svg">
WhatsApp
</a>

<a class="share-btn"
href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($canonical)?>"
target="_blank">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/facebook.svg">
Facebook
</a>

</div>



<div class="article-content" id="content">

<?php
$content = '<p>'.implode('</p><p>', explode("\n", $article['content'])).'</p>';

/* AUTO INTERNAL LINK SEO */

foreach($allArticles as $a){

if($a['slug'] != $article['slug']){

$url = $baseDir.'/artikel/'.rawurlencode($a['slug']);

$content = preg_replace(
'/\b('.preg_quote($a['title'],'/').')\b/i',
'<a href="'.$url.'">$1</a>',
$content,
1
);

}

}

echo $content;

?>

</div>



<div class="related">

<h3>Artikel Terkait</h3>

<div class="related-grid">

<?php foreach($related as $r): ?>

<?php
$rImg=!empty($r['featured_image'])
?$r['featured_image']
:'https://source.unsplash.com/600x400/?technology';
?>

<div class="related-card">

<div class="related-img">
<img src="<?=$rImg?>">
</div>

<div class="related-body">

<div class="related-title">

<a href="<?=$baseDir?>/artikel/<?=rawurlencode($r['slug'])?>">
<?=htmlspecialchars($r['title'])?>
</a>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>


</section>


<script>

function copyLink(){

navigator.clipboard.writeText(window.location.href);

alert("Link artikel disalin");

}

/* BACA JUGA */

const paragraphs=document.querySelectorAll("#content p");

if(paragraphs.length>4){

let box=document.createElement("div");

box.className="read-more-box";

box.innerHTML='Baca juga: <a href="<?=$baseDir?>/artikel/<?=rawurlencode($related[0]['slug'] ?? '')?>"><?=htmlspecialchars($related[0]['title'] ?? 'Artikel lainnya')?></a>';

paragraphs[2].after(box);

}

if(paragraphs.length>7){

let box=document.createElement("div");

box.className="read-more-box";

box.innerHTML='Baca juga: <a href="<?=$baseDir?>/artikel/<?=rawurlencode($related[1]['slug'] ?? '')?>"><?=htmlspecialchars($related[1]['title'] ?? 'Artikel lainnya')?></a>';

paragraphs[5].after(box);

}

</script>


<?php include 'partials/footer.php'; ?>