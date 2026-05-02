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

if (!empty($article['featured_image'])) {
    $img_path = $article['featured_image'];
    if (strpos($img_path, 'http') === 0) {
        // Untuk URL eksternal, encode hanya nama file saja
        $parsed = parse_url($img_path);
        if (!empty($parsed['path'])) {
            $pathParts = explode('/', $parsed['path']);
            $filename = array_pop($pathParts);
            $encodedFilename = rawurlencode($filename);
            $encodedPath = implode('/', $pathParts) . '/' . $encodedFilename;
            $heroImg = $parsed['scheme'] . '://' . $parsed['host'] . $encodedPath;
            if (!empty($parsed['query'])) {
                $heroImg .= '?' . $parsed['query'];
            }
        } else {
            $heroImg = $img_path;
        }
    } else {
        // Untuk path lokal, encode semua bagian path
        $img_path = ltrim($img_path, '/');
        $parts = explode('/', $img_path);
        $encoded_parts = array_map('rawurlencode', $parts);
        $safe_path = implode('/', $encoded_parts);
        $heroImg = $scheme . '://' . $host . '/' . $safe_path;
    }
} else {
    $heroImg = 'https://source.unsplash.com/1200x800/?technology';
}

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


<section class="article-wrap" data-reveal>


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
📋 Copy
</div>

<a class="share-btn"
href="https://wa.me/?text=<?=urlencode($canonical)?>"
target="_blank">
💬 WhatsApp
</a>

<a class="share-btn"
href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($canonical)?>"
target="_blank">
📘 Facebook
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



<div class="related" data-reveal>

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