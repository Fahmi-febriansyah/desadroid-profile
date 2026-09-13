<?php
// Footer shared partial — renders footer, loads scripts, and chat widget.

// Compute baseDir for asset paths
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($baseDir === '/') $baseDir = '';
// Build URL-encoded base path for hrefs
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; });
$baseDirUrl = '';
if (!empty($baseDirSegments)) {
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments));
}
?>

    <footer class="footer" data-reveal>
        <div class="footer-glow"></div>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <div class="logo mb-2" style="font-size: 1.5rem;"><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/' : $baseDirUrl . '/')) ?>">desadroid</a></div>
                    <p>Studio Teknologi &amp; IT Consultant profesional berbasis di Bogor. Kami merancang website modern, aplikasi mobile berkinerja tinggi, dan sistem kustom yang siap mengakselerasi skala bisnis Anda.</p>
                    <div class="social">
                        <a href="https://github.com/Fahmi-febriansyah" aria-label="GitHub" class="social-link" target="_blank" rel="noopener">GitHub</a>
                        <a href="https://www.linkedin.com/in/fahmifebriansyah/" aria-label="LinkedIn" class="social-link" target="_blank" rel="noopener">LinkedIn</a>
                        <a href="https://www.instagram.com/desadroiditconsultant/" aria-label="Instagram" class="social-link" target="_blank" rel="noopener">Instagram</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h5>Perusahaan</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/tentang' : $baseDirUrl . '/tentang')) ?>">Tentang Kami</a></li>
                        <li><a href="https://project.desadroid.shop" target="_blank">Portofolio Proyek</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/artikel' : $baseDirUrl . '/artikel')) ?>">Blog &amp; Insight</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>">Hubungi Kami</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/privacy.php' : $baseDirUrl . '/privacy.php')) ?>">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Layanan Unggulan</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Jasa Pembuatan Website</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Pengembangan Aplikasi Mobile</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Desain UI/UX &amp; Prototyping</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Konsultasi IT &amp; Cloud Architecture</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Lokasi &amp; Kontak</h5>
                    <ul style="color: var(--text2); font-size: 0.92rem; line-height: 1.6;">
                        <li style="margin-bottom: 0.5rem;"><strong style="color: var(--text);">Kantor:</strong> Cikeas Udik, Kabupaten Bogor, Jawa Barat</li>
                        <li style="margin-bottom: 0.5rem;"><strong style="color: var(--text);">Email:</strong> <a href="mailto:consulting@desadroid.shop">consulting@desadroid.shop</a></li>
                        <li style="margin-bottom: 0.5rem;"><strong style="color: var(--text);">WhatsApp:</strong> <a href="https://wa.me/6289669709021" target="_blank">+62 896 6970 9021</a></li>
                        <li><strong style="color: var(--text);">Jam Kerja:</strong> Sen - Jum 09:00 - 18:00 WIB</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; 2026 Desadroid. All rights reserved.</div>
                <div>Mitra Inovasi Digital &amp; IT Consultant Bogor, Jawa Barat</div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <?php $jsVersion = file_exists(dirname(__DIR__) . '/src/js/main.js') ? filemtime(dirname(__DIR__) . '/src/js/main.js') : '1.0'; ?>
    <script src="<?= htmlspecialchars(($baseDirUrl === '' ? '/src/js/main.js' : $baseDirUrl . '/src/js/main.js') . '?v=' . $jsVersion) ?>"></script>

    <!-- Tawk.to Live Chat -->
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/69b25f11f2d5f91c395012be/1jjgc6o5n';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

</body>
</html>
