<?php
require_once 'config/db.php';

$slug=$_GET['slug'] ?? '';
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
AND id!=?
ORDER BY RAND()
LIMIT 5
");

$stmt->execute([$article['id']]);
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

<style>

.article-container{
max-width:900px;
margin:auto;
padding:60px 20px;
position:relative;
}

.article-title{
font-size:36px;
font-weight:700;
line-height:1.3;
margin-bottom:10px;
}

.article-meta{
color:#666;
font-size:14px;
margin-bottom:25px;
}

/* HERO IMAGE */

.hero{
margin-bottom:30px;
}

.hero-img{
width:100%;
aspect-ratio:16/9;
overflow:hidden;
border-radius:10px;
}

.hero-img img{
width:100%;
height:100%;
object-fit:cover;
}

.hero-caption{
font-size:13px;
color:#888;
margin-top:6px;
}

/* CONTENT */

.article-content{
font-size:18px;
line-height:1.9;
color:#333;
}

/* SHARE SIDEBAR */

.share-bar{
position:sticky;
top:120px;
left:-70px;
float:left;
display:flex;
flex-direction:column;
gap:10px;
}

.share-btn{
width:40px;
height:40px;
border-radius:50%;
background:#f1f5f9;
display:flex;
align-items:center;
justify-content:center;
cursor:pointer;
}

.share-btn img{
width:18px;
}

/* BACA JUGA */

.baca-juga{
background:#f8fafc;
border-left:4px solid #0066cc;
padding:14px 16px;
margin:30px 0;
}

.baca-juga a{
font-weight:700;
text-decoration:none;
color:#0066cc;
}

/* RELATED */

.related-section{
margin-top:60px;
}

.related-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
}

.related-card{
background:#fff;
border-radius:10px;
overflow:hidden;
box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.related-card img{
width:100%;
height:150px;
object-fit:cover;
}

.related-card h4{
font-size:15px;
padding:10px;
line-height:1.4;
}

.related-card a{
text-decoration:none;
color:#111;
}

.related-card a:hover{
color:#0066cc;
}

@media(max-width:900px){

.share-bar{
display:none;
}

.related-grid{
grid-template-columns:1fr;
}

.article-title{
font-size:28px;
}

}

</style>


<section class="article-container">

<!-- SHARE BAR -->

<div class="share-bar">

<a class="share-btn"
href="https://wa.me/?text=<?=urlencode($canonical)?>"
target="_blank">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/whatsapp.svg">
</a>

<a class="share-btn"
href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($canonical)?>"
target="_blank">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/facebook.svg">
</a>

<div class="share-btn" onclick="copyLink()">
<img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/link.svg">
</div>

</div>


<!-- TITLE -->

<h1 class="article-title">
<?=htmlspecialchars($article['title'])?>
</h1>

<div class="article-meta">

<?=htmlspecialchars($article['author']??'Tim Kami')?>  
• <?=date('d M Y',strtotime($article['published_date']))?>

</div>


<!-- HERO IMAGE -->

<div class="hero">

<div class="hero-img">
<img src="<?=$heroImg?>">
</div>

<div class="hero-caption">
Ilustrasi teknologi • Desadroid
</div>

</div>


<!-- CONTENT -->

<div class="article-content" id="article-content">
<?=$article['content']?>
</div>



<!-- RELATED -->

<div class="related-section">

<h3>Artikel Terkait</h3>

<div class="related-grid">

<?php foreach($related as $r): ?>

<?php
$rImg=!empty($r['featured_image'])
?$r['featured_image']
:'https://source.unsplash.com/400x300/?technology';
?>

<div class="related-card">

<a href="<?=$baseDir?>/artikel/<?=rawurlencode($r['slug'])?>">

<img src="<?=$rImg?>">

<h4><?=htmlspecialchars($r['title'])?></h4>

</a>

</div>

<?php endforeach; ?>

</div>

</div>


</section>



<script>

/* COPY LINK */

function copyLink(){
navigator.clipboard.writeText(window.location.href);
alert("Link artikel disalin");
}


/* BACA JUGA INSERT */

const content=document.querySelector("#article-content");

let paragraphs=content.querySelectorAll("p");

if(paragraphs.length>3){

let baca=document.createElement("div");

baca.className="baca-juga";

baca.innerHTML=`Baca juga: 
<a href="<?=$baseDir?>/artikel/<?=rawurlencode($related[0]['slug']??'')?>">

<?=htmlspecialchars($related[0]['title']??'Artikel lainnya')?> 

</a>`;

paragraphs[2].after(baca);

}

</script>


<?php include 'partials/footer.php'; ?>