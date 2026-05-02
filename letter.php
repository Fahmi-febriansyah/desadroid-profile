<?php
require_once 'config/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('HTTP/1.1 404 Not Found');
    echo 'Surat tidak ditemukan.';
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM outgoing_letters WHERE id = ? AND project_id IS NOT NULL LIMIT 1');
    $stmt->execute([$id]);
    $letter = $stmt->fetch();
} catch (Exception $e) {
    $letter = false;
}

if (!$letter) {
    header('HTTP/1.1 404 Not Found');
    echo 'Surat tidak ditemukan atau tidak terkait proyek.';
    exit;
}

include 'partials/header.php';
?>

<section class="article-wrap" data-reveal>
    <h1 class="article-title"><?= htmlspecialchars($letter['letter_number'] . ' — ' . $letter['title']) ?></h1>
    <div style="color:var(--text2);margin-bottom:12px">Diterbitkan: <?= date('d M Y', strtotime($letter['created_at'])) ?></div>
    <div class="article-content" style="background:var(--surface);padding:1.5rem;border-radius:var(--radius);border:1px solid rgba(255,255,255,.06)">
        <?= $letter['content'] ?>
    </div>

    <?php if (!empty($letter['attachments'])): ?>
        <div style="margin-top:1.5rem">
            <h3 style="color:var(--text);margin-bottom:.5rem">Lampiran</h3>
            <?php
            $files = explode(';', $letter['attachments']);
            foreach ($files as $f): if (trim($f)==='') continue; $url = htmlspecialchars($f); ?>
                <div style="margin-bottom:.5rem"><a href="<?= $url ?>" target="_blank"><?= basename($f) ?></a></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="margin-top:1.5rem"><a href="proyek.php" class="back-link">← Kembali ke Proyek</a></div>
</section>

<?php include 'partials/footer.php'; ?>
