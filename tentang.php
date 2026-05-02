<?php
require_once 'config/db.php';
$pageTitle = 'Tentang Kami - Desadroid';
$metaDescription = 'Ketahui lebih lanjut tentang Desadroid, agensi digital yang berfokus pada inovasi dan transformasi digital.';
include 'partials/header.php'; 
?>
<?php 
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
if ($baseDir === '/') $baseDir = ''; 
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; }); 
$baseDirUrl = ''; 
if (!empty($baseDirSegments)) { 
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments)); 
} 
?>

<!-- About Hero -->
<section class="about-hero" data-reveal>
    <div class="container">
        <div class="about-hero-content text-center">
            <span class="hero-label">Tentang Kami</span>
            <h1>Membangun <span class="text-gradient">Ekosistem Digital</span><br>yang Berdampak Nyata.</h1>
            <p>Sejak 2025, kami membantu perusahaan mengubah ide brilian menjadi produk digital yang kuat dan fungsional. Kami bukan sekadar agensi, melainkan mitra inovasi Anda.</p>
        </div>
    </div>
</section>

<!-- About Story -->
<section class="about-story bg-light" data-reveal>
    <div class="container story-split">
        <div class="story-image">
            <?php $imgSrc = (!empty($baseDirUrl) ? $baseDirUrl . '/src/img/DESADROID.jpg' : '/src/img/DESADROID.jpg'); ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Tim Desadroid">
            <div class="story-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <div class="card-text">
                    <strong>Fokus pada Kualitas</strong>
                    <span>Kode bersih & Desain intuitif</span>
                </div>
            </div>
        </div>
        <div class="story-text">
            <h2>Kisah Perjalanan Kami</h2>
            <p>Desadroid lahir dari semangat untuk menyelesaikan masalah bisnis melalui teknologi. Di era di mana transformasi digital adalah sebuah keharusan, kami hadir untuk menjembatani kesenjangan antara visi bisnis dan eksekusi teknis.</p>
            <p>Tim ahli kami terdiri dari talenta-talenta kreatif dan teknikal—desainer, developer, strategis—yang bekerja berdampingan dengan satu tujuan utama: <strong>Menciptakan nilai tambah yang terukur bagi klien kami.</strong></p>
            
            <div class="stats-clean mt-4">
                <div class="stat-item">
                    <h3>100%</h3>
                    <p>Kepuasan Klien</p>
                </div>
                <div class="stat-item">
                    <h3>15+</h3>
                    <p>Proyek Sukses</p>
                </div>
                <div class="stat-item">
                    <h3>2025</h3>
                    <p>Tahun Berdiri</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="core-values" data-reveal>
    <div class="container">
        <div class="section-title text-center">
            <h2>Nilai Inti Perusahaan</h2>
            <p>Prinsip yang memandu setiap baris kode yang kami tulis dan setiap desain yang kami rancang.</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                </div>
                <h3>Inovasi Berkelanjutan</h3>
                <p>Kami tidak pernah berhenti belajar. Kami selalu mengadopsi teknologi terbaru yang terbukti andal untuk memberikan keunggulan kompetitif bagi produk Anda.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3>Kualitas Tanpa Kompromi</h3>
                <p>Dari arsitektur backend hingga detail UI mikroskopis, kami memastikan standar kualitas tertinggi di setiap fase pengembangan.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3>Kemitraan Jangka Panjang</h3>
                <p>Kami memposisikan diri sebagai perpanjangan dari tim Anda. Kesuksesan klien adalah metrik utama kesuksesan Desadroid.</p>
            </div>
        </div>
    </div>
</section>

<!-- About CTA -->
<section class="about-cta bg-light" data-reveal>
    <div class="container text-center">
        <h2>Siap Berkembang Bersama Kami?</h2>
        <p class="mb-4 text-muted">Mari diskusikan bagaimana kami bisa membantu mewujudkan visi bisnis Anda.</p>
        <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn primary">Hubungi Kami Sekarang</a>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
