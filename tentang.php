<?php
require_once 'config/db.php';
?>
<?php include 'partials/header.php'; ?>

<?php $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); if ($baseDir === '/') $baseDir = ''; $baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; }); $baseDirUrl = ''; if (!empty($baseDirSegments)) { $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments)); } ?>

    <section class="about" style="padding:5rem 0;" data-reveal>
        <div class="container">
            <h2>Tentang desadroid</h2>
            <p>Mitra tepercaya Anda dalam transformasi digital.</p>

            <div class="about-content">
                <?php $imgSrc = (!empty($baseDirUrl) ? $baseDirUrl . '/src/img/DESADROID.jpg' : '/src/img/DESADROID.jpg'); ?>
                <img class="about-image" src="<?= htmlspecialchars($imgSrc) ?>" alt="Tim sedang bekerja" />
                <div class="text">
                    <p>Desadroid adalah studio produksi digital yang berfokus membantu
                        bisnis menciptakan pengalaman digital luar biasa sejak 2025.
                        Tim desainer, pengembang, dan strategis kami merancang
                        solusi yang mendorong pertumbuhan dan inovasi.</p>
                    <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn tertiary">Ajukan Proyek</a>
                </div>
            </div>
            <div class="stats">
                <div class="stat" data-reveal data-reveal-delay="100">
                    <h3>100%</h3>
                    <p>Klien Puas</p>
                </div>
                <div class="stat" data-reveal data-reveal-delay="200">
                    <h3>2</h3>
                    <p>Proyek Selesai</p>
                </div>
                <div class="stat" data-reveal data-reveal-delay="300">
                    <h3>Modern</h3>
                    <p>Teknologi Terkini</p>
                </div>
                <div class="stat" data-reveal data-reveal-delay="400">
                    <h3>2025</h3>
                    <p>Tahun Berdiri</p>
                </div>
            </div>
        </div>
    </section>

<?php include 'partials/footer.php'; ?>
