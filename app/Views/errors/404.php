<section class="error-page" data-reveal>
    <div class="container">
        <div class="error-wrapper">
            <div class="error-visual">
                <div class="error-glow"></div>
                <span class="error-code">404</span>
                <div class="error-badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>Page Not Found</span>
                </div>
            </div>

            <div class="error-content">
                <h1>Ups! Halaman yang Anda Tuju Tidak Ditemukan</h1>
                <p class="error-desc">
                    Halaman yang Anda cari mungkin telah dipindahkan, diubah namanya, atau sedang tidak tersedia. 
                    Pastikan alamat URL yang Anda masukkan sudah benar, atau gunakan navigasi di bawah ini untuk menemukan apa yang Anda butuhkan.
                </p>

                <div class="error-actions">
                    <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/') ?>" class="btn primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                    <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/layanan') ?>" class="btn secondary">
                        <span>Lihat Layanan Kami</span>
                    </a>
                    <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/kontak') ?>" class="btn secondary">
                        <span>Hubungi Bantuan</span>
                    </a>
                </div>

                <div class="error-quick-links">
                    <span class="quick-links-title">Mungkin Anda sedang mencari:</span>
                    <div class="quick-links-pills">
                        <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/tentang') ?>" class="quick-pill">Profil Tentang Kami</a>
                        <a href="https://project.desadroid.shop" target="_blank" rel="noopener" class="quick-pill">Portofolio Proyek</a>
                        <a href="<?= htmlspecialchars(($baseDirUrl ?: '') . '/artikel') ?>" class="quick-pill">Artikel &amp; Blog</a>
                        <a href="https://wa.me/6289669709021" target="_blank" rel="noopener" class="quick-pill">Chat WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
