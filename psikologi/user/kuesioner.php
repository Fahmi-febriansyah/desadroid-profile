<?php
session_start();
include '../koneksi.php';

// Ambil data pertanyaan dari database
$query = "SELECT i.*, a.kode_aspek, a.nama_aspek FROM indikator i 
          JOIN aspek_hars a ON i.id_aspek = a.id_aspek 
          ORDER BY i.id_indikator ASC";
$result = mysqli_query($koneksi, $query);

$page_title = 'Kuesioner HARS - Psikologi Kita';
$extra_css = '
<style>
    .page-header {
        padding: 120px 0 60px;
        background: linear-gradient(135deg, #1e1b4b, #312e81);
        color: #fff;
        text-align: center;
    }
    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .kuesioner-wrapper {
        padding: 60px 24px;
        background: #f8fafc;
        min-height: calc(100vh - 400px);
    }
    .container-kuesioner {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
    }
    .container-kuesioner p.subtitle {
        text-align: center;
        color: #64748b;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .pertanyaan-item {
        margin-bottom: 24px;
        background: #fff;
    }
    .pertanyaan-teks {
        font-weight: 600;
        margin-bottom: 15px;
        color: #1e293b;
        font-size: 1.05rem;
    }
    .opsi-jawaban {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 10px;
    }
    .opsi-label {
        cursor: pointer;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
    }
    .opsi-label:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }
    .opsi-label input[type="radio"] {
        accent-color: #6366f1;
        width: 16px;
        height: 16px;
    }
    .btn-submit-wrapper {
        margin-top: 40px;
        text-align: center;
    }

    /* Loading Overlay */
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.92);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 24px;
    }
    .loading-overlay.show {
        display: flex;
    }
    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #e2e8f0;
        border-top: 5px solid #6366f1;
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }
    .loading-text {
        font-size: 1.1rem;
        color: #1e293b;
        font-weight: 600;
    }
    .loading-subtext {
        font-size: 0.9rem;
        color: #64748b;
        margin-top: -16px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
';
include 'header.php';
?>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <p class="loading-text">Memproses jawaban Anda...</p>
        <p class="loading-subtext">Sistem sedang menganalisis menggunakan metode Forward Chaining</p>
    </div>

    <div class="page-header">
        <div class="section-container">
            <h1>Kuesioner HARS</h1>
            <p>Jawablah pertanyaan berikut dengan jujur sesuai kondisi Anda saat ini.</p>
        </div>
    </div>

    <div class="kuesioner-wrapper">
        <div class="container-kuesioner">
            <p class="subtitle">Silakan pilih salah satu jawaban yang paling mendeskripsikan intensitas gejala yang Anda rasakan.</p>

            <form action="proses_kuesioner.php" method="POST" id="formKuesioner">
                <?php
                if(mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="pertanyaan-item">
                    <div class="pertanyaan-teks">
                        <?php echo $no . ". " . $row['pertanyaan']; ?>
                    </div>
                    <div class="opsi-jawaban">
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="0" required> Tidak Ada
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="1" required> Ringan
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="2" required> Sedang
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="3" required> Berat
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="4" required> Sangat Berat
                        </label>
                    </div>
                </div>
                <?php
                        $no++;
                    }
                } else {
                    echo "<p>Tidak ada pertanyaan yang tersedia.</p>";
                }
                ?>
                <div class="btn-submit-wrapper">
                    <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Kirim Jawaban & Lihat Hasil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        var formKuesioner = document.getElementById('formKuesioner');
        var loadingOverlay = document.getElementById('loadingOverlay');

        formKuesioner.addEventListener('submit', function(e) {
            // Tampilkan loading overlay saat form di-submit
            loadingOverlay.classList.add('show');
        });
    </script>

<?php include 'footer.php'; ?>
