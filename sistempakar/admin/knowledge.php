<?php
require_once '../config/db.php';

// --- HANDLER CRUD ---
// 1. Hapus Data
if (isset($_GET['hapus_kategori'])) {
    $id = intval($_GET['hapus_kategori']);
    $cek = $conn->query("SELECT id_rule FROM rule_cf WHERE id_kategori = $id LIMIT 1")->fetch();
    if ($cek) { echo "<script>alert('Gagal! Kategori ini sedang digunakan di Aturan CF.'); window.location='knowledge.php?tab=kategori';</script>"; exit; }
    $conn->query("DELETE FROM kategori_mesin WHERE id_kategori = $id");
    header("Location: knowledge.php?tab=kategori"); exit;
}
if (isset($_GET['hapus_gejala'])) {
    $id = intval($_GET['hapus_gejala']);
    $cek = $conn->query("SELECT id_rule FROM rule_cf WHERE id_gejala = $id LIMIT 1")->fetch();
    if ($cek) { echo "<script>alert('Gagal! Gejala ini sedang digunakan di Aturan CF.'); window.location='knowledge.php?tab=gejala';</script>"; exit; }
    $conn->query("DELETE FROM gejala WHERE id_gejala = $id");
    header("Location: knowledge.php?tab=gejala"); exit;
}
if (isset($_GET['hapus_kerusakan'])) {
    $id = intval($_GET['hapus_kerusakan']);
    $cek = $conn->query("SELECT id_rule FROM rule_cf WHERE id_kerusakan = $id LIMIT 1")->fetch();
    if ($cek) { echo "<script>alert('Gagal! Kerusakan ini sedang digunakan di Aturan CF.'); window.location='knowledge.php?tab=kerusakan';</script>"; exit; }
    $conn->query("DELETE FROM kerusakan WHERE id_kerusakan = $id");
    header("Location: knowledge.php?tab=kerusakan"); exit;
}
if (isset($_GET['hapus_rule'])) {
    $id = intval($_GET['hapus_rule']);
    $conn->query("DELETE FROM rule_cf WHERE id_rule = $id");
    header("Location: knowledge.php?tab=rule"); exit;
}

