<?php
session_start();
include '../koneksi.php';

$page_title = 'Psikolog Kami - Psikologi Kita';
$extra_css = '
<style>
    .page-header {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
        color: #fff;
        text-align: center;
    }
    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .psikolog-wrapper {
        padding: 80px 24px;
        background: #f8fafc;
    }
    .psikolog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .psikolog-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .psikolog-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .psikolog-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    .psikolog-info {
        padding: 24px;
    }
    .psikolog-info h3 {
        font-size: 1.25rem;
        color: #1e293b;
        margin-bottom: 5px;
    }
    .psikolog-info .spesialis {
        color: #f97316;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 15px;
        display: block;
    }
    .psikolog-info p {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .psikolog-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
    }
    .rating {
        color: #f59e0b;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
    }
</style>
';
include 'header.php';
?>

    <div class="page-header">
        <div class="section-container">
            <h1>Psikolog Kami</h1>
            <p>Konsultasikan masalah Anda dengan tenaga ahli profesional kami.</p>
        </div>
    </div>

    <div class="psikolog-wrapper">
        <div class="psikolog-grid">

            <div class="psikolog-card">
                <img src="https://images.unsplash.com/photo-1559839734-2b71f1536783?q=80&w=1470&auto=format&fit=crop" alt="Psikolog 1" class="psikolog-img">
                <div class="psikolog-info">
                    <h3>Dr. Sarah Jasmine, M.Psi</h3>
                    <span class="spesialis">Spesialis Kecemasan & Depresi</span>
                    <p>Berpengalaman lebih dari 10 tahun dalam menangani berbagai kasus gangguan kecemasan pada orang dewasa.</p>
                    <div class="psikolog-footer">
                        <div class="rating">
                            <i class="fas fa-star"></i> 4.9 (120+ Sesi)
                        </div>
                    </div>
                </div>
            </div>

            <div class="psikolog-card">
                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=1464&auto=format&fit=crop" alt="Psikolog 2" class="psikolog-img">
                <div class="psikolog-info">
                    <h3>Budi Setiawan, M.Psi</h3>
                    <span class="spesialis">Psikolog Klinis Dewasa</span>
                    <p>Fokus pada pengembangan diri dan manajemen stres kerja untuk profesional muda.</p>
                    <div class="psikolog-footer">
                        <div class="rating">
                            <i class="fas fa-star"></i> 4.8 (85+ Sesi)
                        </div>
                    </div>
                </div>
            </div>

            <div class="psikolog-card">
                <img src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?q=80&w=1374&auto=format&fit=crop" alt="Psikolog 3" class="psikolog-img">
                <div class="psikolog-info">
                    <h3>Anita Wijaya, M.Psi</h3>
                    <span class="spesialis">Psikolog Anak & Remaja</span>
                    <p>Membantu anak-anak dan remaja dalam mengatasi masalah emosional dan perilaku di sekolah.</p>
                    <div class="psikolog-footer">
                        <div class="rating">
                            <i class="fas fa-star"></i> 5.0 (50+ Sesi)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>
