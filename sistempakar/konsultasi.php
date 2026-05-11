<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_auth'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'batal') {
    $stmt = $conn->prepare("UPDATE user SET draft_merk_mobil = NULL, draft_id_kategori = NULL WHERE id_user = ?");
    $stmt->execute([$_SESSION['user_auth']['id_user']]);
    unset($_SESSION['active_konsultasi']);
    header("Location: pilih_mobil.php");
    exit;
}

if (!isset($_SESSION['active_konsultasi'])) {
    header("Location: pilih_mobil.php");
    exit;
}

$id_kat = $_SESSION['active_konsultasi']['id_kategori'];

// Hanya ambil gejala yang ada di rule_cf untuk kategori mesin ini
$stmt = $conn->prepare("
    SELECT DISTINCT g.* 
    FROM gejala g
    JOIN rule_cf r ON g.id_gejala = r.id_gejala
    WHERE r.id_kategori = ?
    ORDER BY g.id_gejala ASC
");
$stmt->execute([$id_kat]);
$gejala_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php'; 
?>

<div class="container" style="padding: 50px 20px 80px; min-height: 80vh;">
    <div class="page-header" style="margin-bottom: 30px;">
        <h1>Pilih Gejala Kendaraan</h1>
        <p>Centang gejala yang dialami lalu tentukan tingkat keyakinan Anda.</p>
    </div>

    <!-- Info Kendaraan Aktif -->
    <div style="max-width: 780px; margin: 0 auto 30px;">
        <div style="background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; box-shadow: var(--shadow-sm);">
            <div>
                <span style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Kendaraan & Teknologi Mesin:</span>
                <span style="font-size: 18px; font-weight: 700; color: var(--primary);"><i class="fas fa-car" style="margin-right: 8px;"></i><?= htmlspecialchars($_SESSION['active_konsultasi']['merk_mobil']) ?></span>
                <span style="font-size: 14px; font-weight: 500; color: var(--text-muted); margin-left: 8px;">(<?= htmlspecialchars($_SESSION['active_konsultasi']['nama_kategori']) ?>)</span>
            </div>
            <a href="konsultasi.php?action=batal" class="btn btn-outline" style="padding: 8px 16px; font-size: 13px;" onclick="localStorage.removeItem('dpm_draft_gejala');">
                <i class="fas fa-times" style="margin-right: 5px;"></i> Ganti Mobil
            </a>
        </div>
    </div>

    <?php if(empty($gejala_list)): ?>
        <div class="glass-card" style="text-align: center; max-width: 600px; margin: 0 auto;">
            <i class="fas fa-exclamation-triangle" style="font-size: 50px; color: #f59e0b; margin-bottom: 20px;"></i>
            <h3>Basis Pengetahuan Belum Tersedia</h3>
            <p style="color: var(--text-muted); margin-top: 10px;">Mohon maaf, pakar kami belum memasukkan data aturan diagnosa (Rule CF) untuk kategori mesin <strong><?= htmlspecialchars($_SESSION['active_konsultasi']['nama_kategori']) ?></strong>.</p>
            <a href="konsultasi.php?action=batal" class="btn btn-primary" style="margin-top: 20px;">Pilih Kategori Lain</a>
        </div>
    <?php else: ?>
        <form action="proses_diagnosa.php" method="POST" style="max-width: 780px; margin: 0 auto;">
            <div class="glass-card" style="padding: 28px;">
                <?php foreach($gejala_list as $g): ?>
                <div class="symptom-item">
                    <label class="symptom-header">
                        <div class="custom-checkbox">
                            <input type="checkbox" name="gejala[]" value="<?= $g['id_gejala'] ?>" onchange="toggleSelect(this, <?= $g['id_gejala'] ?>)">
                            <span class="checkmark"></span>
                        </div>
                        <div class="symptom-text">
                            <span style="color: var(--primary); font-size: 13px; font-weight: 600;">[<?= $g['kode_gejala'] ?>]</span> <?= $g['nama_gejala'] ?>
                        </div>
                    </label>
                    <div class="cf-select" id="select_<?= $g['id_gejala'] ?>">
                        <label class="form-label" style="font-size: 13px;">Tingkat Keyakinan:</label>
                        <select name="cf_user[<?= $g['id_gejala'] ?>]" class="form-control" style="max-width: 280px;" disabled>
                            <option value="1.0">Sangat Yakin</option>
                            <option value="0.8" selected>Yakin</option>
                            <option value="0.6">Cukup Yakin</option>
                            <option value="0.4">Sedikit Yakin</option>
                            <option value="0.2">Tidak Yakin</option>
                        </select>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div style="margin-top: 28px; text-align: center;">
                    <button type="submit" class="btn btn-primary" id="btn-submit" disabled style="padding: 14px 36px; font-size: 16px;">
                        <i class="fas fa-microchip"></i> Proses Diagnosa
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
    const STORAGE_KEY = 'dpm_draft_gejala';

    function toggleSelect(checkbox, id) {
        const selectDiv = document.getElementById('select_' + id);
        const select = selectDiv.querySelector('select');
        if(checkbox.checked) {
            selectDiv.classList.add('active');
            select.disabled = false;
        } else {
            selectDiv.classList.remove('active');
            select.disabled = true;
        }
        checkSubmitBtn();
        saveDraft();
    }

    function checkSubmitBtn() {
        const checkboxes = document.querySelectorAll('input[name="gejala[]"]:checked');
        document.getElementById('btn-submit').disabled = checkboxes.length === 0;
    }

    function saveDraft() {
        if (localStorage.getItem('dpm_cookie_consent') !== 'accepted') return;
        const draft = {};
        document.querySelectorAll('input[name="gejala[]"]').forEach(cb => {
            if (cb.checked) {
                const id = cb.value;
                const sel = document.querySelector('select[name="cf_user[' + id + ']"]');
                draft[id] = sel ? sel.value : '0.8';
            }
        });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(draft));
    }

    function restoreDraft() {
        if (localStorage.getItem('dpm_cookie_consent') !== 'accepted') return;
        const saved = localStorage.getItem(STORAGE_KEY);
        if (!saved) return;
        try {
            const draft = JSON.parse(saved);
            for (const [id, cfVal] of Object.entries(draft)) {
                const cb = document.querySelector('input[name="gejala[]"][value="' + id + '"]');
                if (cb) {
                    cb.checked = true;
                    const selectDiv = document.getElementById('select_' + id);
                    const select = selectDiv.querySelector('select');
                    selectDiv.classList.add('active');
                    select.disabled = false;
                    select.value = cfVal;
                }
            }
            checkSubmitBtn();
        } catch(e) {}
    }

    document.querySelectorAll('.cf-select select').forEach(sel => {
        sel.addEventListener('change', saveDraft);
    });

    document.querySelector('form').addEventListener('submit', function() {
        localStorage.removeItem(STORAGE_KEY);
    });

    restoreDraft();
</script>
