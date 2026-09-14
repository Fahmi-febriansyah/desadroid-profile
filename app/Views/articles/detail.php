<!-- Structured Data: BlogPosting Schema for Google Rich Snippets -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?= htmlspecialchars($canonical) ?>"
  },
  "headline": "<?= htmlspecialchars($article['title']) ?>",
  "description": "<?= htmlspecialchars($article['excerpt'] ?? '') ?>",
  "image": "<?= htmlspecialchars($heroImg) ?>",
  "datePublished": "<?= date('c', strtotime($article['published_date'])) ?>",
  "dateModified": "<?= date('c', strtotime($article['published_date'])) ?>",
  "author": {
    "@type": "Person",
    "name": "<?= htmlspecialchars($article['author'] ?? 'Tim Desadroid') ?>"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Desadroid",
    "logo": {
      "@type": "ImageObject",
      "url": "<?= htmlspecialchars(($baseDirUrl ?: '') . '/src/icon/icon.png') ?>"
    }
  }
}
</script>

<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>

<!-- Article Hero Banner -->
<section class="ad-hero">
    <div class="ad-hero-overlay"></div>
    <img class="ad-hero-bg" src="<?= htmlspecialchars($heroImg) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
    <div class="container">
        <div class="ad-hero-content" data-reveal>
            <div class="ad-breadcrumb">
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/') ?>">Beranda</a>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/artikel') ?>">Artikel</a>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                <span>Detail</span>
            </div>
            <h1 class="ad-title"><?= htmlspecialchars($article['title']) ?></h1>
            <div class="ad-meta-row">
                <div class="ad-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span><?= htmlspecialchars($article['author'] ?? 'Tim Kami') ?></span>
                </div>
                <div class="ad-meta-divider"></div>
                <div class="ad-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span><?= date('d M Y', strtotime($article['published_date'])) ?></span>
                </div>
                <div class="ad-meta-divider"></div>
                <div class="ad-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span><?= $readingTime ?> menit baca</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Article Main Layout -->
<section class="ad-main">
    <div class="container">
        <div class="ad-layout">

            <!-- Article Content Column -->
            <article class="ad-article" data-reveal>

                <!-- Share Bar -->
                <div class="ad-share-bar">
                    <span class="ad-share-label">Bagikan</span>
                    <button class="ad-share-btn" onclick="copyLink()" title="Salin link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    </button>
                    <a class="ad-share-btn" href="https://wa.me/?text=<?= urlencode($canonical) ?>" target="_blank" rel="noopener" title="WhatsApp">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.694-1.382A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.239 0-4.308-.726-5.993-1.957l-.418-.307-2.788.821.749-2.738-.337-.436A9.94 9.94 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                    </a>
                    <a class="ad-share-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonical) ?>" target="_blank" rel="noopener" title="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a class="ad-share-btn" href="https://twitter.com/intent/tweet?url=<?= urlencode($canonical) ?>&text=<?= urlencode($article['title']) ?>" target="_blank" rel="noopener" title="Twitter/X">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>

                <!-- Article Content -->
                <div class="ad-content" id="content">
                <?php
                $renderedContent = $article['content'];
                // Auto Internal Link SEO
                if (!empty($allArticles)) {
                    foreach ($allArticles as $a) {
                        if ($a['slug'] !== $article['slug']) {
                            $linkUrl = ($baseDirUrl ?: '') . '/artikel/' . rawurlencode($a['slug']);
                            $renderedContent = preg_replace(
                                '/\b(' . preg_quote($a['title'], '/') . ')\b/i',
                                '<a href="' . $linkUrl . '">$1</a>',
                                $renderedContent,
                                1
                            );
                        }
                    }
                }
                echo $renderedContent;
                ?>
                </div>

                <!-- Tags -->
                <?php if (!empty($article['tags'])): ?>
                <div class="ad-tags">
                    <?php foreach(explode(',', $article['tags']) as $tag): ?>
                    <span class="ad-tag"><?= htmlspecialchars(trim($tag)) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Author Box -->
                <div class="ad-author-box">
                    <div class="ad-author-avatar">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="ad-author-info">
                        <span class="ad-author-label">Ditulis oleh</span>
                        <h4 class="ad-author-name"><?= htmlspecialchars($article['author'] ?? 'Tim Kami') ?></h4>
                        <p class="ad-author-bio">Tim profesional di Desadroid yang berdedikasi menghadirkan konten berkualitas tentang teknologi dan inovasi digital.</p>
                    </div>
                </div>

            </article>

            <!-- Sidebar -->
            <aside class="ad-sidebar">

                <!-- Table of Contents -->
                <div class="ad-sidebar-card ad-toc-card" data-reveal>
                    <h4 class="ad-sidebar-heading">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        Daftar Isi
                    </h4>
                    <div id="tocList" class="ad-toc-list"></div>
                </div>

                <!-- Sidebar Related Articles -->
                <?php if (!empty($related)): ?>
                <div class="ad-sidebar-card" data-reveal>
                    <h4 class="ad-sidebar-heading">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        Artikel Populer
                    </h4>
                    <div class="ad-sidebar-articles">
                        <?php foreach(array_slice($related, 0, 4) as $idx => $sr): ?>
                        <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/artikel/' . rawurlencode($sr['slug'])) ?>" class="ad-sidebar-article-item">
                            <span class="ad-sidebar-num"><?= str_pad($idx+1, 2, '0', STR_PAD_LEFT) ?></span>
                            <span class="ad-sidebar-article-title"><?= htmlspecialchars($sr['title']) ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </aside>

        </div>
    </div>
