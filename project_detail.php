<?php
require_once 'config/db.php';
$slug = $_GET['slug'] ?? '';
if (!$slug) {
    // Fallback for old links if any
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id <= 0) {
        header("HTTP/1.1 404 Not Found");
        echo 'Proyek tidak ditemukan.';
        exit;
    }
}

try {
    if ($slug) {
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
    }
    $project = $stmt->fetch();
} catch (Exception $e) {
    $project = false;
}

if (!$project) {
    header("HTTP/1.1 404 Not Found");
    echo 'Proyek tidak ditemukan.';
    exit;
}

$id = $project['id']; // Ensure $id is available for other queries

if (empty($project['link'])) {
    header('Location: error/index.html');
    exit;
}

// fetch project images
try {
    $imgStmt = $pdo->prepare('SELECT image_url FROM project_images WHERE project_id = :id ORDER BY id ASC LIMIT 8');
    $imgStmt->execute(['id' => $id]);
    $images = $imgStmt->fetchAll();
} catch (Exception $e) {
    $images = [];
}

// fetch MOU letter
try {
    $letterStmt = $pdo->prepare('SELECT id, letter_number, title, content, attachments, created_at FROM outgoing_letters WHERE project_id = :id LIMIT 1');
    $letterStmt->execute(['id' => $id]);
    $mou = $letterStmt->fetch();
} catch (Exception $e) {
    $mou = false;
}

// fetch related projects (same category, max 3)
try {
    $relStmt = $pdo->prepare("SELECT id, title, category, description, featured_image, progress FROM projects WHERE id != :id AND category = :cat LIMIT 3");
    $relStmt->execute(['id' => $id, 'cat' => $project['category']]);
    $relatedProjects = $relStmt->fetchAll();
    if (count($relatedProjects) < 3) {
        $relStmt2 = $pdo->prepare("SELECT id, title, category, description, featured_image, progress FROM projects WHERE id != :id ORDER BY id DESC LIMIT 3");
        $relStmt2->execute(['id' => $id]);
        $relatedProjects = $relStmt2->fetchAll();
    }
} catch (Exception $e) {
    $relatedProjects = [];
}

$progress = isset($project['progress']) ? intval($project['progress']) : 0;
$heroImg = !empty($project['featured_image']) ? $project['featured_image'] : (!empty($images[0]['image_url']) ? $images[0]['image_url'] : 'https://source.unsplash.com/1400x700/?technology,office');

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

$pageTitle = htmlspecialchars($project['title']) . ' — Desadroid';
$metaDescription = !empty($project['description']) ? htmlspecialchars(mb_substr(strip_tags($project['description']), 0, 155)) : 'Detail proyek Desadroid: ' . htmlspecialchars($project['title']);

include 'partials/header.php';

// Parse tech stack from tags field if available
$techStack = [];
if (!empty($project['tags'])) {
    $techStack = array_filter(array_map('trim', explode(',', $project['tags'])));
}
?>

<!-- PD = Project Detail prefix -->

<!-- Reading Progress -->
<div class="reading-progress" id="readingProgress"></div>

