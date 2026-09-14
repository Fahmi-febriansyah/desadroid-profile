<section class="error-page" data-reveal>
    <div class="container">
        <div class="error-wrapper">
            <div class="error-visual error-visual-403">
                <div class="error-glow error-glow-orange"></div>
                <span class="error-code">403</span>
                <div class="error-badge error-badge-orange">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <span>Access Forbidden</span>
                </div>
            </div>

            <div class="error-content">
                <h1>Akses Terbatas / Dilarang (403)</h1>
                <p class="error-desc">
                    Maaf, Anda tidak memiliki izin untuk mengakses direktori atau sumber daya ini. 
                    Area ini dilindungi oleh konfigurasi keamanan sistem Desadroid.
                </p>

                <div class="error-actions">
                    <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/') ?>" class="btn primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                    <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/kontak') ?>" class="btn secondary">
                        <span>Hubungi Administrator</span>
                    </a>
                </div>

                <div class="error-quick-links">
                    <span class="quick-links-title">Akses Cepat:</span>
                    <div class="quick-links-pills">
                        <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>" class="quick-pill">Layanan Kami</a>
                        <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/tentang') ?>" class="quick-pill">Tentang Desadroid</a>
                        <a href="https://project.desadroid.shop" target="_blank" rel="noopener" class="quick-pill">Portofolio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