</section>

<!-- Artikel Terkait Full Width -->
<?php if (!empty($related)): ?>
<section class="ad-related-section" data-reveal>
    <div class="container">
        <div class="ad-related-header">
            <h2 class="ad-related-title">Artikel Terkait</h2>
            <p class="ad-related-subtitle">Temukan lebih banyak artikel menarik yang relevan untuk Anda</p>
        </div>
        <div class="ad-related-grid">
            <?php foreach($related as $r): ?>
            <?php
            $rImg = !empty($r['featured_image'])
                  ? (preg_match('/^https?:\/\//', $r['featured_image']) ? $r['featured_image'] : ($baseDirUrl ?: '') . '/' . ltrim($r['featured_image'], '/'))
                  : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=600&q=80';
            ?>
            <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/artikel/' . rawurlencode($r['slug'])) ?>" class="ad-related-card">
                <div class="ad-related-img">
                    <img src="<?= htmlspecialchars($rImg) ?>" alt="<?= htmlspecialchars($r['title']) ?>" loading="lazy">
                </div>
                <div class="ad-related-body">
                    <span class="ad-related-date">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?= date('d M Y', strtotime($r['published_date'])) ?>
                    </span>
                    <h3 class="ad-related-card-title"><?= htmlspecialchars($r['title']) ?></h3>
                    <span class="ad-related-read">
                        Baca Selengkapnya
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function copyLink(){
    navigator.clipboard.writeText(window.location.href).then(function(){
        const toast = document.createElement('div');
        toast.className = 'ad-toast';
        toast.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Link berhasil disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    });
}

/* BACA JUGA - setiap 2 blok konten */
(function(){
    const content = document.getElementById('content');
    if (!content) return;

    const blocks = Array.from(content.querySelectorAll(':scope > p, :scope > h2, :scope > h3, :scope > ul, :scope > ol, :scope > blockquote, :scope > figure'));
    const relatedArticles = <?= json_encode(array_map(function($r) use ($baseDirUrl){
        return [
            'title' => $r['title'],
            'url'   => ($baseDirUrl ?: '') . '/artikel/' . rawurlencode($r['slug'])
        ];
    }, $related ?? [])) ?>;

    if (relatedArticles.length === 0 || blocks.length < 3) return;

    let insertedCount = 0;
    for (let i = 1; i < blocks.length - 1; i++) {
        if ((i % 2) === 0 && insertedCount < relatedArticles.length) {
            const art = relatedArticles[insertedCount % relatedArticles.length];
            const box = document.createElement('div');
            box.className = 'ad-read-also';
            box.innerHTML =
                '<div class="ad-read-also-label">' +
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>' +
                    ' Baca Juga' +
                '</div>' +
                '<a href="' + art.url + '" class="ad-read-also-link">' +
                    '<div class="ad-read-also-text">' +
                        '<span class="ad-read-also-title">' + art.title + '</span>' +
                        '<span class="ad-read-also-arrow">' +
                            '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>' +
                        '</span>' +
                    '</div>' +
                '</a>';
            blocks[i].after(box);
            insertedCount++;
        }
    }
})();

/* Reading Progress Bar */
window.addEventListener('scroll', function(){
    const bar = document.getElementById('readingProgress');
    const article = document.querySelector('.ad-article');
    if(!article || !bar) return;
    const rect = article.getBoundingClientRect();
    const top = window.scrollY + rect.top;
    const height = article.offsetHeight;
    const scrolled = window.scrollY - top;
    const progress = Math.min(Math.max(scrolled / (height - window.innerHeight), 0), 1);
    bar.style.width = (progress * 100) + '%';
});

/* Auto-generate Table of Contents */
(function(){
    const content = document.getElementById('content');
    const tocList = document.getElementById('tocList');
    if(!content || !tocList) return;

    const headings = content.querySelectorAll('h2, h3');
    if(headings.length === 0){
        const tocCard = document.querySelector('.ad-toc-card');
        if (tocCard) tocCard.style.display = 'none';
        return;
    }

    headings.forEach(function(h, i){
        const id = 'section-' + i;
        h.id = id;
        const a = document.createElement('a');
        a.href = '#' + id;
        a.className = 'ad-toc-item' + (h.tagName === 'H3' ? ' ad-toc-sub' : '');
        a.textContent = h.textContent;
        tocList.appendChild(a);
    });
})();
</script>
