<?php
$page_title = "Basis Pengetahuan - Admin Psikologi";
$active_menu = "system";
include '../koneksi.php';

// --- HANDLE POST ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Aspek Actions
    if (isset($_POST['save_aspek'])) {
        $id = intval($_POST['id_aspek']);
        $kode = mysqli_real_escape_string($koneksi, $_POST['kode_aspek']);
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama_aspek']);
        if ($id > 0) {
            mysqli_query($koneksi, "UPDATE aspek_hars SET kode_aspek='$kode', nama_aspek='$nama' WHERE id_aspek=$id");
        } else {
            mysqli_query($koneksi, "INSERT INTO aspek_hars (kode_aspek, nama_aspek) VALUES ('$kode', '$nama')");
        }
        header("Location: knowledge_base.php?tab=aspek&status=success"); exit();
    }

    // 2. Indikator Actions
    if (isset($_POST['save_indikator'])) {
        $id = intval($_POST['id_indikator']);
        $id_aspek = intval($_POST['id_aspek']);
        $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
        if ($id > 0) {
            mysqli_query($koneksi, "UPDATE indikator SET pertanyaan='$pertanyaan' WHERE id_indikator=$id");
        } else {
            mysqli_query($koneksi, "INSERT INTO indikator (id_aspek, pertanyaan) VALUES ($id_aspek, '$pertanyaan')");
        }
        header("Location: knowledge_base.php?tab=aspek&status=success"); exit();
    }

    // 3. Rule Actions (UPGRADED TO DROPDOWN COMBINATION)
    if (isset($_POST['save_rule'])) {
        $id = intval($_POST['id_rule']);
        $kode = mysqli_real_escape_string($koneksi, $_POST['kode_rule']);
        $hasil = mysqli_real_escape_string($koneksi, $_POST['hasil']);
        
        // Gabungkan 3 input dropdown/angka jadi satu string kondisi
        $aspek_kode = $_POST['rule_aspek_kode'];
        $operator = $_POST['rule_operator'];
        $nilai = $_POST['rule_nilai'];
        $kondisi = "$aspek_kode $operator $nilai";
        
        if ($id > 0) {
            mysqli_query($koneksi, "UPDATE rule SET kode_rule='$kode', kondisi='$kondisi', hasil='$hasil' WHERE id_rule=$id");
        } else {
            mysqli_query($koneksi, "INSERT INTO rule (kode_rule, kondisi, hasil) VALUES ('$kode', '$kondisi', '$hasil')");
        }
        header("Location: knowledge_base.php?tab=rules&status=success"); exit();
    }

    // 4. Rule Kategori Actions
    if (isset($_POST['save_kat'])) {
        $id = intval($_POST['id_kategori']);
        $min = intval($_POST['min_skor']);
        $max = intval($_POST['max_skor']);
        $kat = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        mysqli_query($koneksi, "UPDATE rule_kategori SET min_skor=$min, max_skor=$max, kategori='$kat' WHERE id_kategori=$id");
        header("Location: knowledge_base.php?tab=results&status=success"); exit();
    }

    // 5. Rekomendasi Actions
    if (isset($_POST['save_rekom'])) {
        $id = intval($_POST['id_rekomendasi']);
        $kat = mysqli_real_escape_string($koneksi, $_POST['kategori']);
        $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
        mysqli_query($koneksi, "UPDATE rekomendasi SET kategori='$kat', isi='$isi' WHERE id_rekomendasi=$id");
        header("Location: knowledge_base.php?tab=results&status=success"); exit();
    }
}

// --- HANDLE DELETE ACTIONS ---
if (isset($_GET['delete_aspek'])) {
    $id = intval($_GET['delete_aspek']);
    mysqli_query($koneksi, "DELETE FROM indikator WHERE id_aspek = $id");
    mysqli_query($koneksi, "DELETE FROM aspek_hars WHERE id_aspek = $id");
    header("Location: knowledge_base.php?tab=aspek&status=deleted"); exit();
}
if (isset($_GET['delete_indikator'])) {
    $id = intval($_GET['delete_indikator']);
    mysqli_query($koneksi, "DELETE FROM indikator WHERE id_indikator = $id");
    header("Location: knowledge_base.php?tab=aspek&status=deleted"); exit();
}
if (isset($_GET['delete_rule'])) {
    $id = intval($_GET['delete_rule']);
    mysqli_query($koneksi, "DELETE FROM rule WHERE id_rule = $id");
    header("Location: knowledge_base.php?tab=rules&status=deleted"); exit();
}

