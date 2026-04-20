<?php
require_once 'config/db.php';
include 'partials/header.php';
?>

<style>

.projects-section{
padding:4rem 0;
background:#f9fafc;
}

.section-title{
text-align:center;
font-size:2rem;
margin-bottom:3rem;
}

.project-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:30px;
}

.project-card{
background:#fff;
border-radius:10px;
overflow:hidden;
box-shadow:0 6px 20px rgba(0,0,0,0.08);
transition:all .3s ease;
display:flex;
flex-direction:column;
height:100%;
}

.project-card:hover{
transform:translateY(-6px);
box-shadow:0 12px 35px rgba(0,0,0,0.12);
}

.project-image{position:relative;flex:0}
.project-image img{width:100%;height:200px;object-fit:cover;display:block}

.project-category{
position:absolute;
top:12px;
left:12px;
background:#0066cc;
color:#fff;
padding:4px 10px;
font-size:12px;
border-radius:4px;
}

.project-overlay{
position:absolute;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,.55);
display:flex;
justify-content:center;
align-items:center;
gap:10px;
opacity:0;
transition:.3s;
}

.project-card:hover .project-overlay{
opacity:1;
}

.overlay-btn{
background:#fff;
color:#000;
padding:6px 12px;
border-radius:4px;
font-size:14px;
text-decoration:none;
font-weight:600;
}

.project-content{padding:20px;flex:1;display:flex;flex-direction:column}
.project-content h4{margin-bottom:10px;font-size:18px}
.project-content p{font-size:14px;color:#555;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;}

.btn-case{
text-decoration:none;
color:#0066cc;
font-weight:600;
}

.progress-wrap{margin:10px 0 0;margin-top:auto}
.progress{background:#eef2ff;border-radius:999px;height:10px;overflow:hidden}
.progress-bar{height:10px;background:linear-gradient(90deg,#0066cc,#00aaff);display:block}
.progress-label{font-size:12px;color:#444;margin-top:6px}

.empty-project{
text-align:center;
padding:60px;
color:#999;
}

.empty-title{
font-size:18px;
margin-bottom:10px;
}

</style>

<section class="projects-section" data-aos="fade-up">

<div class="container">

<h2 class="section-title">Proyek Kami</h2>

<?php
try {
$projects_query = $pdo->query("SELECT * FROM projects ORDER BY order_num ASC, created_at DESC");
$projects = $projects_query->fetchAll();
} catch (Exception $e) {
$projects = [];
}
?>

<?php if (!empty($projects)): ?>

<div class="project-grid">

<?php $delay = 100; foreach ($projects as $project): ?>

<div class="project-card" data-aos="flip-left" data-aos-delay="<?= $delay ?>">

<div class="project-image">

<?php if ($project['image_url']): ?>
<img src="<?= htmlspecialchars($project['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
<?php else: ?>
<img src="https://source.unsplash.com/400x250/?<?= urlencode(strtolower($project['category'])) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
<?php endif; ?>

<span class="project-category">
<?= htmlspecialchars($project['category']) ?>
</span>

			<div class="project-overlay">

			<?php
				$hasLink = !empty($project['link']);
				$viewHref = $hasLink ? htmlspecialchars($project['link']) : '../error/index.html';
				$viewTarget = $hasLink ? ' target="_blank"' : '';
			?>
			<a href="<?= $viewHref ?>"<?= $viewTarget ?> class="overlay-btn"><?= $hasLink ? 'View' : 'Info' ?></a>

			<?php if (!empty($project['code_link'])): ?>
			<a href="<?= htmlspecialchars($project['code_link']) ?>" target="_blank" class="overlay-btn">Code</a>
			<?php endif; ?>

			</div>

</div>

<div class="project-content">

<h4><?= htmlspecialchars($project['title']) ?></h4>

<?php $short = mb_strimwidth(trim($project['description'] ?? ''), 0, 140, '...'); ?>
<p style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($short) ?></p>

<div class="progress-wrap">
	<?php $progress = isset($project['progress']) ? intval($project['progress']) : 0; ?>
	<div class="progress" aria-hidden="true">
		<span class="progress-bar" style="width: <?= $progress ?>%;"></span>
	</div>
	<div class="progress-label"><?= $progress ?>% Selesai</div>
	<a href="project_detail.php?id=<?= intval($project['id']) ?>" class="btn-case">Lihat Case Study</a>
</div>

</div>

</div>

<?php $delay += 100; endforeach; ?>

</div>

<?php else: ?>

<div class="empty-project">

<p class="empty-title">📋 Belum ada proyek yang ditambahkan</p>

<p>Hubungi kami untuk melihat portofolio lengkap atau mengajukan proyek baru.</p>

</div>

<?php endif; ?>

</div>

</section>

<?php include 'partials/footer.php'; ?>