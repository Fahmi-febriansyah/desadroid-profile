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

<section class="container" style="padding:3rem 0">
    <h1><?= htmlspecialchars($letter['letter_number'] . ' — ' . $letter['title']) ?></h1>
    <div style="color:#666;margin-bottom:12px">Diterbitkan: <?= date('d M Y', strtotime($letter['created_at'])) ?></div>
    <div style="background:#fff;padding:1.2rem;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06)">
        <?= $letter['content'] ?>
    </div>

    <?php if (!empty($letter['attachments'])): ?>
        <div style="margin-top:1rem">
            <h3>Lampiran</h3>
            <?php
            $files = explode(';', $letter['attachments']);
            foreach ($files as $f): if (trim($f)==='') continue; $url = htmlspecialchars($f); ?>
                <div><a href="<?= $url ?>" target="_blank"><?= basename($f) ?></a></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div style="margin-top:1.2rem"><a href="proyek.php">← Kembali ke Proyek</a></div>
</section>

<?php include 'partials/footer.php'; ?>
