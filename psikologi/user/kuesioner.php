<?php
session_start();
include '../koneksi.php';

$query = "SELECT i.*, a.nama_aspek FROM indikator i 
          JOIN aspek_hars a ON i.id_aspek = a.id_aspek 
          ORDER BY a.id_aspek ASC, i.id_indikator ASC";
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
        max-width: 900px;
        margin: 0 auto;
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
    }

    .petunjuk-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 40px;
    }
    .petunjuk-box h3 {
        font-size: 1.1rem;
        color: #1e293b;
        margin: 0 0 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .petunjuk-box p {
        font-size: 0.95rem;
        color: #475569;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .info-item h4 {
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .info-item ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .info-item li {
        font-size: 0.9rem;
        color: #1e293b;
        padding: 3px 0;
        display: flex;
        justify-content: space-between;
    }

    .aspect-group {
        margin-bottom: 40px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 20px;
    }
    .aspect-group:last-child {
        border-bottom: none;
    }
    .aspect-header {
        background: #eef2ff;
        color: #4338ca;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .aspect-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
    }
    .aspect-badge {
        font-size: 0.75rem;
        background: #fff;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }

    .pertanyaan-item {
        margin-bottom: 20px;
        padding-left: 10px;
    }
    .pertanyaan-teks {
        font-weight: 500;
        margin-bottom: 12px;
        color: #334155;
        font-size: 1rem;
    }
    .opsi-jawaban {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .opsi-label {
        cursor: pointer;
        padding: 8px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        font-size: 0.85rem;
    }
    .opsi-label:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .opsi-label input[type="radio"] {
        accent-color: #6366f1;
    }
    .opsi-label input[type="radio"]:checked + span {
        color: #6366f1;
        font-weight: 600;
    }
    .opsi-label:has(input:checked) {
        background: #eef2ff;
        border-color: #6366f1;
    }

    .btn-submit-wrapper {
        margin-top: 40px;
        text-align: center;
    }

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
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 600px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        .opsi-jawaban {
            flex-direction: column;
        }
    }
</style>
';
include 'header.php';
?>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <p class="loading-text">Memproses jawaban Anda...</p>
    </div>

    <div class="page-header">
        <div class="section-container">
            <h1>Kuesioner HARS</h1>
            <p>Hamilton Anxiety Rating Scale</p>
        </div>
    </div>

    <div class="kuesioner-wrapper">
        <div class="container-kuesioner">
            <div class="petunjuk-box">
                <h3><i class="fas fa-info-circle"></i> Petunjuk Pengisian</h3>
                <p>Pilihlah jawaban yang paling sesuai dengan kondisi yang kamu rasakan dalam satu minggu terakhir. Tidak ada jawaban benar atau salah. Jawablah sesuai keadaan yang kamu alami.</p>
                
                <div class="info-grid">
                    <div class="info-item">
                        <h4>Skor Jawaban</h4>
                        <ul>
                            <li>0 = Tidak ada</li>
                            <li>1 = Ringan</li>
                            <li>2 = Sedang</li>
                            <li>3 = Berat</li>
                            <li>4 = Berat sekali</li>
                        </ul>
                    </div>
                    <div class="info-item">
                        <h4>Klasifikasi Skor Total</h4>
                        <ul>
                            <li>< 14 : Tidak ada kecemasan</li>
                            <li>14 – 20 : Kecemasan ringan</li>
                            <li>21 – 27 : Kecemasan sedang</li>
                            <li>28 – 41 : Kecemasan berat</li>
                            <li>42 – 56 : Kecemasan berat sekali</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="proses_kuesioner.php" method="POST" id="formKuesioner">
                <?php
                $current_aspect = '';
                $aspect_count = 0;
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        if ($current_aspect != $row['nama_aspek']) {
                            if ($current_aspect != '') echo '</div>'; // Tutup group sebelumnya
                            $current_aspect = $row['nama_aspek'];
                            $aspect_count++;
                            echo '<div class="aspect-group">';
                            echo '<div class="aspect-header"><h4>' . $aspect_count . '. ' . $current_aspect . '</h4><span class="aspect-badge">Aspek HARS</span></div>';
                        }
                ?>
                <div class="pertanyaan-item">
                    <div class="pertanyaan-teks">
                        <?php echo $row['pertanyaan']; ?>
                    </div>
                    <div class="opsi-jawaban">
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="0" required> <span>0</span>
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="1" required> <span>1</span>
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="2" required> <span>2</span>
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="3" required> <span>3</span>
                        </label>
                        <label class="opsi-label">
                            <input type="radio" name="jawaban[<?php echo $row['id_indikator']; ?>]" value="4" required> <span>4</span>
                        </label>
                    </div>
                </div>
                <?php
                    }
                    echo '</div>'; // Tutup group terakhir
                }
                ?>
                <div class="btn-submit-wrapper">
                    <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit" style="width:100%; justify-content:center; padding: 15px;">
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
            loadingOverlay.classList.add('show');
        });
    </script>

<?php include 'footer.php'; ?>
