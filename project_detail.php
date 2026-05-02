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

<section class="project-detail container" data-reveal>
    <div class="project-hero">
        <div>
            <h1 style="color:var(--text)"><?= htmlspecialchars($project['title']) ?></h1>
            <div style="color:var(--text2);margin-bottom:8px"><?= htmlspecialchars($project['category']) ?></div>
            <?php $progress = isset($project['progress']) ? intval($project['progress']) : 0; ?>
            <div class="progress" aria-hidden="true">
                <span class="progress-bar" style="width: <?= $progress ?>%;"></span>
            </div>
            <div style="margin-top:8px;font-size:14px;color:var(--text2)"><?= $progress ?>% Selesai</div>
            <p style="margin-top:16px;color:var(--text2);line-height:1.6"><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            <?php if ($project['link']): ?><p><a href="<?= htmlspecialchars($project['link']) ?>" target="_blank">Lihat project</a></p><?php endif; ?>
            <?php if ($project['code_link']): ?><p><a href="<?= htmlspecialchars($project['code_link']) ?>" target="_blank">Lihat Kode</a></p><?php endif; ?>
            <a href="proyek.php" class="back-link">← Kembali ke Proyek</a>
        </div>
        <aside class="project-meta">
            <h3 style="color:var(--text)">Galeri Proyek</h3>
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
                <hr style="margin:12px 0;border-color:rgba(255,255,255,.06);">
                <h3 style="color:var(--text)">MOU / Surat Terkait</h3>
                <div style="font-weight:700;color:var(--accent1);margin-bottom:6px"><?= htmlspecialchars($mou['letter_number']) ?></div>
                <div style="margin-bottom:8px;font-size:15px;color:var(--text2)"><?= htmlspecialchars($mou['title']) ?></div>
                <div style="font-size:13px;color:var(--text2);margin-bottom:8px">Diterbitkan: <?= date('d M Y', strtotime($mou['created_at'])) ?></div>
                <a href="letter.php?id=<?= intval($mou['id']) ?>" class="btn tertiary" style="display:inline-block;margin-top:6px">Lihat MOU</a>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
