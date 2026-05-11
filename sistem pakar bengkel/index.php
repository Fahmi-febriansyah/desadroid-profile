<?php 
session_start();
include 'header.php'; 
?>

    <section class="hero">
        <div class="container">
            <div class="hero-text">
                <div class="hero-badge"><i class="fas fa-wrench"></i> Sistem Pakar Bengkel DPM</div>
                <h1>Diagnosa Kerusakan Mobil <em>Cepat & Akurat</em></h1>
                <p>Tidak perlu menebak masalah kendaraan Anda. Sistem pakar kami menggunakan metode Certainty Factor untuk memberikan diagnosa dan solusi terbaik seperti mekanik profesional.</p>
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <a href="pilih_mobil.php" class="btn btn-primary" style="padding: 14px 30px; font-size: 16px;">
                        <i class="fas fa-tools"></i> Mulai Konsultasi
                    </a>
                    <a href="tentang.php" class="btn btn-outline" style="padding: 14px 30px; font-size: 16px;">
                        Tentang Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section style="padding: 0; position: relative; z-index: 2; margin-top: -40px;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                <div class="glass-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 30px; font-weight: 800; color: var(--primary);">15</div>
                    <div style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">Gejala Teridentifikasi</div>
                </div>
                <div class="glass-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 30px; font-weight: 800; color: var(--primary);">8</div>
                    <div style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">Jenis Kerusakan</div>
                </div>
                <div class="glass-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 30px; font-weight: 800; color: var(--primary);">24</div>
                    <div style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">Aturan Pakar (CF)</div>
                </div>
                <div class="glass-card" style="text-align: center; padding: 24px;">
                    <div style="font-size: 30px; font-weight: 800; color: var(--primary);">100%</div>
                    <div style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">Gratis Digunakan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section style="padding: 80px 0;">
        <div class="container">
            <div class="section-title">
                <h2>Layanan Diagnosa Kami</h2>
                <p>Sistem pakar ini dapat menganalisa berbagai masalah umum kendaraan Anda</p>
            </div>
            <div class="feature-grid">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-car-battery"></i></div>
                    <h3>Sistem Kelistrikan</h3>
                    <p>Mendeteksi masalah pada aki, dinamo starter, alternator, hingga sistem pengapian.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-oil-can"></i></div>
                    <h3>Sistem Pelumasan</h3>
                    <p>Analisa kebocoran oli, kualitas oli mesin, dan indikasi kerusakan komponen dalam.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-thermometer-half"></i></div>
                    <h3>Sistem Pendingin</h3>
                    <p>Diagnosa penyebab mesin cepat panas, masalah radiator, dan sirkulasi air pendingin.</p>
                </div>
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-fan"></i></div>
                    <h3>Sistem AC Mobil</h3>
                    <p>Mengetahui kerusakan kompresor, kebocoran freon, atau masalah evaporator.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section style="padding: 80px 0; background: var(--surface);">
        <div class="container">
            <div class="section-title">
                <h2>Cara Kerja Sistem</h2>
                <p>Hanya 3 langkah mudah untuk mendapatkan diagnosa kerusakan kendaraan Anda</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; max-width: 900px; margin: 0 auto;">
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; line-height: 60px; border-radius: 50%; background: var(--primary); color: #fff; font-size: 24px; font-weight: 800; margin: 0 auto 18px;">1</div>
                    <h3 style="margin-bottom: 8px; font-size: 18px;">Masukkan Kendaraan</h3>
                    <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">Ketik merk dan tipe mobil Anda yang sedang bermasalah.</p>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; line-height: 60px; border-radius: 50%; background: var(--primary); color: #fff; font-size: 24px; font-weight: 800; margin: 0 auto 18px;">2</div>
                    <h3 style="margin-bottom: 8px; font-size: 18px;">Pilih Gejala</h3>
                    <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">Centang gejala yang dialami dan tentukan tingkat keyakinan Anda.</p>
                </div>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; line-height: 60px; border-radius: 50%; background: var(--primary); color: #fff; font-size: 24px; font-weight: 800; margin: 0 auto 18px;">3</div>
                    <h3 style="margin-bottom: 8px; font-size: 18px;">Dapatkan Hasil</h3>
                    <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">Sistem akan menampilkan kerusakan beserta solusi dan tingkat kepastiannya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Location / Map -->
    <section style="padding: 80px 0;">
        <div class="container">
            <div class="glass-card" style="padding: 0; overflow: hidden; display: flex; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
                    <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 16px;">Kunjungi Bengkel Kami</h2>
                    <p style="color: var(--text-muted); line-height: 1.7; margin-bottom: 24px;">Jika diagnosa menunjukkan masalah serius, jangan ragu untuk membawa kendaraan Anda ke bengkel kami untuk penanganan langsung oleh mekanik ahli.</p>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 16px;"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <div style="font-weight: 600; font-size: 15px;">Dua Putri Motor</div>
                            <div style="color: var(--text-muted); font-size: 14px;">Jakarta Timur, Indonesia</div>
                        </div>
                    </div>
                </div>
                <div style="flex: 1; min-width: 300px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.1724679578847!2d106.94714307499154!3d-6.371721793618472!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e699382f6ab2911%3A0x704196d83d2235d3!2sDua%20putri%20motor%20%F0%9F%9A%97!5e0!3m2!1sid!2sid!4v1778487674150!5m2!1sid!2sid" width="100%" height="100%" style="border:0; min-height: 350px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
