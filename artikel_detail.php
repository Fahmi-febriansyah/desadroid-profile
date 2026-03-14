<?php
require_once 'config/db.php';

$slug = $_GET['slug'] ?? '';
$article=null;
$related=[];

$baseDir=rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\');
if($baseDir==='/')$baseDir='';

if($slug){

$stmt=$pdo->prepare("SELECT * FROM articles WHERE slug=? AND status='published'");
$stmt->execute([$slug]);
$article=$stmt->fetch();

if($article){

$stmt=$pdo->prepare("
SELECT * FROM articles
WHERE status='published'
AND category=?
AND id!=?
ORDER BY RAND()
LIMIT 3
");

$stmt->execute([
$article['category'],
$article['id']
]);

$related=$stmt->fetchAll();

}

}

if(!$article){
echo "Artikel tidak ditemukan";
exit;
}

$scheme=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http';
$host=$_SERVER['HTTP_HOST'];

$canonical=$scheme.'://'.$host.$baseDir.'/artikel/'.rawurlencode($article['slug']);

$heroImg=!empty($article['featured_image'])
?$article['featured_image']
:'https://source.unsplash.com/1200x800/?technology';

?>

<?php include 'partials/header.php'; ?>


<!-- SEO SCHEMA -->
<script type="application/ld+json">
{
"@context":"https://schema.org",
"@type":"Article",
"headline":"<?=htmlspecialchars($article['title'])?>",
"image":"<?=$heroImg?>",
"author":{
"@type":"Person",
"name":"<?=htmlspecialchars($article['author']??'Desadroid')?>"
},
"publisher":{
"@type":"Organization",
"name":"Desadroid",
"logo":{
"@type":"ImageObject",
"url":"<?=$baseDir?>/logo.png"
}
},
"datePublished":"<?=$article['published_date']?>",
"mainEntityOfPage":"<?=$canonical?>"
}
</script>


<style>

.reading-progress{
position:fixed;
top:0;
left:0;
height:4px;
background:#0066cc;
width:0;
z-index:999;
}

.article-wrapper{
max-width:1200px;
margin:auto;
padding:80px 20px;
display:grid;
grid-template-columns:3fr 1fr;
gap:50px;
}

.article-card{
background:#fff;
padding:40px;
border-radius:14px;
box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

/* HERO */

.hero-img{
width:100%;
aspect-ratio:16/9;
overflow:hidden;
border-radius:12px;
margin-bottom:30px;
}

.hero-img img{
width:100%;
height:100%;
object-fit:cover;
}

/* TITLE */

.article-title{
font-size:34px;
font-weight:700;
margin-bottom:10px;
}

.meta{
color:#777;
font-size:14px;
margin-bottom:20px;
}

/* CONTENT */

.article-content{
font-size:18px;
line-height:1.9;
color:#333;
}

/* TOC */

.toc{
background:#f8fafc;
border-radius:10px;
padding:18px;
margin-bottom:25px;
}

.toc h4{
margin-bottom:10px;
font-size:16px;
}

.toc a{
display:block;
font-size:14px;
color:#0066cc;
margin-bottom:6px;
text-decoration:none;
}

/* SIDEBAR */

.sidebar{
position:sticky;
top:100px;
}

.sidebar-card{
background:#fff;
padding:20px;
border-radius:10px;
box-shadow:0 6px 20px rgba(0,0,0,0.05);
margin-bottom:20px;
}

.related-item{
margin-bottom:12px;
}

.related-item a{
font-weight:600;
color:#111;
text-decoration:none;
}

.related-item a:hover{
color:#0066cc;
}

/* SHARE */

.share{
display:flex;
gap:10px;
margin-top:20px;
}

.share-btn{
width:38px;
height:38px;
background:#f1f5f9;
border-radius:8px;
display:flex;
align-items:center;
justify-content:center;
cursor:pointer;
}

.share-btn img{
width:18px;
}

@media(max-width:900px){

.article-wrapper{
grid-template-columns:1fr;
}

}

</style>


<div class="reading-progress"></div>


<section class="article-wrapper">

<article class="article-card">

<div class="hero-img">
<img src="<?=$heroImg?>">
</div>

<h1 class="article-title">
<?=htmlspecialchars($article['title'])?>
</h1>

<div class="meta">
<?=date('d M Y',strtotime($article['published_date']))?>
•
<?=htmlspecialchars($article['author']??'Tim Kami')?>
</div>


<!-- TOC -->
<div class="toc">
<h4>Daftar Isi</h4>
<div id="toc-list"></div>
</div>


<div class="article-content" id="article-content">
<?=$article['content']?>
</div>


<div class="share">

<div class="share-btn" onclick="copyLink()">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/link.svg">
</div>

<a class="share-btn"
href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($canonical)?>"
target="_blank">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/facebook.svg">
</a>

<a class="share-btn"
href="https://wa.me/?text=<?=urlencode($canonical)?>"
target="_blank">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/whatsapp.svg">
</a>

</div>

</article>


<!-- SIDEBAR -->

<aside class="sidebar">

<div class="sidebar-card">

<h4>Artikel Terkait</h4>

<?php foreach($related as $r): ?>

<div class="related-item">
<a href="<?=$baseDir?>/artikel/<?=rawurlencode($r['slug'])?>">
<?=htmlspecialchars($r['title'])?>
</a>
</div>

<?php endforeach; ?>

</div>


<div class="sidebar-card">

<h4>Share Artikel</h4>

<p style="font-size:14px;color:#666">
Bagikan artikel ini ke teman atau sosial media.
</p>

</div>

</aside>

</section>



<script>

/* reading progress */

window.addEventListener("scroll",()=>{

let winScroll=document.documentElement.scrollTop;
let height=document.documentElement.scrollHeight-document.documentElement.clientHeight;

let scrolled=(winScroll/height)*100;

document.querySelector(".reading-progress").style.width=scrolled+"%";

});


/* copy link */

function copyLink(){

navigator.clipboard.writeText(window.location.href);

alert("Link disalin");

}


/* generate TOC */

const content=document.querySelector("#article-content");
const toc=document.querySelector("#toc-list");

const headers=content.querySelectorAll("h2,h3");

headers.forEach((h,i)=>{

let id="section-"+i;

h.id=id;

let a=document.createElement("a");

a.href="#"+id;

a.innerText=h.innerText;

toc.appendChild(a);

});

</script>


<?php include 'partials/footer.php'; ?>