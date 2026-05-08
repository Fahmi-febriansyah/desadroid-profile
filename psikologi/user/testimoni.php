<?php
session_start();
include '../koneksi.php';

$page_title = 'Isi Testimoni - Psikologi Kita';
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
    .testimoni-wrapper {
        padding: 60px 24px;
        background: #f8fafc;
        min-height: calc(100vh - 400px);
    }
    .container-testimoni {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1e293b;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
        transition: all 0.3s;
        resize: vertical;
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    }
    .btn-submit-wrapper {
        margin-top: 30px;
    }
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
</style>
';
include 'header.php';
?>

    <div class="page-header">
        <div class="section-container">
            <h1>Testimoni</h1>
            <p>Bagikan pengalaman Anda menggunakan layanan konsultasi kami.</p>
        </div>
    </div>

    <div class="testimoni-wrapper">
        <div class="container-testimoni">
            <?php
            if (isset($_SESSION['pesan'])) {
                echo '<div class="alert-success">'.$_SESSION['pesan'].'</div>';
                unset($_SESSION['pesan']);
            }

            $sudah_testi = false;
            if (isset($_SESSION['id_user'])) {
                $id_u = intval($_SESSION['id_user']);
                $cek_testi = mysqli_query($koneksi, "SELECT id_testimoni FROM testimoni WHERE id_user = $id_u LIMIT 1");
                if (mysqli_num_rows($cek_testi) > 0) {
                    $sudah_testi = true;
                }
            }

            if ($sudah_testi): ?>
                <div style="text-align:center; padding:20px;">
                    <div style="font-size:3rem; color:#10b981; margin-bottom:15px;"><i class="fas fa-check-circle"></i></div>
                    <h3 style="color:#1e293b; margin-bottom:10px;">Anda Sudah Memberikan Testimoni</h3>
                    <p style="color:#64748b;">Terima kasih atas masukan Anda. Setiap pengguna hanya dapat memberikan testimoni sebanyak satu kali.</p>
                    <a href="index.php" class="btn btn-outline" style="margin-top:20px; display:inline-block;">Kembali ke Beranda</a>
                </div>
            <?php else: ?>
            <form action="proses_testimoni.php" method="POST">
                <div class="form-group">
                    <label for="isi">Pesan Testimoni Anda</label>
                    <textarea name="isi" id="isi" rows="5" class="form-control" placeholder="Ceritakan bagaimana layanan kami membantu Anda..." required></textarea>
                </div>
                <div class="btn-submit-wrapper">
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Kirim Testimoni
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

<?php include 'footer.php'; ?>
