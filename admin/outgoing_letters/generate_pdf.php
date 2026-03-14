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

// Load Composer autoload (required for Dompdf)
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Check if Dompdf is available
if (!class_exists('Dompdf\\Dompdf')) {
    echo '<h2>Dompdf belum terpasang</h2>';
    echo '<p>Untuk mengaktifkan fitur ini, jalankan di terminal (folder project):</p>';
    echo '<pre>composer require dompdf/dompdf</pre>';
    echo '<p>Setelah terpasang, kembali dan coba lagi.</p>';
    exit;
}

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// build HTML (reuse preview layout but simplified for PDF)
// helper: resolve file path for images (convert web path to absolute file:// path)
function resolve_file_url($webPath) {
    if (!$webPath) return false;
    if (strpos($webPath, 'file://') === 0) return $webPath;
    if (strpos($webPath, 'http://') === 0 || strpos($webPath, 'https://') === 0) return $webPath;
    // Try project-relative path first (this repo), then doc root fallback
    $projectRoot = realpath(__DIR__ . '/../../');
    $candidate = $projectRoot . DIRECTORY_SEPARATOR . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $webPath), DIRECTORY_SEPARATOR);
    if (file_exists($candidate)) return 'file://' . $candidate;
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $full = $docRoot . str_replace('/', DIRECTORY_SEPARATOR, $webPath);
    if (file_exists($full)) return 'file://' . $full;
    return false;
}

ob_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 36pt 48pt; }
        body { font-family: 'Times New Roman', Times, serif; color:#222; }
        .letterhead { display:flex; align-items:center; gap:1rem; margin-bottom:6pt; }
        .letterhead img { max-height:80px; }
        .letterhead .title { flex:1; text-align:center; }
        .letterhead h1 { margin:0; font-size:16pt; letter-spacing:1px; font-weight:700; }
        .letterhead p { margin:0; font-size:10pt; }
        .line { border-top:3px solid #000; margin-top:8px; margin-bottom:12px; }
        .meta { font-size:10pt; color:#333; margin-bottom:8px; }
        h2.center { text-align:center; font-size:12pt; font-weight:700; margin:18px 0; }
        .content { margin-top:6pt; font-size:11pt; line-height:1.6; }
        .signature { margin-top:36px; display:flex; justify-content:space-between; }
        .sig-block { width:45%; text-align:left; }
        .sig-block.right { text-align:right; }
        .sig-img { max-width:220px; height:auto; display:block; margin-bottom:6px; }
    </style>
</head>
<body>
    <div class="letterhead">
            <?php
                // prefer project-relative logo (relative to this file)
                $logoPath = realpath(__DIR__ . '/../../src/img/logo.png');
                if ($logoPath && file_exists($logoPath)) {
                    $logoFile = 'file://' . $logoPath;
                } else {
                    $logoFile = resolve_file_url('../../src/img/logo.png');
                }
            ?>
            <?php if ($logoFile): ?>
                <img src="<?= $logoFile ?>" alt="Logo">
            <?php endif; ?>
        <div class="title">
            <h1>DESADROID IT CONSULTANT</h1>
            <p>IT Development & Digital Solutions<br>Jl. Letda Natsir, Cikeas Udik, Nagrak, Kabupaten Bogor, Jawa Barat<br>e-mail: consulting@desadroid.shop · Telp: 085183252240</p>
        </div>
    </div>

    <div class="meta">Nomor: <?= htmlspecialchars($letter['letter_number']) ?></div>
    <h2 class="center"><?= htmlspecialchars($letter['title']) ?></h2>

    <div class="content">
        <?= $letter['content'] ?>
    </div>

    <?php
        // prepare signature images: prefer creator signature (admin) as left sign, fallback to letter signature
        $leftSig = false;
        if (!empty($letter['creator_signature'])) $leftSig = resolve_file_url($letter['creator_signature']);
        if (!$leftSig && !empty($letter['signature_file'])) $leftSig = resolve_file_url($letter['signature_file']);

        $rightName = $letter['recipient'] ?? 'Penerima';
        // format date in Indonesian
        function indo_date($time = null) {
            $time = $time ? strtotime($time) : time();
            $months = [1=> 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            $d = date('j', $time);
            $m = $months[intval(date('n', $time))];
            $y = date('Y', $time);
            return "$d $m $y";
        }
        $today = indo_date();
        $creatorName = $letter['full_name'] ?? 'Fahmi Febriansyah';
        $creatorTitle = 'Founder';
    ?>

    <div class="signature">
        <div class="sig-block left">
            <div><?= 'Bogor, ' . $today ?></div>
            <?php if ($leftSig): ?><img class="sig-img" src="<?= $leftSig ?>" alt="Signature"><?php endif; ?>
            <div style="font-weight:700;margin-top:6px;"><?= htmlspecialchars($creatorName) ?></div>
            <div><?= htmlspecialchars($creatorTitle) ?></div>
        </div>
        <div class="sig-block right">
            <div><?= htmlspecialchars($rightName) ?></div>
            <div style="font-weight:700;margin-top:36px;">(<?= htmlspecialchars($rightName) ?>)</div>
            <div style="font-size:10pt;color:#666;">Penerima / Customer</div>
        </div>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = preg_replace('/[^A-Za-z0-9_-]/', '_', $letter['letter_number'] ?: 'surat') . '.pdf';
$dompdf->stream($filename, ['Attachment' => 1]);
exit;
?>