<?php
require_once 'config/db.php';
$pageTitle = 'Hubungi Kami - Desadroid';
$metaDescription = 'Hubungi tim Desadroid untuk konsultasi proyek digital, penawaran jasa, atau pertanyaan lainnya. Kami siap membantu bisnis Anda.';
include 'partials/header.php'; 
?>

<?php 
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
if ($baseDir === '/') $baseDir = ''; 
?>

<!-- Contact Hero -->
<section class="page-hero text-center" data-reveal>
    <div class="container">
        <span class="hero-label">Kontak</span>
        <h1>Ayo <span class="text-gradient">Bicara</span> Tentang Proyek Anda</h1>
        <p class="hero-subtitle mx-auto">Kami selalu bersemangat mendengar tentang ide-ide baru dan tantangan digital. Jangan ragu untuk menyapa tim kami.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-page bg-light" data-reveal>
    <div class="container">
        <div class="contact-split">
            <div class="contact-text">
                <h2>Informasi Kontak</h2>
                <p>Silakan gunakan salah satu channel di bawah ini atau isi formulir untuk mengirimkan pesan langsung kepada kami.</p>
                
                <div class="contact-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p>consulting@desadroid.shop</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <h4>Telepon / WhatsApp</h4>
                            <p>+62 896 6970 9021</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <h4>Lokasi Kantor</h4>
                            <p>JW7P+2P Cikeas Udik, Kabupaten Bogor, Jawa Barat</p>
                        </div>
                    </div>
                </div>
                
                <div class="social-contact mt-4">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links">
                        <!-- Standard social links as defined in footer could be repeated here or just kept simple -->
                        <a href="https://github.com/Fahmi-febriansyah" class="social-btn" target="_blank">GitHub</a>
                        <a href="https://www.linkedin.com/in/fahmifebriansyah/" class="social-btn" target="_blank">LinkedIn</a>
                        <a href="https://www.instagram.com/desadroiditconsultant/" class="social-btn" target="_blank">Instagram</a>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-card">
                <form method="post" action="<?= htmlspecialchars($baseDir . '/send_message.php') ?>">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Pesan Anda</label>
                        <textarea id="message" name="message" rows="5" placeholder="Ceritakan kebutuhan proyek atau pertanyaan Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn primary btn-submit">Kirim Pesan Sekarang</button>
                </form>
            </div>
        </div>
        
        <div class="map-container mt-4">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d991.2628275958328!2d106.9363426!3d-6.3873803!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699592e27d4a99%3A0xd951adc24903faa!2sPojok%20cell!5e0!3m2!1sid!2sid!4v1773116030996!5m2!1sid!2sid"
                width="100%" height="450" style="border:0; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
