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
                    <h4>desadroid</h4>
                    <p>Menciptakan solusi digital inovatif yang mengubah bisnis dan menyenangkan pengguna.</p>
                    <div class="social">
                        <a href="https://github.com/Fahmi-febriansyah" aria-label="GitHub" class="social-link" target="_blank" rel="noopener">GitHub</a>
                        <a href="https://www.linkedin.com/in/fahmifebriansyah/" aria-label="LinkedIn" class="social-link" target="_blank" rel="noopener">LinkedIn</a>
                        <a href="https://www.instagram.com/desadroiditconsultant/" aria-label="Instagram" class="social-link" target="_blank" rel="noopener">Instagram</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h5>Company</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/tentang' : $baseDirUrl . '/tentang')) ?>">About</a></li>
                        <li><a href="#">Team</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Services</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Web Development</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Mobile Apps</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">UI/UX Design</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>">Consulting</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/artikel' : $baseDirUrl . '/artikel')) ?>">Blog</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/privacy.php' : $baseDirUrl . '/privacy.php')) ?>">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; 2026 desadroid. All rights reserved.</div>
                <div>Made with care in Indonesia</div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?= htmlspecialchars(($baseDirUrl === '' ? '/src/js/main.js' : $baseDirUrl . '/src/js/main.js')) ?>"></script>

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
