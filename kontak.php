<?php
require_once 'config/db.php';
?>
<?php include 'partials/header.php'; ?>

    <section class="contact" style="padding:5rem 0;" data-reveal>
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="contact-grid">
                <div class="contact-info">
                    <h3>Ayo Bicara</h3>
                    <p>Kami selalu bersemangat mendengar tentang proyek dan peluang baru. Hubungi kami melalui channel manapun.</p>
                    <div class="contact-item">
                        <div class="contact-icon email-icon"></div>
                        <div>
                            <div class="contact-label">Email</div>
                            <div class="contact-value">consulting@desadroid.shop</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon phone-icon"></div>
                        <div>
                            <div class="contact-label">Telepon</div>
                            <div class="contact-value">+6289669709021</div>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon location-icon"></div>
                        <div>
                            <div class="contact-label">Kantor</div>
                            <div class="contact-value">JW7P+2P Cikeas Udik, Kabupaten Bogor, Jawa Barat</div>
                        </div>
                    </div>
                    <a href="https://wa.me/6289669709021" class="btn whatsapp-btn" target="_blank">Chat on WhatsApp</a>
                </div>
                <?php $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); if ($baseDir === '/') $baseDir = ''; ?>
                <form class="contact-form" method="post" action="<?= htmlspecialchars($baseDir . '/send_message.php') ?>">
                    <input type="text" name="name" placeholder="Nama lengkap" required>
                    <input type="email" name="email" placeholder="Alamat Email" required>
                    <textarea name="message" placeholder="Pesan" rows="5" required></textarea>
                    <button type="submit" class="btn primary">Kirim Pesan</button>
                </form>
            </div>
            <div class="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d991.2628275958328!2d106.9363426!3d-6.3873803!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699592e27d4a99%3A0xd951adc24903faa!2sPojok%20cell!5e0!3m2!1sid!2sid!4v1773116030996!5m2!1sid!2sid"
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

<?php include 'partials/footer.php'; ?>