<!-- Hero Section -->
<section class="pd-hero">
    <div class="pd-hero-overlay"></div>
    <img class="pd-hero-bg" src="<?= htmlspecialchars($heroImg) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
    <div class="container">
        <div class="pd-hero-content" data-reveal>
            <div class="pd-breadcrumb">
                <a href="<?= $baseDir ?>/">Beranda</a>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                <a href="<?= $baseDir ?>/proyek">Proyek</a>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                <span>Detail</span>
            </div>
            <div class="pd-category-badge"><?= htmlspecialchars($project['category']) ?></div>
            <h1 class="pd-title"><?= htmlspecialchars($project['title']) ?></h1>
            <div class="pd-meta-row">
                <div class="pd-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <span><?= $progress ?>% Selesai</span>
                </div>
                <?php if (!empty($project['client'])): ?>
                <div class="pd-meta-divider"></div>
                <div class="pd-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span><?= htmlspecialchars($project['client']) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($project['year'])): ?>
                <div class="pd-meta-divider"></div>
                <div class="pd-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span><?= htmlspecialchars($project['year']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="pd-main">
    <div class="container">
        <div class="pd-layout">

            <!-- Left: Content -->
            <div class="pd-content-col" data-reveal>

                <!-- Progress Bar Card -->
                <div class="pd-progress-card">
                    <div class="pd-progress-header">
                        <span class="pd-progress-label">Status Penyelesaian</span>
                        <span class="pd-progress-pct"><?= $progress ?>%</span>
                    </div>
                    <div class="pd-progress-track">
                        <div class="pd-progress-fill" style="width:<?= $progress ?>%"></div>
                    </div>
                    <div class="pd-progress-status">
                        <?php if ($progress >= 100): ?>
                        <span class="pd-status-badge pd-status-done">✓ Selesai</span>
                        <?php elseif ($progress >= 50): ?>
                        <span class="pd-status-badge pd-status-progress">⚡ Dalam Pengerjaan</span>
                        <?php else: ?>
                        <span class="pd-status-badge pd-status-early">🚀 Tahap Awal</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <div class="pd-section-block">
                    <h2 class="pd-section-heading">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Tentang Proyek
                    </h2>
                    <div class="pd-description">
                        <?= nl2br(htmlspecialchars($project['description'])) ?>
                    </div>
                </div>

                <!-- Tech Stack -->
                <?php if (!empty($techStack)): ?>
                <div class="pd-section-block">
                    <h2 class="pd-section-heading">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        Teknologi
                    </h2>
                    <div class="pd-tech-stack">
                        <?php foreach ($techStack as $tech): ?>
                        <span class="pd-tech-badge"><?= htmlspecialchars($tech) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Gallery -->
                <?php if (!empty($images)): ?>
                <div class="pd-section-block">
                    <h2 class="pd-section-heading">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Galeri Proyek
                    </h2>
                    <div class="pd-gallery">
                        <?php foreach ($images as $img): ?>
                        <div class="pd-gallery-item" onclick="openLightbox('<?= htmlspecialchars($img['image_url']) ?>')">
                            <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" loading="lazy">
                            <div class="pd-gallery-overlay">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- MOU Section -->
                <?php if ($mou): ?>
                <div class="pd-section-block">
                    <h2 class="pd-section-heading">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Dokumen MOU
                    </h2>
                    <div class="pd-mou-card">
                        <div class="pd-mou-number"><?= htmlspecialchars($mou['letter_number']) ?></div>
                        <div class="pd-mou-title"><?= htmlspecialchars($mou['title']) ?></div>
                        <div class="pd-mou-date">Diterbitkan: <?= date('d M Y', strtotime($mou['created_at'])) ?></div>
                        <a href="letter.php?id=<?= intval($mou['id']) ?>" class="pd-mou-btn">
                            Lihat Dokumen
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- Right: Sidebar -->
            <aside class="pd-sidebar">

                <!-- CTA Buttons -->
                <div class="pd-sidebar-card pd-cta-card" data-reveal>
                    <?php if (!empty($project['link'])): ?>
                    <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank" class="pd-cta-btn pd-cta-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Lihat Live Project
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($project['code_link'])): ?>
                    <a href="<?= htmlspecialchars($project['code_link']) ?>" target="_blank" class="pd-cta-btn pd-cta-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        Source Code
                    </a>
                    <?php endif; ?>
                    <a href="<?= $baseDir ?>/proyek" class="pd-cta-btn pd-cta-ghost">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Semua Proyek
                    </a>
                </div>

                <!-- Project Info -->
                <div class="pd-sidebar-card" data-reveal>
                    <h4 class="pd-sidebar-heading">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Info Proyek
                    </h4>
                    <div class="pd-info-list">
                        <div class="pd-info-item">
                            <span class="pd-info-key">Kategori</span>
                            <span class="pd-info-val"><?= htmlspecialchars($project['category']) ?></span>
                        </div>
                        <div class="pd-info-item">
                            <span class="pd-info-key">Progress</span>
                            <span class="pd-info-val"><?= $progress ?>%</span>
                        </div>
                        <?php if (!empty($project['client'])): ?>
                        <div class="pd-info-item">
                            <span class="pd-info-key">Klien</span>
                            <span class="pd-info-val"><?= htmlspecialchars($project['client']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($project['year'])): ?>
                        <div class="pd-info-item">
                            <span class="pd-info-key">Tahun</span>
                            <span class="pd-info-val"><?= htmlspecialchars($project['year']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Share -->
                <div class="pd-sidebar-card" data-reveal>
                    <h4 class="pd-sidebar-heading">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        Bagikan
                    </h4>
                    <div class="pd-share-btns">
                        <button class="pd-share-btn" onclick="copyLink()" title="Salin link">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Salin Link
                        </button>
                        <a class="pd-share-btn" href="https://wa.me/?text=<?= urlencode($scheme.'://'.$host.$_SERVER['REQUEST_URI']) ?>" target="_blank" title="WhatsApp">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.694-1.382A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.308-.726-5.993-1.957l-.418-.307-2.788.821.749-2.738-.337-.436A9.94 9.94 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg> WhatsApp
                        </a>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</section>

<!-- Related Projects -->
<?php if (!empty($relatedProjects)): ?>
<section class="pd-related-section" data-reveal>
    <div class="container">
        <div class="pd-related-header">
            <h2 class="pd-related-title">Proyek Lainnya</h2>
            <p class="pd-related-subtitle">Jelajahi proyek-proyek kami yang lain</p>
        </div>
        <div class="pd-related-grid">
            <?php foreach ($relatedProjects as $rp): ?>
            <?php
            $rpImg = !empty($rp['featured_image']) ? $rp['featured_image'] : 'https://source.unsplash.com/600x400/?technology,software';
            $rpProgress = isset($rp['progress']) ? intval($rp['progress']) : 0;
            ?>
            <a href="<?= $baseDir ?>/proyek/<?= rawurlencode($rp['slug']) ?>" class="pd-related-card">
                <div class="pd-related-img">
                    <img src="<?= htmlspecialchars($rpImg) ?>" alt="<?= htmlspecialchars($rp['title']) ?>" loading="lazy">
                    <span class="pd-related-cat"><?= htmlspecialchars($rp['category']) ?></span>
                </div>
                <div class="pd-related-body">
                    <h3 class="pd-related-card-title"><?= htmlspecialchars($rp['title']) ?></h3>
                    <div class="pd-related-progress-wrap">
                        <div class="pd-related-progress-bar" style="width:<?= $rpProgress ?>%"></div>
                    </div>
                    <span class="pd-related-read">
                        Lihat Detail
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Lightbox -->
<div class="pd-lightbox" id="pdLightbox" onclick="closeLightbox()">
    <button class="pd-lightbox-close" onclick="closeLightbox()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <img class="pd-lightbox-img" id="pdLightboxImg" src="" alt="">
</div>

<script>
function openLightbox(src) {
    document.getElementById('pdLightboxImg').src = src;
    document.getElementById('pdLightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('pdLightbox').classList.remove('active');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeLightbox(); });

function copyLink(){
    navigator.clipboard.writeText(window.location.href).then(function(){
        const toast = document.createElement('div');
        toast.className = 'ad-toast';
        toast.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Link berhasil disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 2500);
    });
}

window.addEventListener('scroll', function(){
    const bar = document.getElementById('readingProgress');
    if(!bar) return;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = docHeight > 0 ? window.scrollY / docHeight : 0;
    bar.style.width = (Math.min(progress,1)*100) + '%';
});

// Animate progress bar on load
window.addEventListener('load', function(){
    const fill = document.querySelector('.pd-progress-fill');
    if(fill) {
        const target = fill.style.width;
        fill.style.width = '0';
        setTimeout(()=>{ fill.style.width = target; }, 300);
    }
});
</script>

<?php include 'partials/footer.php'; ?>
