<?php
$page_title = 'Konsultasi HARS - Psikologi Kita';
$extra_css = '
<style>
    .container-konsul {
        max-width: 800px;
        margin: 100px auto 50px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .container-konsul h2 {
        color: #1e293b;
        border-bottom: 2px solid #f97316;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .container-konsul p {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 15px;
    }
    .box-info {
        background-color: #f0f7ff;
        border-left: 4px solid #f97316;
        padding: 15px;
        margin-bottom: 20px;
    }
    .checkbox-container {
        display: block;
        margin: 20px 0;
        padding: 15px;
        background: #f8fafc;
        border-radius: 5px;
        cursor: pointer;
    }
    .checkbox-container input {
        margin-right: 10px;
    }
    .btn-mulai {
        display: inline-block;
        background-color: #f97316;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
    }
    .btn-mulai.disabled {
        background-color: #cbd5e1;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
';
include 'header.php';
?>

    <div class="container-konsul">
        <h2>Persiapan Konsultasi (HARS)</h2>

        <div class="box-info">
            <strong>Apa itu Tes HARS?</strong><br>
            Hamilton Anxiety Rating Scale (HARS) adalah instrumen yang digunakan untuk menilai tingkat kecemasan seseorang. Tes ini mengukur 14 parameter gejala kecemasan seperti perasaan cemas, ketegangan, ketakutan, gangguan tidur, dan gejala fisik lainnya.
        </div>

        <p>Silakan jawab pertanyaan pada halaman selanjutnya dengan jujur sesuai dengan kondisi yang Anda rasakan akhir-akhir ini.</p>
        <p>Hasil dari kuesioner ini akan memberikan gambaran mengenai tingkat kecemasan yang Anda alami (Ringan, Sedang, atau Berat) dan catatan penanganan yang sesuai.</p>

        <label class="checkbox-container">
            <input type="checkbox" id="cekSetuju">
            Saya mengerti dan bersedia mengisi kuesioner ini dengan jujur.
        </label>

        <a href="kuesioner.php" id="tombolMulai" class="btn-mulai disabled">Mulai Kuesioner</a>
    </div>

    <script>
        const cekSetuju = document.getElementById('cekSetuju');
        const tombolMulai = document.getElementById('tombolMulai');

        cekSetuju.addEventListener('change', function() {
            if(this.checked) {
                tombolMulai.classList.remove('disabled');
            } else {
                tombolMulai.classList.add('disabled');
            }
        });
    </script>
<?php include 'footer.php'; ?>
