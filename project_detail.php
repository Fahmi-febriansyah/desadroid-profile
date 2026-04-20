<?php
require_once 'config/db.php';
// Get project id
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("HTTP/1.1 404 Not Found");
    echo 'Proyek tidak ditemukan.';
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $project = $stmt->fetch();
} catch (Exception $e) {
    $project = false;
}

if (!$project) {
    header("HTTP/1.1 404 Not Found");
    echo 'Proyek tidak ditemukan.';
    exit;
}

// Jika proyek belum memiliki link eksternal, arahkan ke halaman error/maintenance
if (empty($project['link'])) {
    header('Location: error/index.html');
    exit;
}

// fetch up to 5 images from project_images table
try {
    $imgStmt = $pdo->prepare('SELECT image_url FROM project_images WHERE project_id = :id ORDER BY id ASC LIMIT 5');
    $imgStmt->execute(['id' => $id]);
    $images = $imgStmt->fetchAll();
} catch (Exception $e) {
    $images = [];
}

// fetch associated outgoing letter (MOU) if any
try {
    $letterStmt = $pdo->prepare('SELECT id, letter_number, title, content, attachments, created_at FROM outgoing_letters WHERE project_id = :id LIMIT 1');
    $letterStmt->execute(['id' => $id]);
    $mou = $letterStmt->fetch();
} catch (Exception $e) {
    $mou = false;
}

include 'partials/header.php';
?>

<style>
 .project-detail{padding:3rem 0}
 .project-hero{display:grid;grid-template-columns:1fr 420px;gap:2rem;align-items:start}
 .project-meta{background:#fff;padding:18px;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.06)}
 .gallery{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-top:1rem}
 .gallery img{width:100%;height:220px;object-fit:cover;border-radius:8px}
 .back-link{display:inline-block;margin-top:12px;color:#0066cc}
 .progress{background:#eef2ff;border-radius:999px;height:12px;overflow:hidden}
 .progress-bar{height:12px;background:linear-gradient(90deg,#0066cc,#00aaff);display:block}
 @media(max-width:900px){.project-hero{grid-template-columns:1fr}}
</style>

<section class="project-detail container" data-aos="fade-up">
    <div class="project-hero">
        <div>
            <h1><?= htmlspecialchars($project['title']) ?></h1>
            <div style="color:#666;margin-bottom:8px"><?= htmlspecialchars($project['category']) ?></div>
            <?php $progress = isset($project['progress']) ? intval($project['progress']) : 0; ?>
            <div class="progress" aria-hidden="true">
                <span class="progress-bar" style="width: <?= $progress ?>%;"></span>
            </div>
            <div style="margin-top:8px;font-size:14px;color:#444"><?= $progress ?>% Selesai</div>
            <p style="margin-top:16px;color:#333;line-height:1.6"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            <?php if ($project['link']): ?><p><a href="<?= htmlspecialchars($project['link']) ?>" target="_blank">Lihat project</a></p><?php endif; ?>
            <?php if ($project['code_link']): ?><p><a href="<?= htmlspecialchars($project['code_link']) ?>" target="_blank">Lihat Kode</a></p><?php endif; ?>
            <a href="proyek.php" class="back-link">← Kembali ke Proyek</a>
        </div>
        <aside class="project-meta">
            <h3>Galeri Proyek</h3>
            <div class="gallery">
                <?php if (!empty($images)): foreach ($images as $img): ?>
                    <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
                <?php endforeach; else: ?>
                    <?php for ($i=0;$i<5;$i++): ?>
                        <img src="https://source.unsplash.com/featured/600x400/?<?= urlencode(strtolower($project['category'])) ?>,office" alt="placeholder">
                    <?php endfor; ?>
                <?php endif; ?>
            </div>

            <?php if ($mou): ?>
                <hr style="margin:12px 0;">
                <h3>MOU / Surat Terkait</h3>
                <div style="font-weight:700;color:#0066cc;margin-bottom:6px"><?= htmlspecialchars($mou['letter_number']) ?></div>
                <div style="margin-bottom:8px;font-size:15px"><?= htmlspecialchars($mou['title']) ?></div>
                <div style="font-size:13px;color:#666;margin-bottom:8px">Diterbitkan: <?= date('d M Y', strtotime($mou['created_at'])) ?></div>
                <a href="letter.php?id=<?= intval($mou['id']) ?>" class="btn tertiary" style="display:inline-block;margin-top:6px">Lihat MOU</a>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
