<?php
http_response_code(500);


$pageTitle = '500 — Kesalahan Server Internal | Desadroid';
$metaDescription = 'Terjadi kendala teknis internal pada sistem kami. Tim kami sedang menanganinya.';
$metaRobots = 'noindex, nofollow';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; });
$baseDirUrl = '';
if (!empty($baseDirSegments)) {
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments));
}

include __DIR__ . '/partials/header.php';
?>

<section class="error-page" data-reveal>
    <div class="container">
        <div class="error-wrapper">
            <div class="error-visual error-visual-500">
                <div class="error-glow error-glow-red"></div>
                <span class="error-code">500</span>
                <div class="error-badge error-badge-red">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                    <span>Server Issue</span>
                </div>
            </div>

            <div class="error-content">
                <h1>Terjadi Kendala pada Sistem Kami (500)</h1>
                <p class="error-desc">
                    Mohon maaf atas ketidaknyamanan ini. Server kami mengalami kendala tak terduga saat memproses permintaan Anda. 
                    Tim teknis kami telah menerima notifikasi dan sedang mengatasinya sesegera mungkin.
                </p>

                <div class="error-actions">
                    <button onclick="window.location.reload();" class="btn primary" style="cursor: pointer;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                        <span>Coba Muat Ulang</span>
                    </button>
                    <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/' : $baseDirUrl . '/')) ?>" class="btn secondary">
                        <span>Kembali ke Beranda</span>
                    </a>
                    <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn secondary">
                        <span>Laporkan Kendala</span>
                    </a>
                </div>

                <div class="error-quick-links">
                    <span class="quick-links-title">Kontak Cepat:</span>
                    <div class="quick-links-pills">
                        <a href="https://wa.me/6289669709021" target="_blank" class="quick-pill">WhatsApp Dukungan</a>
                        <a href="mailto:consulting@desadroid.shop" class="quick-pill">consulting@desadroid.shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>