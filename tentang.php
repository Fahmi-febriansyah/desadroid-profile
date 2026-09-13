<?php
require_once 'config/db.php';
$pageTitle = 'Tentang Kami — Desadroid IT Consultant & Web Studio';
$metaDescription = 'Ketahui profil Desadroid, konsultan IT dan mitra pembuatan website modern terpercaya di Bogor. Berkomitmen menghadirkan solusi teknologi scalable sejak 2025.';
$metaKeywords = 'tentang desadroid, it consultant bogor, jasa pembuatan web bogor, konsultan it bogor, profil desadroid, agensi web bogor';
$metaImage = 'src/img/DESADROID.jpg';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
if ($baseDir === '/') $baseDir = ''; 
$baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/')), function($s){ return $s !== ''; }); 
$baseDirUrl = ''; 
if (!empty($baseDirSegments)) { 
    $baseDirUrl = '/' . implode('/', array_map('rawurlencode', $baseDirSegments)); 
} 

include 'partials/header.php'; 
?>

<!-- About Hero Section -->
<section class="about-hero" data-reveal>
    <div class="container">
        <div class="about-hero-content text-center">
            <div class="hero-badge-tag">
                <span class="hero-badge-dot"></span>
                <span>Profil &amp; Filosofi Desadroid</span>
            </div>
            <h1>Membangun <span class="gradient-text">Ekosistem Digital</span> yang Berdampak Nyata</h1>
            <p>Berbasis di Bogor, kami memadukan keahlian teknik berstandar tinggi dan kepekaan desain modern untuk membantu bisnis mewujudkan produk digital yang andal, aman, dan scalable.</p>
        </div>
    </div>
</section>

<!-- About Story & Journey -->
<section class="about-story bg-light" data-reveal>
    <div class="container story-split">
        <div class="story-image">
            <?php $imgSrc = (!empty($baseDirUrl) ? $baseDirUrl . '/src/img/DESADROID.jpg' : '/src/img/DESADROID.jpg'); ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Tim Desadroid IT Consultant Bogor" loading="lazy">
            <div class="story-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <div class="card-text">
                    <strong>Fokus pada Kualitas</strong>
                    <span>Kode bersih & Desain intuitif</span>
                </div>
            </div>
            <div class="hero-float-badge hero-float-badge-top">
                <div class="float-badge-icon badge-icon-green">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="float-badge-text">
                    <strong>15+ Solusi Sukses</strong>
                    <span>Kemitraan Jangka Panjang</span>
                </div>
            </div>
        </div>
        <div class="story-text">
            <span class="hero-label">Kisah Perjalanan Kami</span>
            <h2>Dedikasi Nyata untuk Inovasi Digital Bisnis</h2>
            <p>Desadroid lahir dari semangat untuk menyelesaikan tantangan bisnis nyata melalui teknologi tepat guna. Di era akselerasi digital, kami hadir menjembatani kesenjangan antara visi strategis bisnis Anda dan eksekusi teknis yang presisi.</p>
            <p>Kami percaya bahwa produk digital yang hebat bukan hanya tentang tampilan yang indah, melainkan tentang fondasi arsitektur yang kuat, performa yang ultra-cepat, serta rasa aman yang terjamin bagi setiap pengguna.</p>
            
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

<!-- Vision & Mission Section -->
<section class="about-vision-section" data-reveal>
    <div class="container">
        <div class="vision-grid">
            <div class="vision-card" data-reveal>
                <div class="vision-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <h3>Visi Kami</h3>
                <p>Menjadi mitra transformasi teknologi andalan bagi pelaku bisnis di Bogor dan seluruh Indonesia dalam menciptakan ekosistem digital yang modern, andal, dan mampu bersaing di panggung global.</p>
            </div>
            <div class="vision-card" data-reveal>
                <div class="vision-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <h3>Misi Kami</h3>
                <p>Menyajikan layanan konsultasi IT transparan, menerapkan praktik *clean code* dengan keamanan tingkat tinggi, serta memberikan pendampingan berkelanjutan yang menumbuhkan nilai bisnis klien.</p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="core-values bg-light" data-reveal>
    <div class="container">
        <div class="section-title text-center">
            <h2>Nilai Inti yang Kami Pegang</h2>
            <p>Prinsip kerja yang memandu setiap baris kode yang kami kembangkan dan setiap solusi yang kami bangun.</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card" data-reveal>
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 16 16 12 12 8"></polyline><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                </div>
                <h3>Inovasi Berkelanjutan</h3>
                <p>Kami senantiasa mengeksplorasi teknologi modern dan metodologi mutakhir yang terbukti efisien untuk memberikan keunggulan kompetitif bagi produk Anda.</p>
            </div>
            <div class="value-card" data-reveal>
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3>Kualitas Tanpa Kompromi</h3>
                <p>Mulai dari arsitektur database, proteksi keamanan data, hingga detail estetika antarmuka, setiap aspek dikerjakan dengan standar kualitas tertinggi.</p>
            </div>
            <div class="value-card" data-reveal>
                <div class="value-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <h3>Kemitraan Jangka Panjang</h3>
                <p>Kami hadir bukan hanya untuk menyelesaikan satu proyek, melainkan menjadi mitra teknologi tepercaya yang siap mendukung perkembangan bisnis Anda ke depan.</p>
            </div>
        </div>
    </div>
</section>

<!-- About CTA -->
<section class="about-cta" data-reveal>
    <div class="container text-center">
        <h2>Siap Mewujudkan Visi Digital Anda Bersama Kami?</h2>
        <p class="mb-4 text-muted mx-auto" style="max-width: 600px;">Diskusikan kebutuhan sistem, website, maupun aplikasi mobile Anda bersama tim ahli kami. Kami siap memberikan arahan teknis terbaik.</p>
        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/kontak' : $baseDirUrl . '/kontak')) ?>" class="btn primary">
                <span>Konsultasi Proyek Sekarang</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
            <a href="<?= htmlspecialchars(($baseDirUrl === '' ? '/layanan' : $baseDirUrl . '/layanan')) ?>" class="btn secondary">
                <span>Eksplorasi Layanan Kami</span>
            </a>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
