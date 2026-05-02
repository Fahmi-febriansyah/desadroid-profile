<?php
require_once 'config/db.php';
include 'partials/header.php';
?>

<section class="projects-section" style="padding:5rem 0;" data-reveal>

<div class="container">

<div class="section-title">
<h2>Proyek Kami</h2>
</div>

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

<div class="project-card" data-reveal data-reveal-delay="<?= $delay ?>">

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
<p><?= htmlspecialchars($short) ?></p>

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