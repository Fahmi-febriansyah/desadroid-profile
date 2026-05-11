    <footer style="background: var(--text-main); color: #fff; padding: 60px 0 30px; margin-top: auto;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
                <!-- Kolom 1 -->
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                        <img src="assets/logo.png" alt="DPM Logo" style="height: 40px; filter: brightness(0) invert(1);">
                        <div style="font-size: 22px; font-weight: 800; letter-spacing: 0.5px;">DPM <span style="color: var(--primary);">Expert</span></div>
                    </div>
                    <p style="color: #a0a0b8; line-height: 1.6; font-size: 14px; margin-bottom: 20px;">
                        Sistem pakar diagnosa kerusakan kendaraan roda empat berbasis Certainty Factor. Membantu Anda menemukan solusi tepat layaknya mekanik profesional.
                    </p>
                    <div style="display: flex; gap: 15px;">
                        <a href="#" style="color: #a0a0b8; font-size: 20px; transition: color 0.3s;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: #a0a0b8; font-size: 20px; transition: color 0.3s;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: #a0a0b8; font-size: 20px; transition: color 0.3s;"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div>
                    <h3 style="color: #fff; font-size: 16px; margin-bottom: 20px; font-weight: 600;">Menu Navigasi</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                        <li><a href="index.php" style="color: #a0a0b8; text-decoration: none; font-size: 14px; transition: color 0.3s;"><i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px; color: var(--primary);"></i> Beranda</a></li>
                        <li><a href="pilih_mobil.php" style="color: #a0a0b8; text-decoration: none; font-size: 14px; transition: color 0.3s;"><i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px; color: var(--primary);"></i> Mulai Konsultasi</a></li>
                        <li><a href="riwayat.php" style="color: #a0a0b8; text-decoration: none; font-size: 14px; transition: color 0.3s;"><i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px; color: var(--primary);"></i> Riwayat Diagnosa</a></li>
                        <li><a href="tentang.php" style="color: #a0a0b8; text-decoration: none; font-size: 14px; transition: color 0.3s;"><i class="fas fa-chevron-right" style="font-size: 10px; margin-right: 8px; color: var(--primary);"></i> Tentang Bengkel</a></li>
                        <li><a href="admin/login.php" style="color: #a0a0b8; text-decoration: none; font-size: 14px; transition: color 0.3s;"><i class="fas fa-user-shield" style="font-size: 10px; margin-right: 8px; color: var(--primary);"></i> Admin Portal</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div>
                    <h3 style="color: #fff; font-size: 16px; margin-bottom: 20px; font-weight: 600;">Hubungi Kami</h3>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 16px;">
                        <li style="display: flex; gap: 12px; align-items: flex-start;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-top: 4px;"></i>
                            <span style="color: #a0a0b8; font-size: 14px; line-height: 1.5;">Dua Putri Motor (DPM)<br>Jl. Sapi Perah, Cipayung, Jakarta Timur</span>
                        </li>
                        <li style="display: flex; gap: 12px; align-items: center;">
                            <i class="fas fa-phone-alt" style="color: var(--primary);"></i>
                            <span style="color: #a0a0b8; font-size: 14px;">(021) 1234-5678 / 0812-3456-7890</span>
                        </li>
                        <li style="display: flex; gap: 12px; align-items: center;">
                            <i class="fas fa-envelope" style="color: var(--primary);"></i>
                            <span style="color: #a0a0b8; font-size: 14px;">cs@dpm-garage.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 24px; text-align: center; color: #7a7a9e; font-size: 13px;">
                &copy; <?= date('Y') ?> Sistem Pakar DPM Garage. Dibuat untuk Keperluan Skripsi.
            </div>
        </div>
    </footer>
    
    <style>
        footer a:hover { color: var(--primary) !important; }
    </style>
</body>
</html>
