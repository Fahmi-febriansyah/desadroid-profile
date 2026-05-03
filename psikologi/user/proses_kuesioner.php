<?php
session_start();
include '../koneksi.php';

// Cek apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['jawaban'])) {
    header("Location: kuesioner.php");
    exit();
}

$jawaban = $_POST['jawaban'];

// Gunakan id_user dari session jika ada, kalau tidak pakai NULL
$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 'NULL';

// ============================================================
// LANGKAH 1: Hitung total skor dari semua jawaban
// ============================================================
$total_skor = 0;
foreach ($jawaban as $id_indikator => $nilai) {
    $total_skor += intval($nilai);
}

// ============================================================
// LANGKAH 2: FORWARD CHAINING - Hitung nilai per aspek
// ============================================================
// Ambil semua aspek
$aspek_query = "SELECT * FROM aspek_hars ORDER BY id_aspek ASC";
$aspek_result = mysqli_query($koneksi, $aspek_query);

$nilai_per_aspek = array(); // kode_aspek => rata-rata nilai

while ($aspek = mysqli_fetch_assoc($aspek_result)) {
    $id_aspek = $aspek['id_aspek'];
    $kode_aspek = $aspek['kode_aspek'];
    $nama_aspek = $aspek['nama_aspek'];

    // Cari semua indikator yang termasuk aspek ini
    $indikator_query = "SELECT id_indikator FROM indikator WHERE id_aspek = $id_aspek";
    $indikator_result = mysqli_query($koneksi, $indikator_query);

    $jumlah_nilai = 0;
    $jumlah_indikator = 0;

    while ($ind = mysqli_fetch_assoc($indikator_result)) {
        $id_ind = $ind['id_indikator'];
        if (isset($jawaban[$id_ind])) {
            $jumlah_nilai += intval($jawaban[$id_ind]);
            $jumlah_indikator++;
        }
    }

    // Hitung rata-rata nilai aspek
    $rata_rata = ($jumlah_indikator > 0) ? round($jumlah_nilai / $jumlah_indikator, 2) : 0;
    
    $nilai_per_aspek[$kode_aspek] = array(
        'id_aspek' => $id_aspek,
        'kode_aspek' => $kode_aspek,
        'nama_aspek' => $nama_aspek,
        'nilai' => $rata_rata
    );
}

// ============================================================
// LANGKAH 3: FORWARD CHAINING - Evaluasi rule per aspek
// ============================================================
$rule_query = "SELECT * FROM rule ORDER BY id_rule ASC";
$rule_result = mysqli_query($koneksi, $rule_query);

$rule_terpicu = array(); // Menyimpan rule yang terpicu (fired)

while ($rule = mysqli_fetch_assoc($rule_result)) {
    $kondisi = $rule['kondisi']; // contoh: "A1 > 2"
    
    // Parse kondisi: ambil kode aspek dan threshold
    // Format: "A1 > 2"
    $parts = explode(' ', trim($kondisi));
    if (count($parts) >= 3) {
        $kode = $parts[0];       // A1
        $operator = $parts[1];    // >
        $threshold = floatval($parts[2]); // 2
        
        if (isset($nilai_per_aspek[$kode])) {
            $nilai_aspek = $nilai_per_aspek[$kode]['nilai'];
            $terpicu = false;
            
            // Evaluasi kondisi
            switch ($operator) {
                case '>':
                    $terpicu = ($nilai_aspek > $threshold);
                    break;
                case '>=':
                    $terpicu = ($nilai_aspek >= $threshold);
                    break;
                case '<':
                    $terpicu = ($nilai_aspek < $threshold);
                    break;
                case '<=':
                    $terpicu = ($nilai_aspek <= $threshold);
                    break;
                case '==':
                    $terpicu = ($nilai_aspek == $threshold);
                    break;
            }
            
            $rule_terpicu[] = array(
                'kode_rule' => $rule['kode_rule'],
                'kondisi' => $kondisi,
                'hasil' => $rule['hasil'],
                'nilai_aktual' => $nilai_aspek,
                'terpicu' => $terpicu
            );
        }
    }
}

// ============================================================
// LANGKAH 4: FORWARD CHAINING - Tentukan kategori dari total skor
// ============================================================
$kategori = 'Tidak diketahui';
$kategori_query = "SELECT * FROM rule_kategori WHERE $total_skor BETWEEN min_skor AND max_skor LIMIT 1";
$kategori_result = mysqli_query($koneksi, $kategori_query);

if ($kat = mysqli_fetch_assoc($kategori_result)) {
    $kategori = $kat['kategori'];
}

// ============================================================
// LANGKAH 5: Ambil rekomendasi berdasarkan kategori
// ============================================================
$rekomendasi = 'Tidak ada rekomendasi';
$rekom_query = "SELECT * FROM rekomendasi WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kategori) . "' LIMIT 1";
$rekom_result = mysqli_query($koneksi, $rekom_query);

if ($rek = mysqli_fetch_assoc($rekom_result)) {
    $rekomendasi = $rek['isi'];
}

// ============================================================
// LANGKAH 6: Simpan hasil ke database
// ============================================================

// Simpan ke tabel konsultasi
$insert_konsultasi = "INSERT INTO konsultasi (id_user, total_skor, kategori) 
                      VALUES ($id_user, $total_skor, '" . mysqli_real_escape_string($koneksi, $kategori) . "')";
mysqli_query($koneksi, $insert_konsultasi);
$id_konsultasi = mysqli_insert_id($koneksi);

// Simpan jawaban per indikator ke tabel jawaban_user
foreach ($jawaban as $id_indikator => $nilai) {
    $id_ind = intval($id_indikator);
    $val = intval($nilai);
    $insert_jawaban = "INSERT INTO jawaban_user (id_konsultasi, id_indikator, nilai) VALUES ($id_konsultasi, $id_ind, $val)";
    mysqli_query($koneksi, $insert_jawaban);
}

// Simpan nilai per aspek ke tabel hasil_aspek
foreach ($nilai_per_aspek as $kode => $data) {
    $id_asp = $data['id_aspek'];
    $val_asp = $data['nilai'];
    $insert_aspek = "INSERT INTO hasil_aspek (id_konsultasi, id_aspek, nilai_aspek) VALUES ($id_konsultasi, $id_asp, $val_asp)";
    mysqli_query($koneksi, $insert_aspek);
}

// ============================================================
// LANGKAH 7: Simpan data ke session untuk ditampilkan di hasil.php
// ============================================================
$_SESSION['hasil_konsultasi'] = array(
    'id_konsultasi' => $id_konsultasi,
    'total_skor' => $total_skor,
    'kategori' => $kategori,
    'rekomendasi' => $rekomendasi,
    'nilai_per_aspek' => $nilai_per_aspek,
    'rule_terpicu' => $rule_terpicu,
    'tanggal' => date('Y-m-d H:i:s')
);

// Redirect ke halaman hasil
header("Location: hasil.php");
exit();
?>