// 2. Simpan Kategori
if (isset($_POST['simpan_kategori'])) {
    $id = $_POST['id_kategori'];
    $nama = htmlspecialchars($_POST['nama_kategori']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    if(empty($id)) {
        $stmt = $conn->prepare("INSERT INTO kategori_mesin (nama_kategori, deskripsi) VALUES (?, ?)");
        $stmt->execute([$nama, $deskripsi]);
    } else {
        $stmt = $conn->prepare("UPDATE kategori_mesin SET nama_kategori=?, deskripsi=? WHERE id_kategori=?");
        $stmt->execute([$nama, $deskripsi, $id]);
    }
    header("Location: knowledge.php?tab=kategori"); exit;
}

// 3. Simpan Gejala
if (isset($_POST['simpan_gejala'])) {
    $id = $_POST['id_gejala'];
    $kode = htmlspecialchars($_POST['kode_gejala']);
    $nama = htmlspecialchars($_POST['nama_gejala']);
    if(empty($id)) {
        $stmt = $conn->prepare("INSERT INTO gejala (kode_gejala, nama_gejala) VALUES (?, ?)");
        $stmt->execute([$kode, $nama]);
    } else {
        $stmt = $conn->prepare("UPDATE gejala SET kode_gejala=?, nama_gejala=? WHERE id_gejala=?");
        $stmt->execute([$kode, $nama, $id]);
    }
    header("Location: knowledge.php?tab=gejala"); exit;
}

// 4. Simpan Kerusakan
if (isset($_POST['simpan_kerusakan'])) {
    $id = $_POST['id_kerusakan'];
    $kode = htmlspecialchars($_POST['kode_kerusakan']);
    $nama = htmlspecialchars($_POST['nama_kerusakan']);
    $solusi = htmlspecialchars($_POST['solusi']);
    if(empty($id)) {
        $stmt = $conn->prepare("INSERT INTO kerusakan (kode_kerusakan, nama_kerusakan, solusi) VALUES (?, ?, ?)");
        $stmt->execute([$kode, $nama, $solusi]);
    } else {
        $stmt = $conn->prepare("UPDATE kerusakan SET kode_kerusakan=?, nama_kerusakan=?, solusi=? WHERE id_kerusakan=?");
        $stmt->execute([$kode, $nama, $solusi, $id]);
    }
    header("Location: knowledge.php?tab=kerusakan"); exit;
}

// 5. Simpan Rule
if (isset($_POST['simpan_rule'])) {
    $id = $_POST['id_rule'];
    $id_kat = $_POST['id_kategori'];
    $id_gej = $_POST['id_gejala'];
    $id_ker = $_POST['id_kerusakan'];
    $cf = $_POST['cf_pakar'];
    
    // Get Codes
    $kg = $conn->query("SELECT kode_gejala FROM gejala WHERE id_gejala=$id_gej")->fetchColumn();
    $kk = $conn->query("SELECT kode_kerusakan FROM kerusakan WHERE id_kerusakan=$id_ker")->fetchColumn();

    if(empty($id)) {
        $stmt = $conn->prepare("INSERT INTO rule_cf (id_kategori, id_gejala, kode_gejala, id_kerusakan, kode_kerusakan, cf_pakar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_kat, $id_gej, $kg, $id_ker, $kk, $cf]);
    } else {
        $stmt = $conn->prepare("UPDATE rule_cf SET id_kategori=?, id_gejala=?, kode_gejala=?, id_kerusakan=?, kode_kerusakan=?, cf_pakar=? WHERE id_rule=?");
        $stmt->execute([$id_kat, $id_gej, $kg, $id_ker, $kk, $cf, $id]);
    }
    header("Location: knowledge.php?tab=rule"); exit;
}

// Fetch all data
$kategori = $conn->query("SELECT * FROM kategori_mesin")->fetchAll(PDO::FETCH_ASSOC);
$gejala = $conn->query("SELECT * FROM gejala")->fetchAll(PDO::FETCH_ASSOC);
$kerusakan = $conn->query("SELECT * FROM kerusakan")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("
    SELECT r.*, km.nama_kategori, g.nama_gejala, k.nama_kerusakan 
    FROM rule_cf r
    JOIN kategori_mesin km ON r.id_kategori = km.id_kategori
    JOIN gejala g ON r.id_gejala = g.id_gejala
    JOIN kerusakan k ON r.id_kerusakan = k.id_kerusakan
    ORDER BY r.id_kategori, r.id_rule ASC
");
$rules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---- AUTO GENERATE KODE ----
// Gejala
$last_g = $conn->query("SELECT kode_gejala FROM gejala ORDER BY id_gejala DESC LIMIT 1")->fetchColumn();
if ($last_g) {
    $num = intval(preg_replace('/[^0-9]/', '', $last_g));
    $next_gejala = 'G' . str_pad($num + 1, 2, '0', STR_PAD_LEFT);
} else {
    $next_gejala = 'G01';
}

// Kerusakan
$last_k = $conn->query("SELECT kode_kerusakan FROM kerusakan ORDER BY id_kerusakan DESC LIMIT 1")->fetchColumn();
if ($last_k) {
    $num = intval(preg_replace('/[^0-9]/', '', $last_k));
    $next_kerusakan = 'K' . str_pad($num + 1, 2, '0', STR_PAD_LEFT);
} else {
    $next_kerusakan = 'K01';
}

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'kategori';

include 'header.php';
?>

<div class="print-hide" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
    <div>
        <h2 style="margin: 0 0 5px 0;">Basis Pengetahuan (Knowledge Base)</h2>
        <p style="color: var(--text-muted); margin: 0;">Kontrol penuh data pakar (Kategori, Gejala, Kerusakan, Aturan CF).</p>
    </div>
    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Seluruh Data Pakar</button>
</div>

<div class="print-only" style="display: none; text-align: center; margin-bottom: 20px;">
    <h2>Dokumen Basis Pengetahuan Sistem Pakar - DPM Garage</h2>
    <p>Tanggal Cetak: <?= date('d M Y') ?></p>
</div>

<!-- Tabs -->
<div class="print-hide" style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid var(--border); padding-bottom: 10px;">
    <button class="btn <?= $activeTab=='kategori'?'btn-primary':'btn-outline' ?>" onclick="showTab('tab-kategori')" id="btn-kategori">Kategori Mesin</button>
    <button class="btn <?= $activeTab=='gejala'?'btn-primary':'btn-outline' ?>" onclick="showTab('tab-gejala')" id="btn-gejala">Gejala</button>
    <button class="btn <?= $activeTab=='kerusakan'?'btn-primary':'btn-outline' ?>" onclick="showTab('tab-kerusakan')" id="btn-kerusakan">Kerusakan & Solusi</button>
    <button class="btn <?= $activeTab=='rule'?'btn-primary':'btn-outline' ?>" onclick="showTab('tab-rule')" id="btn-rule">Aturan CF (Rules)</button>
</div>

<!-- Kategori -->
<div id="tab-kategori" class="glass-card tab-content" style="<?= $activeTab=='kategori'?'display:block;':'display:none;' ?> padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; align-items: center;">
        <h3 style="margin: 0;">Data Kategori Mesin</h3>
        <div style="display: flex; gap: 10px;" class="print-hide">
            <input type="text" id="sKategori" class="form-control" placeholder="Cari..." style="width: 200px; padding: 6px 12px;" onkeyup="filterTable('sKategori', 'tKategori')">
            <button class="btn btn-secondary btn-sm" onclick="formKategori()"><i class="fas fa-plus"></i> Tambah</button>
        </div>
    </div>
    <table id="tKategori" class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">ID</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Nama Kategori</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Deskripsi</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($kategori as $k): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);"><?= $k['id_kategori'] ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 600; color: var(--primary);"><?= htmlspecialchars($k['nama_kategori']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px;"><?= htmlspecialchars($k['deskripsi']) ?></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='formKategori(<?= json_encode($k, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                    <a href="?hapus_kategori=<?= $k['id_kategori'] ?>" onclick="return confirm('Hapus kategori?')" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: #dc3545; border-color: #dc3545;"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Gejala -->
<div id="tab-gejala" class="glass-card tab-content" style="<?= $activeTab=='gejala'?'display:block;':'display:none;' ?> padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; align-items: center;">
        <h3 style="margin: 0;">Data Gejala</h3>
        <div style="display: flex; gap: 10px;" class="print-hide">
            <input type="text" id="sGejala" class="form-control" placeholder="Cari gejala..." style="width: 200px; padding: 6px 12px;" onkeyup="filterTable('sGejala', 'tGejala')">
            <button class="btn btn-secondary btn-sm" onclick="formGejala()"><i class="fas fa-plus"></i> Tambah</button>
        </div>
    </div>
    <table id="tGejala" class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Kode</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Nama Gejala</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($gejala as $g): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 600;">[<?= $g['kode_gejala'] ?>]</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border);"><?= htmlspecialchars($g['nama_gejala']) ?></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='formGejala(<?= json_encode($g, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                    <a href="?hapus_gejala=<?= $g['id_gejala'] ?>" onclick="return confirm('Hapus gejala?')" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: #dc3545; border-color: #dc3545;"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Kerusakan -->
<div id="tab-kerusakan" class="glass-card tab-content" style="<?= $activeTab=='kerusakan'?'display:block;':'display:none;' ?> padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; align-items: center;">
        <h3 style="margin: 0;">Data Kerusakan & Solusi</h3>
        <div style="display: flex; gap: 10px;" class="print-hide">
            <input type="text" id="sKerusakan" class="form-control" placeholder="Cari kerusakan..." style="width: 200px; padding: 6px 12px;" onkeyup="filterTable('sKerusakan', 'tKerusakan')">
            <button class="btn btn-secondary btn-sm" onclick="formKerusakan()"><i class="fas fa-plus"></i> Tambah</button>
        </div>
    </div>
    <table id="tKerusakan" class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Kode</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Nama Kerusakan</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Solusi & Tindakan</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($kerusakan as $k): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-weight: 600;">[<?= $k['kode_kerusakan'] ?>]</td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); color: var(--primary); font-weight: 600;"><?= htmlspecialchars($k['nama_kerusakan']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px;"><?= nl2br(htmlspecialchars($k['solusi'])) ?></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='formKerusakan(<?= json_encode($k, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                    <a href="?hapus_kerusakan=<?= $k['id_kerusakan'] ?>" onclick="return confirm('Hapus kerusakan?')" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: #dc3545; border-color: #dc3545;"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Rule CF -->
<div id="tab-rule" class="glass-card tab-content" style="<?= $activeTab=='rule'?'display:block;':'display:none;' ?> padding: 24px; border-radius: var(--radius-sm); border: 1px solid var(--border);">
    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; align-items: center;">
        <h3 style="margin: 0;">Aturan Pakar (Rule CF)</h3>
        <div style="display: flex; gap: 10px;" class="print-hide">
            <input type="text" id="sRule" class="form-control" placeholder="Cari rule..." style="width: 200px; padding: 6px 12px;" onkeyup="filterTable('sRule', 'tRule')">
            <button class="btn btn-secondary btn-sm" onclick="formRule()"><i class="fas fa-plus"></i> Tambah Rule</button>
        </div>
    </div>
    <table id="tRule" class="data-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--surface);">
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Kategori Mesin</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Kerusakan (IF)</th>
                <th style="padding: 12px; text-align: left; border-bottom: 1px solid var(--border);">Gejala (THEN)</th>
                <th style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">MB / MD Pakar</th>
                <th class="print-hide" style="padding: 12px; text-align: center; border-bottom: 1px solid var(--border);">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rules as $r): ?>
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px; font-weight: 500; color: var(--text-muted);"><?= htmlspecialchars($r['nama_kategori']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px;">[<?= $r['kode_kerusakan'] ?>] <?= htmlspecialchars($r['nama_kerusakan']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px;">[<?= $r['kode_gejala'] ?>] <?= htmlspecialchars($r['nama_gejala']) ?></td>
                <td style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;"><span class="badge badge-primary"><?= $r['cf_pakar'] ?></span></td>
                <td class="print-hide" style="padding: 12px; border-bottom: 1px solid var(--border); text-align: center;">
                    <button onclick='formRule(<?= json_encode($r, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)' class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;"><i class="fas fa-edit"></i></button>
                    <a href="?hapus_rule=<?= $r['id_rule'] ?>" onclick="return confirm('Hapus rule?')" class="btn btn-outline" style="padding: 4px 8px; font-size: 12px; color: #dc3545; border-color: #dc3545;"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ================= MODALS ================= -->
<!-- Kategori -->
<div id="mKat" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <span class="modal-close" onclick="closeModal('mKat')">&times;</span>
        <h3 id="mKatTitle" style="margin-bottom: 20px;">Tambah Kategori</h3>
        <form method="POST">
            <input type="hidden" name="simpan_kategori" value="1">
            <input type="hidden" name="id_kategori" id="fk_id">
            <div class="form-group"><label>Nama Kategori</label><input type="text" name="nama_kategori" id="fk_nama" class="form-control" required></div>
            <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" id="fk_desc" class="form-control"></textarea></div>
            <div style="text-align:right;"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Gejala -->
<div id="mGej" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <span class="modal-close" onclick="closeModal('mGej')">&times;</span>
        <h3 id="mGejTitle" style="margin-bottom: 20px;">Tambah Gejala</h3>
        <form method="POST">
            <input type="hidden" name="simpan_gejala" value="1">
            <input type="hidden" name="id_gejala" id="fg_id">
            <div class="form-group"><label>Kode Gejala (G01)</label><input type="text" name="kode_gejala" id="fg_kode" class="form-control" required></div>
            <div class="form-group"><label>Nama Gejala</label><input type="text" name="nama_gejala" id="fg_nama" class="form-control" required></div>
            <div style="text-align:right;"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Kerusakan -->
<div id="mKer" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <span class="modal-close" onclick="closeModal('mKer')">&times;</span>
        <h3 id="mKerTitle" style="margin-bottom: 20px;">Tambah Kerusakan</h3>
        <form method="POST">
            <input type="hidden" name="simpan_kerusakan" value="1">
            <input type="hidden" name="id_kerusakan" id="fkr_id">
            <div class="form-group"><label>Kode Kerusakan (K01)</label><input type="text" name="kode_kerusakan" id="fkr_kode" class="form-control" required></div>
            <div class="form-group"><label>Nama Kerusakan</label><input type="text" name="nama_kerusakan" id="fkr_nama" class="form-control" required></div>
            <div class="form-group"><label>Solusi</label><textarea name="solusi" id="fkr_sol" class="form-control" rows="4" required></textarea></div>
            <div style="text-align:right;"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<!-- Rule -->
<div id="mRule" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <span class="modal-close" onclick="closeModal('mRule')">&times;</span>
        <h3 id="mRuleTitle" style="margin-bottom: 20px;">Tambah Rule CF</h3>
        <form method="POST">
            <input type="hidden" name="simpan_rule" value="1">
            <input type="hidden" name="id_rule" id="fr_id">
            <div class="form-group"><label>Kategori Mesin</label>
                <select name="id_kategori" id="fr_kat" class="form-control" required>
                    <?php foreach($kategori as $kat): ?><option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Kerusakan (IF)</label>
                <select name="id_kerusakan" id="fr_ker" class="form-control" required>
                    <?php foreach($kerusakan as $ker): ?><option value="<?= $ker['id_kerusakan'] ?>">IF [<?= $ker['kode_kerusakan'] ?>] <?= $ker['nama_kerusakan'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Gejala (THEN)</label>
                <select name="id_gejala" id="fr_gej" class="form-control" required>
                    <?php foreach($gejala as $gej): ?><option value="<?= $gej['id_gejala'] ?>">THEN [<?= $gej['kode_gejala'] ?>] <?= $gej['nama_gejala'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label>Bobot Pakar (CF)</label>
                <input type="number" step="0.1" max="1" min="-1" name="cf_pakar" id="fr_cf" class="form-control" required placeholder="0.8">
            </div>
            <div style="text-align:right;"><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div>
</div>

<script>
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.print-hide .btn').forEach(el => {
        if(el.id.startsWith('btn-')) { el.classList.remove('btn-primary'); el.classList.add('btn-outline'); }
    });
    document.getElementById(tabId).style.display = 'block';
    document.getElementById('btn-' + tabId.split('-')[1]).classList.remove('btn-outline');
    document.getElementById('btn-' + tabId.split('-')[1]).classList.add('btn-primary');
}

window.onbeforeprint = function() { document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'block'); }
window.onafterprint = function() { showTab('tab-<?= $activeTab ?>'); }

function formKategori(data=null) {
    document.getElementById('mKatTitle').innerText = data ? "Edit Kategori" : "Tambah Kategori";
    document.getElementById('fk_id').value = data ? data.id_kategori : "";
    document.getElementById('fk_nama').value = data ? data.nama_kategori : "";
    document.getElementById('fk_desc').value = data ? data.deskripsi : "";
    openModal('mKat');
}
function formGejala(data=null) {
    document.getElementById('mGejTitle').innerText = data ? "Edit Gejala" : "Tambah Gejala";
    document.getElementById('fg_id').value = data ? data.id_gejala : "";
    document.getElementById('fg_kode').value = data ? data.kode_gejala : "<?= $next_gejala ?>";
    document.getElementById('fg_kode').readOnly = data ? true : false; // Kalau edit gak bisa diubah kodenya biar aman
    document.getElementById('fg_nama').value = data ? data.nama_gejala : "";
    openModal('mGej');
}
function formKerusakan(data=null) {
    document.getElementById('mKerTitle').innerText = data ? "Edit Kerusakan" : "Tambah Kerusakan";
    document.getElementById('fkr_id').value = data ? data.id_kerusakan : "";
    document.getElementById('fkr_kode').value = data ? data.kode_kerusakan : "<?= $next_kerusakan ?>";
    document.getElementById('fkr_kode').readOnly = data ? true : false; // Kalau edit gak bisa diubah kodenya biar aman
    document.getElementById('fkr_nama').value = data ? data.nama_kerusakan : "";
    document.getElementById('fkr_sol').value = data ? data.solusi : "";
    openModal('mKer');
}
function formRule(data=null) {
    document.getElementById('mRuleTitle').innerText = data ? "Edit Rule CF" : "Tambah Rule CF";
    document.getElementById('fr_id').value = data ? data.id_rule : "";
    document.getElementById('fr_kat').value = data ? data.id_kategori : "";
    document.getElementById('fr_ker').value = data ? data.id_kerusakan : "";
    document.getElementById('fr_gej').value = data ? data.id_gejala : "";
    document.getElementById('fr_cf').value = data ? data.cf_pakar : "";
    openModal('mRule');
}

function filterTable(inputId, tableId) {
    let filter = document.getElementById(inputId).value.toLowerCase();
    let rows = document.getElementById(tableId).getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        let text = rows[i].innerText.toLowerCase();
        rows[i].style.display = text.includes(filter) ? "" : "none";
    }
}
</script>

<style>
    @media print {
        .tab-content { display: block !important; margin-bottom: 30px; page-break-inside: avoid; }
    }
</style>

<?php include 'footer.php'; ?>
