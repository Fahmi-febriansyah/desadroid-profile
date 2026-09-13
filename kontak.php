<?php
require_once 'config/db.php';
$pageTitle = 'Hubungi Kami & Konsultasi IT Bogor — Desadroid';
$metaDescription = 'Hubungi tim Desadroid di Bogor untuk konsultasi proyek pembuatan website, aplikasi mobile, maupun arsitektur sistem. Respons cepat dan ramah.';
$metaKeywords = 'kontak desadroid, it consultant bogor, jasa pembuatan web bogor, konsultasi it bogor, kantor desadroid bogor, whatsapp desadroid';
$metaImage = 'src/img/DESADROID.jpg';

$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); 
if ($baseDir === '/') $baseDir = ''; 

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
$form_flash = $_SESSION['contact_flash'] ?? null;
unset($_SESSION['contact_flash']);

include 'partials/header.php'; 
?>

<!-- Contact Hero Section -->
<section class="page-hero text-center" data-reveal>
    <div class="container">
        <div class="hero-badge-tag">
            <span class="hero-badge-dot"></span>
            <span>Konsultasi &amp; Kolaborasi</span>
        </div>
        <h1>Mari Berdiskusi Tentang <span class="gradient-text">Ide Digital</span> Anda</h1>
        <p class="hero-subtitle mx-auto">Kami siap mendengarkan rencana proyek Anda, menganalisis kebutuhan teknis, dan memberikan solusi terbaik tanpa komitmen awal.</p>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-page bg-light" id="contact" data-reveal>
    <div class="container">
        <div class="contact-split">
            <div class="contact-text">
                <span class="hero-label">Saluran Komunikasi Langsung</span>
                <h2>Informasi Kontak Resmi</h2>
                <p>Silakan hubungi kami melalui channel berikut atau kirimkan rincian kebutuhan Anda melalui formulir di samping.</p>
                
                <div class="contact-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div>
                            <h4>Alamat Email</h4>
                            <p><a href="mailto:consulting@desadroid.shop" style="color: var(--text);">consulting@desadroid.shop</a></p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <h4>Telepon / WhatsApp</h4>
                            <p><a href="https://wa.me/6289669709021" target="_blank" style="color: var(--accent1); font-weight: 600;">+62 896 6970 9021 (Respon Cepat)</a></p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <h4>Lokasi Kantor</h4>
                            <p>Cikeas Udik, Kabupaten Bogor, Jawa Barat 16966</p>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon" style="background: rgba(16,185,129,0.1); color: #059669;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        </div>
                        <div>
                            <h4>Jam Operasional</h4>
                            <p>Senin – Jumat: 09:00 – 18:00 WIB</p>
                        </div>
                    </div>
                </div>
                
                <div class="social-contact mt-4">
                    <h4>Kanal Digital &amp; Portofolio</h4>
                    <div class="social-links">
                        <a href="https://github.com/Fahmi-febriansyah" class="social-btn" target="_blank" rel="noopener">GitHub</a>
                        <a href="https://www.linkedin.com/in/fahmifebriansyah/" class="social-btn" target="_blank" rel="noopener">LinkedIn</a>
                        <a href="https://www.instagram.com/desadroiditconsultant/" class="social-btn" target="_blank" rel="noopener">Instagram</a>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-card">
                <?php if ($form_flash): ?>
                <div class="form-alert <?= $form_flash['type'] === 'success' ? 'form-alert-success' : 'form-alert-error' ?>">
                    <div class="form-alert-icon">
                        <?php if ($form_flash['type'] === 'success'): ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <?php else: ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($form_flash['title'] ?? '') ?></strong>
                        <p><?= htmlspecialchars($form_flash['message'] ?? '') ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <form method="post" action="<?= htmlspecialchars($baseDir . '/send_message.php') ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" name="form_load_time" value="<?= time() ?>">
                    <!-- Honeypot anti-spam trap field (invisible to real users) -->
                    <div style="position: absolute; left: -9999px; top: -9999px; opacity: 0; pointer-events: none;" aria-hidden="true">
                        <label for="website_hp_page">Website Security</label>
                        <input type="text" id="website_hp_page" name="website_hp" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" placeholder="Contoh: Budi Santoso" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email *</label>
                        <input type="email" id="email" name="email" placeholder="nama@perusahaan.com" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon / WhatsApp (Opsional)</label>
                        <input type="text" id="phone" name="phone" placeholder="Contoh: 081234567890" maxlength="25">
                    </div>
                    <div class="form-group">
                        <label for="message">Ceritakan Kebutuhan Proyek Anda *</label>
                        <textarea id="message" name="message" rows="5" placeholder="Ceritakan ide, jenis website/aplikasi, atau kendala sistem yang ingin Anda selesaikan..." required maxlength="3000"></textarea>
                    </div>
                    <div class="form-security-badge">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Enkripsi SSL 256-bit & Proteksi Anti-Spam CSRF Aktif</span>
                    </div>
                    <button type="submit" class="btn primary btn-submit" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                        <span>Kirim Pesan Konsultasi</span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="map-container mt-4">
            <div style="background: var(--surface); padding: 1rem 1.5rem; border-radius: 12px 12px 0 0; border: 1px solid var(--border-color); border-bottom: none; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <strong style="font-size: 0.95rem;">Kantor Desadroid — Cikeas Udik, Kabupaten Bogor, Jawa Barat</strong>
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d991.2628275958328!2d106.9363426!3d-6.3873803!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699592e27d4a99%3A0xd951adc24903faa!2sPojok%20cell!5e0!3m2!1sid!2sid!4v1773116030996!5m2!1sid!2sid"
                width="100%" height="420" style="border:1px solid var(--border-color); border-radius: 0 0 16px 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
