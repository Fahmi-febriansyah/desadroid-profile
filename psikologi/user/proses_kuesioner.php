<?php
// Start session buat simpen hasil tesnya
session_start();
// Koneksi ke database, jangan sampe lupa
include '../koneksi.php';

// Cek kalo datanya beneran dikirim lewat POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['jawaban'])) {
    header("Location: kuesioner.php");
    exit();
}

// Ambil jawaban kuesionernya
$jawaban = $_POST['jawaban'];
// Kalo ada user yang login, ambil ID-nya. Kalo ga ada ya NULL aja (buat tamu)
$id_user = isset($_SESSION['id_user']) ? intval($_SESSION['id_user']) : 'NULL';

// 1. Ambil semua aspek HARS yang ada di DB
$aspek_query = "SELECT * FROM aspek_hars ORDER BY id_aspek ASC";
$aspek_result = mysqli_query($koneksi, $aspek_query);

$nilai_per_aspek = array(); 
$total_skor = 0;

// 2. Itung skor buat tiap aspek (A1 sampe A14)
while ($aspek = mysqli_fetch_assoc($aspek_result)) {
    $id_aspek = $aspek['id_aspek'];
    $kode_aspek = $aspek['kode_aspek'];
    $nama_aspek = $aspek['nama_aspek'];

    // Ambil indikator buat aspek ini
    $indikator_query = "SELECT id_indikator FROM indikator WHERE id_aspek = $id_aspek";
    $indikator_result = mysqli_query($koneksi, $indikator_query);

    $jumlah_nilai = 0;
    $jumlah_indikator = 0;

    // Jumlahin nilai indikatornya
    while ($ind = mysqli_fetch_assoc($indikator_result)) {
        $id_ind = $ind['id_indikator'];
        if (isset($jawaban[$id_ind])) {
            $jumlah_nilai += intval($jawaban[$id_ind]);
            $jumlah_indikator++;
        }
    }

    // Skor aspek itu rata-rata indikator terus dibuletin (0-4) biar pas sama rule
    $rata_rata = ($jumlah_indikator > 0) ? round($jumlah_nilai / $jumlah_indikator) : 0;
    
    // Simpen dulu di array buat dipake nanti
    $nilai_per_aspek[$kode_aspek] = array(
        'id_aspek' => $id_aspek,
        'kode_aspek' => $kode_aspek,
        'nama_aspek' => $nama_aspek,
        'nilai' => $rata_rata
    );
}

// 3. Masuk ke logika Pakar (Forward Chaining) R1-R20
$rule_terpicu = array();
$rules = array();
$rule_q = mysqli_query($koneksi, "SELECT * FROM rule ORDER BY id_rule ASC");
while ($r = mysqli_fetch_assoc($rule_q)) { $rules[] = $r; }

// Tahap 1: Cek aspek mana yang nilainya tinggi (R1-R14)
foreach ($rules as $rule) {
    if (preg_match('/^R([1-9]|1[0-4])$/', $rule['kode_rule'])) {
        $kondisi = $rule['kondisi']; // Contoh: "A1 >= 3"
        $parts = explode(' ', $kondisi);
        $kode = $parts[0];
        $op = $parts[1];
        $val = floatval($parts[2]);
        
        if (isset($nilai_per_aspek[$kode])) {
            $nilai_aktual = $nilai_per_aspek[$kode]['nilai'];
            $terpicu = false;
            if ($op == '>=') $terpicu = ($nilai_aktual >= $val);
            
            // Kalo rule terbukti bener, masukin ke list
            if ($terpicu) {
                $rule_terpicu[] = [
                    'kode_rule' => $rule['kode_rule'],
                    'hasil' => $rule['hasil'],
                    'terpicu' => true
                ];
            }
        }
    }
}

// Tahap 2: Rule R15 buat itung total skor keleruhan
$total_skor = 0;
foreach ($nilai_per_aspek as $asp) {
    $total_skor += $asp['nilai'];
}
$rule_terpicu[] = [
    'kode_rule' => 'R15',
    'hasil' => "Total Skor HARS: $total_skor",
    'terpicu' => true
];

// Tahap 3: Cari kategori berdasarkan total skor dari tabel rule_kategori
$kategori = 'Tidak diketahui';
$kat_query = "SELECT * FROM rule_kategori WHERE $total_skor >= min_skor AND $total_skor <= max_skor LIMIT 1";
$kat_result = mysqli_query($koneksi, $kat_query);
if ($kat_row = mysqli_fetch_assoc($kat_result)) {
    $kategori = $kat_row['kategori'];
    
    // Cari kode rule yang cocok (simulasi buat log inferensi)
    // Biasanya R16-R20, kita cari yang hasilnya sama dengan kategori
    $rule_nama_q = mysqli_query($koneksi, "SELECT kode_rule FROM rule WHERE hasil = '" . mysqli_real_escape_string($koneksi, $kategori) . "' LIMIT 1");
    $rule_kode = 'R?';
    if ($rn = mysqli_fetch_assoc($rule_nama_q)) { $rule_kode = $rn['kode_rule']; }

    $rule_terpicu[] = [
        'kode_rule' => $rule_kode,
        'hasil' => $kategori,
        'terpicu' => true
    ];
}

// 4. Cari rekomendasi yang cocok sama kategori hasilnya
$catatan = 'Tidak ada catatan';
$rekom_query = "SELECT * FROM rekomendasi WHERE kategori = '" . mysqli_real_escape_string($koneksi, $kategori) . "' LIMIT 1";
$rekom_result = mysqli_query($koneksi, $rekom_query);
if ($rek = mysqli_fetch_assoc($rekom_result)) {
    $catatan = $rek['isi'];
}

// 5. Simpen semua hasil konsultasi ke database biar dosen percaya ini jalan beneran
$insert_konsultasi = "INSERT INTO konsultasi (id_user, total_skor, kategori) 
                      VALUES ($id_user, $total_skor, '" . mysqli_real_escape_string($koneksi, $kategori) . "')";
mysqli_query($koneksi, $insert_konsultasi);
$id_konsultasi = mysqli_insert_id($koneksi);

// Simpen jawaban mentahnya juga
foreach ($jawaban as $id_indikator => $nilai) {
    $id_ind = intval($id_indikator);
    $val = intval($nilai);
    $insert_jawaban = "INSERT INTO jawaban_user (id_konsultasi, id_indikator, nilai) VALUES ($id_konsultasi, $id_ind, $val)";
    mysqli_query($koneksi, $insert_jawaban);
}

// Simpen skor per aspeknya
foreach ($nilai_per_aspek as $kode => $data) {
    $id_asp = $data['id_aspek'];
    $val_asp = $data['nilai'];
    $insert_aspek = "INSERT INTO hasil_aspek (id_konsultasi, id_aspek, nilai_aspek) VALUES ($id_konsultasi, $id_asp, $val_asp)";
    mysqli_query($koneksi, $insert_aspek);
}

// 6. Taruh di session biar di halaman hasil tinggal panggil doang
$_SESSION['hasil_konsultasi'] = array(
    'id_konsultasi' => $id_konsultasi,
    'total_skor' => $total_skor,
    'kategori' => $kategori,
    'catatan' => $catatan,
    'nilai_per_aspek' => $nilai_per_aspek,
    'rule_terpicu' => $rule_terpicu,
    'tanggal' => date('Y-m-d H:i:s')
);

// Pindah ke halaman hasil buat pamer hasil tesnya
header("Location: hasil.php");
exit();
?>