// --- FETCH DATA ---
$aspek_list = array();
$aspeks = mysqli_query($koneksi, "SELECT * FROM aspek_hars ORDER BY id_aspek ASC");
while($row = mysqli_fetch_assoc($aspeks)) { $aspek_list[] = $row; }

$rules = mysqli_query($koneksi, "SELECT * FROM rule ORDER BY id_rule ASC");
$kategoris = mysqli_query($koneksi, "SELECT * FROM rule_kategori ORDER BY min_skor ASC");
$rekomendasis = mysqli_query($koneksi, "SELECT * FROM rekomendasi ORDER BY id_rekomendasi ASC");

$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'aspek';

include 'header.php';
?>

<style>
    .tab-nav { display: flex; gap: 12px; margin-bottom: 24px; background: #fff; padding: 12px 20px; border-radius: 12px; box-shadow: var(--shadow-sm); }
    .tab-btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; border: none; background: none; cursor: pointer; color: var(--text-light); transition: 0.2s; }
    .tab-btn:hover { background: #f1f5f9; color: var(--primary); }
    .tab-btn.active { background: var(--primary); color: #fff; }
    
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    
    .aspek-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 20px; box-shadow: var(--shadow-sm); }
    .aspek-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
    .aspek-info h3 { font-size: 1rem; margin: 0; color: #1e293b; }
    .indikator-item { padding: 12px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
    .modal.show { display: flex; }
    .modal-content { background: #fff; padding: 32px; border-radius: 16px; width: 90%; max-width: 500px; animation: slideUp 0.3s; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.85rem; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; outline: none; }
    .form-group select:focus, .form-group input:focus { border-color: var(--primary); }
    .modal-footer { margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; }
    
    .btn-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: 0.2s; }
    .btn-edit-s { background: #e0e7ff; color: #4f46e5; }
    .btn-delete-s { background: #fee2e2; color: #ef4444; }

    .rule-builder { background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 16px; }
    .rule-builder-title { font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 12px; display: block; }
</style>

<div class="page-header" style="margin-bottom: 24px;">
    <div class="page-title">
        <h1>Basis Pengetahuan Pakar</h1>
        <p>Kelola data diagnosa, aturan sistem, dan rekomendasi hasil secara terpusat.</p>
    </div>
</div>

<div class="tab-nav">
    <button class="tab-btn <?php echo ($current_tab == 'aspek') ? 'active' : ''; ?>" onclick="switchTab('aspek')">Aspek & Pertanyaan</button>
    <button class="tab-btn <?php echo ($current_tab == 'rules') ? 'active' : ''; ?>" onclick="switchTab('rules')">Aturan Forward Chaining</button>
    <button class="tab-btn <?php echo ($current_tab == 'results') ? 'active' : ''; ?>" onclick="switchTab('results')">Kategori & Saran</button>
</div>

<!-- SECTION 1: ASPEK & INDIKATOR -->
<div id="tab-aspek" class="tab-content <?php echo ($current_tab == 'aspek') ? 'active' : ''; ?>">
    <div style="margin-bottom: 20px; display: flex; justify-content: flex-end; gap: 10px;">
        <a href="print_data.php?type=knowledge_aspek" target="_blank" class="btn" style="background: #1e293b; color: #fff;">
            <i class="fas fa-print"></i> Cetak Aspek
        </a>
        <button class="btn btn-primary" onclick="openAspekModal()"><i class="fas fa-plus"></i> Tambah Aspek Baru</button>
    </div>
    
    <?php foreach($aspek_list as $aspek): ?>
        <div class="aspek-card">
            <div class="aspek-header">
                <div class="aspek-info">
                    <span style="background: #6366f1; color:#fff; padding:2px 8px; border-radius:4px; font-size:0.75rem; font-weight:700; margin-right:8px;"><?php echo $aspek['kode_aspek']; ?></span>
                    <h3><?php echo htmlspecialchars($aspek['nama_aspek']); ?></h3>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn-icon btn-edit-s" onclick="openAspekModal(<?php echo htmlspecialchars(json_encode($aspek)); ?>)"><i class="fas fa-edit"></i></button>
                    <a href="?delete_aspek=<?php echo $aspek['id_aspek']; ?>" class="btn-icon btn-delete-s" onclick="return confirm('Menghapus aspek akan menghapus semua pertanyaannya. Lanjutkan?')"><i class="fas fa-trash"></i></a>
                </div>
            </div>
            <div class="indikator-list">
                <?php 
                $id_asp = $aspek['id_aspek'];
                $ind_q = mysqli_query($koneksi, "SELECT * FROM indikator WHERE id_aspek = $id_asp");
                while($ind = mysqli_fetch_assoc($ind_q)):
                ?>
                    <div class="indikator-item">
                        <span style="font-size: 0.9rem; color: #475569;"><?php echo htmlspecialchars($ind['pertanyaan']); ?></span>
                        <div style="display: flex; gap: 8px;">
                            <button class="btn-icon" style="background:#f1f5f9; color:#64748b;" onclick="openIndikatorModal(<?php echo $id_asp; ?>, <?php echo htmlspecialchars(json_encode($ind)); ?>)"><i class="fas fa-pen-nib"></i></button>
                            <a href="?delete_indikator=<?php echo $ind['id_indikator']; ?>" class="btn-icon btn-delete-s" style="width:24px; height:24px;"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <div style="padding: 12px 20px; background: #fafafa;">
                    <button class="btn" style="padding: 6px 12px; font-size: 0.8rem; background: #fff; border: 1px dashed #cbd5e1;" onclick="openIndikatorModal(<?php echo $id_asp; ?>)">
                        <i class="fas fa-plus-circle"></i> Tambah Pertanyaan/Gejala
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- SECTION 2: RULES -->
<div id="tab-rules" class="tab-content <?php echo ($current_tab == 'rules') ? 'active' : ''; ?>">
    <div class="card">
        <div class="card-header">
            <h2>Logika Sistem (IF-THEN Rules)</h2>
            <div style="display: flex; gap: 15px; align-items: center;">
                <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 0.85rem; color: #1e40af; flex: 1;">
                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> Aturan R1-R14 (Aspek) dikelola di sini. Aturan klasifikasi akhir (R16-R20) otomatis sinkron dengan tab <strong>Kategori & Saran</strong>.
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="print_data.php?type=knowledge_rules" target="_blank" class="btn" style="background: #1e293b; color: #fff;">
                        <i class="fas fa-print"></i> Cetak Aturan
                    </a>
                    <button class="btn btn-primary" onclick="openRuleModal()"><i class="fas fa-plus"></i> Tambah Rule</button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Kode</th><th>Kondisi (IF)</th><th>Kesimpulan (THEN)</th><th style="width:100px;">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php while($rule = mysqli_fetch_assoc($rules)): ?>
                    <tr>
                        <td><span class="status status-info"><?php echo $rule['kode_rule']; ?></span></td>
                        <td><code><?php echo htmlspecialchars($rule['kondisi']); ?></code></td>
                        <td><strong><?php echo htmlspecialchars($rule['hasil']); ?></strong></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn-icon btn-edit-s" onclick="openRuleModal(<?php echo htmlspecialchars(json_encode($rule)); ?>)"><i class="fas fa-edit"></i></button>
                                <a href="?delete_rule=<?php echo $rule['id_rule']; ?>" class="btn-icon btn-delete-s" onclick="return confirm('Hapus rule ini?')"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SECTION 3: KATEGORI & SARAN -->
<div id="tab-results" class="tab-content <?php echo ($current_tab == 'results') ? 'active' : ''; ?>">
    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h2>Ambang Skor</h2>
                <a href="print_data.php?type=knowledge_results" target="_blank" class="btn" style="background: #1e293b; color: #fff; font-size: 0.8rem;">
                    <i class="fas fa-print"></i> Cetak Kategori & Saran
                </a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Kategori</th><th>Range Skor</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php while($kat = mysqli_fetch_assoc($kategoris)): ?>
                        <tr>
                            <td><strong><?php echo $kat['kategori']; ?></strong></td>
                            <td><?php echo $kat['min_skor']; ?> - <?php echo $kat['max_skor']; ?></td>
                            <td><button class="btn-icon btn-edit-s" onclick="openKatModal(<?php echo htmlspecialchars(json_encode($kat)); ?>)"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h2>Rekomendasi / Saran</h2></div>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Kategori</th><th>Isi Saran</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php while($rek = mysqli_fetch_assoc($rekomendasis)): ?>
                        <tr>
                            <td><span class="status status-success"><?php echo $rek['kategori']; ?></span></td>
                            <td style="font-size: 0.8rem;"><?php echo (strlen($rek['isi']) > 120) ? substr(htmlspecialchars($rek['isi']), 0, 120) . '...' : htmlspecialchars($rek['isi']); ?></td>
                            <td><button class="btn-icon btn-edit-s" onclick="openRekomModal(<?php echo htmlspecialchars(json_encode($rek)); ?>)"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- --- ALL MODALS --- -->

<!-- Aspek Modal -->
<div id="aspekModal" class="modal">
    <div class="modal-content">
        <h2 id="aspekModalTitle">Aspek HARS</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_aspek" id="id_aspek">
            <div class="form-group"><label>Kode Aspek (Contoh: A1)</label><input type="text" name="kode_aspek" id="kode_aspek" required></div>
            <div class="form-group"><label>Nama Aspek</label><input type="text" name="nama_aspek" id="nama_aspek" required></div>
            <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('aspekModal')">Batal</button><button type="submit" name="save_aspek" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Indikator Modal -->
<div id="indModal" class="modal">
    <div class="modal-content">
        <h2 id="indModalTitle">Pertanyaan/Gejala</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_indikator" id="id_indikator">
            <input type="hidden" name="id_aspek" id="ind_id_aspek">
            <div class="form-group"><label>Isi Pertanyaan/Gejala</label><textarea name="pertanyaan" id="pertanyaan" rows="4" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('indModal')">Batal</button><button type="submit" name="save_indikator" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Rule Modal (UPGRADED WITH DROPDOWNS) -->
<div id="ruleModal" class="modal">
    <div class="modal-content">
        <h2 id="ruleModalTitle">Aturan Keputusan</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_rule" id="id_rule">
            <div class="form-group"><label>Kode Rule (Contoh: R1)</label><input type="text" name="kode_rule" id="kode_rule" required></div>
            
            <div class="rule-builder">
                <span class="rule-builder-title">Kondisi (IF)</span>
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 8px;">
                    <div class="form-group">
                        <label>Aspek</label>
                        <select name="rule_aspek_kode" id="rule_aspek_kode">
                            <?php foreach($aspek_list as $a): ?>
                                <option value="<?php echo $a['kode_aspek']; ?>"><?php echo $a['kode_aspek']; ?> - <?php echo $a['nama_aspek']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Op</label>
                        <select name="rule_operator" id="rule_operator">
                            <option value=">=">>=</option>
                            <option value=">">></option>
                            <option value="<="><=</option>
                            <option value="<"><</option>
                            <option value="==">==</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <input type="number" name="rule_nilai" id="rule_nilai" value="3" step="1">
                    </div>
                </div>
            </div>

            <div class="form-group"><label>Kesimpulan (THEN)</label><textarea name="hasil" id="hasil_rule" rows="3" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('ruleModal')">Batal</button><button type="submit" name="save_rule" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Kategori Modal -->
<div id="katModal" class="modal">
    <div class="modal-content">
        <h2>Atur Ambang Skor</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_kategori" id="id_kategori">
            <div class="form-group"><label>Nama Kategori</label><input type="text" name="kategori" id="kat_nama" readonly></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group"><label>Min Skor</label><input type="number" name="min_skor" id="kat_min" required></div>
                <div class="form-group"><label>Max Skor</label><input type="number" name="max_skor" id="kat_max" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('katModal')">Batal</button><button type="submit" name="save_kat" class="btn btn-primary">Simpan Perubahan</button></div>
        </form>
    </div>
</div>

<!-- Rekomendasi Modal -->
<div id="rekomModal" class="modal">
    <div class="modal-content">
        <h2>Edit Rekomendasi</h2>
        <form action="" method="POST">
            <input type="hidden" name="id_rekomendasi" id="id_rekomendasi">
            <div class="form-group"><label>Kategori</label><input type="text" name="kategori" id="rekom_kat" readonly></div>
            <div class="form-group"><label>Isi Rekomendasi</label><textarea name="isi" id="rekom_isi" rows="6" required></textarea></div>
            <div class="modal-footer"><button type="button" class="btn" onclick="closeModal('rekomModal')">Batal</button><button type="submit" name="save_rekom" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        event.currentTarget.classList.add('active');
        window.history.replaceState(null, null, '?tab=' + tabId);
    }

    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    function openAspekModal(data = null) {
        const m = document.getElementById('aspekModal');
        document.getElementById('id_aspek').value = data ? data.id_aspek : 0;
        document.getElementById('kode_aspek').value = data ? data.kode_aspek : '';
        document.getElementById('nama_aspek').value = data ? data.nama_aspek : '';
        document.getElementById('aspekModalTitle').innerText = data ? 'Edit Aspek' : 'Tambah Aspek';
        m.classList.add('show');
    }

    function openIndikatorModal(aspekId, data = null) {
        const m = document.getElementById('indModal');
        document.getElementById('ind_id_aspek').value = aspekId;
        document.getElementById('id_indikator').value = data ? data.id_indikator : 0;
        document.getElementById('pertanyaan').value = data ? data.pertanyaan : '';
        document.getElementById('indModalTitle').innerText = data ? 'Edit Pertanyaan' : 'Tambah Pertanyaan';
        m.classList.add('show');
    }

    function openRuleModal(data = null) {
        const m = document.getElementById('ruleModal');
        document.getElementById('id_rule').value = data ? data.id_rule : 0;
        document.getElementById('kode_rule').value = data ? data.kode_rule : '';
        document.getElementById('hasil_rule').value = data ? data.hasil : '';
        
        // Parse kondisi string (A1 >= 3) balik ke dropdowns
        if (data && data.kondisi) {
            const parts = data.kondisi.split(' ');
            if (parts.length >= 3) {
                document.getElementById('rule_aspek_kode').value = parts[0];
                document.getElementById('rule_operator').value = parts[1];
                document.getElementById('rule_nilai').value = parts[2];
            }
        } else {
            document.getElementById('rule_nilai').value = 3;
        }

        if (data && data.kode_rule && data.kode_rule.match(/^R(1[6-9]|20)$/)) {
            // Jika ini rule kategori, beri peringatan atau arahkan ke tab sebelah
            if(!confirm('Aturan ini (R16-R20) lebih baik dikelola di tab "Kategori & Saran" agar sinkron dengan ambang skor. Tetap lanjut edit di sini?')) {
                switchTab('results');
                return;
            }
        }

        document.getElementById('ruleModalTitle').innerText = data ? 'Edit Aturan' : 'Tambah Aturan';
        m.classList.add('show');
    }

    function openKatModal(data) {
        const m = document.getElementById('katModal');
        document.getElementById('id_kategori').value = data.id_kategori;
        document.getElementById('kat_nama').value = data.kategori;
        document.getElementById('kat_min').value = data.min_skor;
        document.getElementById('kat_max').value = data.max_skor;
        m.classList.add('show');
    }

    function openRekomModal(data) {
        const m = document.getElementById('rekomModal');
        document.getElementById('id_rekomendasi').value = data.id_rekomendasi;
        document.getElementById('rekom_kat').value = data.kategori;
        document.getElementById('rekom_isi').value = data.isi;
        m.classList.add('show');
    }
</script>

<?php include 'footer.php'; ?>
