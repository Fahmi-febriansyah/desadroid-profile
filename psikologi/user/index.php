<?php
session_start();
include '../koneksi.php';
$page_title = 'Psikologi Kita - Konsultasi Psikologi Online';
include 'header.php';
?>

    <main>

        <section id="home" class="hero-section">
            <div class="hero-bg-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
            </div>
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-leaf"></i> Platform Kesehatan Mental
                    </div>
                    <h1 id="welcomeTitle">Kesehatan Mental <br><span class="gradient-text">Adalah Prioritas</span></h1>
                    <p id="welcomeSubtitle">Konsultasi dengan psikolog profesional bersertifikat. Aman, rahasia, dan bisa diakses kapan saja dari mana saja.</p>
                    <div class="hero-actions">
                        <a href="konsultasi.php" class="btn btn-primary">
                            <i class="fas fa-comments"></i> Mulai Konsultasi
                        </a>
                        <a href="#konsultasi" class="btn btn-outline">
                            <i class="fas fa-info-circle"></i> Pelajari Layanan
                        </a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-card-stack">
                        <div class="float-card card-1">
                            <i class="fas fa-heart-pulse"></i>
                            <span>Kesehatan Mental</span>
                        </div>
                        <div class="float-card card-2">
                            <i class="fas fa-shield-heart"></i>
                            <span>100% Rahasia</span>
                        </div>
                        <div class="float-card card-3">
                            <i class="fas fa-clock"></i>
                            <span>24/7 Online</span>
                        </div>
                        <div class="hero-main-icon">
                            <i class="fas fa-brain"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="konsultasi" class="services-section">
            <div class="section-container">
                <div class="section-header">
                    <span class="section-badge">Konsultasi Psikologi</span>
                    <h2>Solusi untuk Setiap <span class="gradient-text">Kebutuhan Mental</span></h2>
                    <p>Kami menyediakan layanan konsultasi psikologi yang disesuaikan dengan kebutuhan Anda</p>
                </div>
                <div class="services-grid" style="grid-template-columns: 1fr; max-width: 500px; margin: 0 auto;">
                    <div class="service-card">
                        <div class="service-icon-wrap color-indigo"><i class="fas fa-brain"></i></div>
                        <h4>Konsultasi Psikologi (HARS)</h4>
                        <p>Atasi stress, kecemasan, dan gangguan kesehatan mental dengan skrining tingkat kecemasan awal menggunakan metode Hamilton Anxiety Rating Scale.</p>
                        <a href="konsultasi.php" class="service-link">Mulai Konsultasi <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimoni" class="testimonial-section">
            <div class="section-container">
                <div class="section-header">
                    <span class="section-badge">Testimoni</span>
                    <h2>Apa Kata <span class="gradient-text">Klien Kami</span></h2>
                    <p>Kisah nyata dari mereka yang telah merasakan manfaatnya</p>
                </div>
                <div class="testimonial-grid">
                    <?php
                    $testi_query = "SELECT t.*, u.nama FROM testimoni t JOIN users u ON t.id_user = u.id_user WHERE t.status = 'tampil' ORDER BY t.tanggal DESC LIMIT 3";
                    $testi_result = mysqli_query($koneksi, $testi_query);

                    if(mysqli_num_rows($testi_result) > 0) {
                        while($testi = mysqli_fetch_assoc($testi_result)) {
                            $initial = strtoupper(substr($testi['nama'], 0, 1));
                    ?>
                        <div class="testimonial-card">
                            <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                            <p class="testi-text">"<?php echo htmlspecialchars($testi['isi']); ?>"</p>
                            <div class="stars">★★★★★</div>
                            <div class="testi-author">
                                <div class="testi-avatar" style="background:#6366f1;"><?php echo $initial; ?></div>
                                <div>
                                    <h4><?php echo htmlspecialchars($testi['nama']); ?></h4>
                                    <p>Pengguna Terverifikasi</p>
                                </div>
                            </div>
                        </div>
                    <?php 
                        }
                    } else {
                    ?>
                        <p style="grid-column: 1/-1; text-align: center; color:
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="kontak" class="contact-section">
            <div class="section-container">
                <div class="section-header">
                    <span class="section-badge">Hubungi Kami</span>
                    <h2>Lokasi & <span class="gradient-text">Kontak</span></h2>
                    <p>Kunjungi kantor kami atau hubungi melalui online</p>
                </div>
                <div class="contact-grid">
                    <div class="contact-info-cards">
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <h4>Alamat</h4>
                                <p>Jl. Cililitan Kecil II No.38, RT.11/RW.7, Cililitan, Kec. Kramat jati, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13640</p>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <h4>Telepon</h4>
                                <p>0815-1916-4649</p>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <h4>Jam Operasional</h4>
                                <p>Buka setiap hari<br>09.00 - 17.00 WIB</p>
                            </div>
                        </div>
                    </div>
                    <div class="maps-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0262793204715!2d106.85998637409685!3d-6.260268661288432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f2545bba3095%3A0x481433625ace6fd4!2sPSIKOLOGI%20KITA%20KONSULTING!5e0!3m2!1sid!2sid!4v1778052864312!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
