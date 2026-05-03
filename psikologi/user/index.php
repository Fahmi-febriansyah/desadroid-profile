<?php
$page_title = 'Psikologi Kita - Konsultasi Psikologi Online';
include 'header.php';
?>

    <!-- MAIN CONTENT -->
    <main>
        <!-- HERO SECTION -->
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
                        <i class="fas fa-leaf"></i> Platform Kesehatan Mental #1
                    </div>
                    <h1 id="welcomeTitle">Kesehatan Mental <br><span class="gradient-text">Adalah Prioritas</span></h1>
                    <p id="welcomeSubtitle">Konsultasi dengan psikolog profesional bersertifikat. Aman, rahasia, dan bisa diakses kapan saja dari mana saja.</p>
                    <div class="hero-actions">
                        <a href="konsultasi.php" class="btn btn-primary">
                            <i class="fas fa-comments"></i> Mulai Konsultasi
                        </a>
                        <a href="psikologi.php" class="btn btn-outline">
                            <i class="fas fa-user-md"></i> Lihat Psikolog
                        </a>
                    </div>
                    <div class="hero-trust">
                        <div class="trust-avatars">
                            <div class="trust-avatar" style="background: #6366f1;">A</div>
                            <div class="trust-avatar" style="background: #10b981;">B</div>
                            <div class="trust-avatar" style="background: #f59e0b;">C</div>
                            <div class="trust-avatar" style="background: #ec4899;">D</div>
                        </div>
                        <p><strong>5,000+</strong> klien sudah terbantu</p>
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

        <!-- STATS SECTION -->
        <section class="stats-section">
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <p class="stat-number">5,200+</p>
                        <p class="stat-label">Klien Terbantu</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fas fa-user-md"></i></div>
                    <div class="stat-info">
                        <p class="stat-number">50+</p>
                        <p class="stat-label">Psikolog Profesional</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon icon-amber"><i class="fas fa-star"></i></div>
                    <div class="stat-info">
                        <p class="stat-number">4.9</p>
                        <p class="stat-label">Rating Kepuasan</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon icon-rose"><i class="fas fa-comment-dots"></i></div>
                    <div class="stat-info">
                        <p class="stat-number">15,000+</p>
                        <p class="stat-label">Sesi Konsultasi</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- KONSULTASI / SERVICES SECTION -->
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
                        <p>Atasi stress, kecemasan, dan gangguan kesehatan mental dengan skrining tingkat kecemasan awal.</p>
                        <a href="konsultasi.php" class="service-link">Mulai Konsultasi <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <!-- PSIKOLOG / OUR TEAM SECTION -->
        <section id="psikolog" class="team-section">
            <div class="section-container">
                <div class="section-header">
                    <span class="section-badge">Tim Profesional</span>
                    <h2>Psikolog <span class="gradient-text">Berpengalaman</span></h2>
                    <p>Didukung oleh tim psikolog bersertifikat dan berdedikasi tinggi</p>
                </div>
                <div class="team-grid">
                    <div class="team-card">
                        <div class="team-avatar" style="background: linear-gradient(135deg,#6366f1,#8b5cf6);">
                            <span>DR</span>
                        </div>
                        <h4>Dr. Ratna Sari, M.Psi</h4>
                        <p class="team-role">Psikolog Klinis</p>
                        <p class="team-desc">Spesialis kecemasan, depresi, dan gangguan mood dengan pengalaman 12 tahun.</p>
                        <div class="team-rating"><i class="fas fa-star"></i> 4.9 <span>(120 sesi)</span></div>
                    </div>
                    <div class="team-card">
                        <div class="team-avatar" style="background: linear-gradient(135deg,#10b981,#059669);">
                            <span>AB</span>
                        </div>
                        <h4>Ahmad Budiman, M.Psi</h4>
                        <p class="team-role">Psikolog Keluarga</p>
                        <p class="team-desc">Ahli terapi keluarga dan pasangan dengan pendekatan humanistik.</p>
                        <div class="team-rating"><i class="fas fa-star"></i> 4.8 <span>(98 sesi)</span></div>
                    </div>
                    <div class="team-card">
                        <div class="team-avatar" style="background: linear-gradient(135deg,#f59e0b,#d97706);">
                            <span>SP</span>
                        </div>
                        <h4>Sinta Permata, M.Psi</h4>
                        <p class="team-role">Psikolog Anak & Remaja</p>
                        <p class="team-desc">Menangani isu tumbuh kembang, bullying, dan gangguan belajar anak.</p>
                        <div class="team-rating"><i class="fas fa-star"></i> 4.9 <span>(85 sesi)</span></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONI SECTION -->
        <section id="testimoni" class="testimonial-section">
            <div class="section-container">
                <div class="section-header">
                    <span class="section-badge">Testimoni</span>
                    <h2>Apa Kata <span class="gradient-text">Klien Kami</span></h2>
                    <p>Kisah nyata dari mereka yang telah merasakan manfaatnya</p>
                </div>
                <div class="testimonial-grid">
                    <div class="testimonial-card">
                        <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                        <p class="testi-text">"Konsultasi di Psikologi Kita sangat membantu saya mengatasi stress kerja. Psikolognya profesional dan sangat empati."</p>
                        <div class="stars">★★★★★</div>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:#6366f1;">SN</div>
                            <div>
                                <h4>Siti Nurhaliza</h4>
                                <p>Profesional Muda</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                        <p class="testi-text">"Layanan online-nya sangat fleksibel. Saya bisa konsultasi kapan saja tanpa harus ke klinik. Sangat praktis!"</p>
                        <div class="stars">★★★★★</div>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:#10b981;">AR</div>
                            <div>
                                <h4>Ahmad Ridho</h4>
                                <p>Pengusaha</p>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testi-quote"><i class="fas fa-quote-left"></i></div>
                        <p class="testi-text">"Harga terjangkau dengan kualitas luar biasa. Psikolognya sabar dan membantu saya memahami diri sendiri lebih baik."</p>
                        <div class="stars">★★★★★</div>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:#ec4899;">DL</div>
                            <div>
                                <h4>Dewi Lestari</h4>
                                <p>Mahasiswa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA SECTION -->
        <section class="cta-section">
            <div class="cta-container">
                <div class="cta-content">
                    <h2>Siap Untuk Memulai <br>Perjalanan Kesehatan Mental?</h2>
                    <p>Langkah pertama adalah yang paling berani. Kami siap menemani Anda.</p>
                    <a href="konsultasi.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-check"></i> Jadwalkan Konsultasi Sekarang
                    </a>
                </div>
            </div>
        </section>

        <!-- KONTAK / MAPS SECTION -->
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
                                <p>Jl. Ahmad Yani No. 123<br>Yogyakarta, Indonesia 55123</p>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <h4>Telepon</h4>
                                <p>(0274) 555-1234<br>+62 812-3456-7890</p>
                            </div>
                        </div>
                        <div class="contact-card">
                            <div class="contact-icon"><i class="fas fa-clock"></i></div>
                            <div>
                                <h4>Jam Operasional</h4>
                                <p>Senin - Jumat: 09:00 - 18:00<br>Sabtu: 10:00 - 16:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="maps-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.8708268968524!2d110.40516332346906!3d-7.797068892147039!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a59916ae7e5b7%3A0x7e7e7e7e7e7e7e7e!2sJl.%20Ahmad%20Yani%2C%20Yogyakarta!5e0!3m2!1sid!2sid!4v1234567890123" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
