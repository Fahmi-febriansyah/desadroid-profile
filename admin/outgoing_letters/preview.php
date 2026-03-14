<?php
require_once '../../config/db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo 'Invalid letter id';
    exit;
}
try {
    $stmt = $pdo->prepare('SELECT o.*, u.full_name, u.signature_file AS creator_signature FROM outgoing_letters o LEFT JOIN admin_users u ON o.created_by = u.id WHERE o.id = ? LIMIT 1');
    $stmt->execute([$id]);
    $letter = $stmt->fetch();
    if (!$letter) {
        echo 'Letter not found';
        exit;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}
// helper: convert stored path or file path to web-safe URL
function web_url($path) {
    if (!$path) return false;
    // if already absolute URL
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) return $path;
    // if file:// strip and convert
    if (strpos($path, 'file://') === 0) {
        $p = substr($path, 7);
    } else {
        $p = $path;
    }
    // if absolute filesystem path, map to web path by removing document root
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    if (strpos($p, $docRoot) === 0) {
        $web = substr($p, strlen($docRoot));
    } else {
        // assume it's already a web-root relative path like '/desadroid/...'
        $web = $p;
    }
    // normalize slashes and encode segments to handle spaces
    $web = str_replace('\\', '/', $web);
    $parts = explode('/', ltrim($web, '/'));
    $enc = array_map('rawurlencode', $parts);
    return '/' . implode('/', $enc);
}

function indo_date($time = null) {
    $time = $time ? strtotime($time) : time();
    $months = [1=> 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $d = date('j', $time);
    $m = $months[intval(date('n', $time))];
    $y = date('Y', $time);
    return "$d $m $y";
}
?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preview Surat - <?= htmlspecialchars($letter['letter_number']) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 2rem; color: #222; background: #f6f7f9; }
        .letterhead { display:flex; align-items:center; gap:1rem; margin-bottom:0.75rem; }
        .letterhead img { max-height:72px; }
        .letterhead .title { flex:1; text-align:center; }
        .letterhead h1 { margin:0; font-size:28px; letter-spacing:1px; }
        .letterhead p { margin:0; font-size:14px; color:#333; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem; }
        .letter-number { font-weight:700; color:#0066cc; }
        .meta { font-size:0.9rem; color:#555; }
        .content { background:#fff; padding:1.5rem; border-radius:6px; box-shadow:0 6px 18px rgba(0,0,0,0.04); }
        .signature { margin-top:2.5rem; text-align:left; }
        .signature img { max-width:220px; height:auto; }
        .print-btn { position: fixed; right: 16px; top: 16px; padding:0.6rem 0.9rem; background:#0066cc; color:#fff; border-radius:6px; text-decoration:none; }
        @media print { .print-btn { display:none } .main-wrap { padding:0 } }
    </style>
</head>
<body>
    <a class="print-btn" href="#" onclick="window.print();return false;">Print / Save as PDF</a>
    <a class="print-btn" style="right:140px;background:#16a34a;" href="./generate_pdf.php?id=<?= $letter['id'] ?>">Download PDF</a>
    <div class="main-wrap">
    <div class="letterhead">
        <?php
            // prefer project logo, convert to web URL for browser preview
            $logoPath = realpath(__DIR__ . '/../../src/img/logo.png');
            if ($logoPath && file_exists($logoPath)) {
                $logoWeb = web_url($logoPath);
            } else {
                $logoWeb = web_url('../../src/img/logo.png');
            }
        ?>
        <?php if ($logoWeb): ?>
            <img src="<?= $logoWeb ?>" alt="Logo">
        <?php else: ?>
            <div style="width:120px;height:72px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:8px;border:1px solid #eee;color:#0070c9;font-weight:700;">desadroid</div>
        <?php endif; ?>
        <div class="title">
            <h1>DESADROID IT CONSULTANT</h1>
            <p>IT Development & Digital Solutions<br>Jl. Letda Natsir, Cikeas Udik, Nagrak, Kabupaten Bogor, Jawa Barat<br>e-mail: consulting@desadroid.shop · Telp: 085183252240</p>
        </div>
        <div style="width:120px;">&nbsp;</div>
    </div>

    <div class="header">
        <div>
            <div class="letter-number"><?= htmlspecialchars($letter['letter_number']) ?></div>
            <div class="meta"><?= htmlspecialchars($letter['title']) ?></div>
        </div>
        <div style="text-align:right;">
            <div>To: <?= htmlspecialchars($letter['recipient'] ?? '-') ?></div>
            <div>From: <?= htmlspecialchars($letter['full_name'] ?? 'Admin') ?></div>
            <div><?= htmlspecialchars(indo_date($letter['created_at'] ?? null)) ?></div>
        </div>
    </div>

    <div class="content">
        <?= $letter['content'] ?>

        <?php if (!empty($letter['attachments'])): ?>
            <div style="margin-top:1rem;">
                Attachment: <a href="<?= web_url($letter['attachments']) ?>" target="_blank">Lihat</a>
            </div>
        <?php endif; ?>

        <div class="signature" style="display:flex;justify-content:space-between;margin-top:28px;align-items:flex-end;">
            <div style="width:45%;text-align:left;">
                <div><?= 'Bogor, ' . indo_date($letter['created_at'] ?? null) ?></div>
                <?php
                    $leftSig = false;
                    if (!empty($letter['creator_signature'])) $leftSig = web_url($letter['creator_signature']);
                    if (!$leftSig && !empty($letter['signature_file'])) $leftSig = web_url($letter['signature_file']);
                ?>
                <?php if ($leftSig): ?>
                    <img src="<?= $leftSig ?>" alt="Signature" style="max-width:220px;margin-top:8px;display:block;">
                <?php endif; ?>
                <div style="font-weight:700;margin-top:6px;"><?= htmlspecialchars($letter['full_name'] ?? 'Fahmi Febriansyah') ?></div>
                <div>Founder</div>
            </div>
            <div style="width:45%;text-align:right;">
                <div><?= htmlspecialchars($letter['recipient'] ?? 'Penerima') ?></div>
                <div style="font-weight:700;margin-top:36px;">(<?= htmlspecialchars($letter['recipient'] ?? 'Penerima') ?>)</div>
                <div style="font-size:10pt;color:#666;">Penerima / Customer</div>
            </div>
        </div>
    </div>
</body>
</html>