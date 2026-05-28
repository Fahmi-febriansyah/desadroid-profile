<?php
require_once 'config/db.php';
include 'partials/header.php';

// Get slug from query parameter
$slug = $_GET['slug'] ?? 'portofolio';
$projectLink = 'https://project.desadroid.shop/project/' . urlencode($slug);
?>

<section style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div class="container">
        <div style="max-width: 600px; margin: 0 auto; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 3rem; border-radius: 12px; color: white; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📁</div>
            <h1 style="font-size: 2rem; margin-bottom: 1rem; color: white;">Detail Proyek Dipindahkan</h1>
            <p style="font-size: 1.1rem; margin-bottom: 1.5rem; line-height: 1.6; color: rgba(255,255,255,0.95);">
                Detail proyek ini telah dipindahkan ke platform portofolio kami yang baru dan lebih lengkap. 
                Klik tombol di bawah untuk melihat informasi lengkap proyek ini.
            </p>
            <div style="margin: 2rem 0;">
                <p style="font-size: 0.95rem; color: rgba(255,255,255,0.85); margin-bottom: 1.5rem;">
                    Anda akan dialihkan secara otomatis dalam <strong id="countdown">5</strong> detik...
                </p>
            </div>
            <a href="<?= htmlspecialchars($projectLink) ?>" style="display: inline-block; padding: 1rem 2.5rem; background: white; color: #667eea; text-decoration: none; border-radius: 6px; font-weight: 700; font-size: 1rem; transition: all 0.3s; border: none; cursor: pointer;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                 Lihat Detail Proyek
            </a>
            <p style="margin-top: 2rem; font-size: 0.9rem; color: rgba(255,255,255,0.8);">
                Jika tidak dialihkan otomatis, klik tombol di atas
            </p>
        </div>
    </div>
</section>

<script>
let countdown = 5;
const countdownEl = document.getElementById('countdown');
const interval = setInterval(() => {
    countdown--;
    countdownEl.textContent = countdown;
    if (countdown <= 0) {
        clearInterval(interval);
        window.location.href = '<?= htmlspecialchars($projectLink) ?>';
    }
}, 1000);
</script>

<?php include 'partials/footer.php'; ?>
