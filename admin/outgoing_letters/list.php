<?php
$page_title = 'Surat Keluar';
require_once '../../config/db.php';
require_once '../includes/header.php';

$success = '';
$error = '';

// Handle delete (remove files from disk as well)
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delId = intval($_GET['delete']);
    try {
        // fetch existing record
        $s = $pdo->prepare('SELECT attachments, signature_file FROM outgoing_letters WHERE id = ? LIMIT 1');
        $s->execute([$delId]);
        $row = $s->fetch();

        // helper to resolve local path
        $resolve = function($url) {
            if (!$url) return false;
            if (strpos($url, 'file://') === 0) return substr($url, 7);
            $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
            $candidate = $docRoot . '/' . ltrim($url, '/');
            if (file_exists($candidate)) return $candidate;
            $proj = realpath(__DIR__ . '/../../');
            $candidate2 = $proj . '/' . ltrim($url, '/');
            if (file_exists($candidate2)) return $candidate2;
            return false;
        };

        if ($row) {
            if (!empty($row['attachments'])) {
                $p = $resolve($row['attachments']);
                if ($p && file_exists($p)) @unlink($p);
            }
            if (!empty($row['signature_file'])) {
                $p2 = $resolve($row['signature_file']);
                if ($p2 && file_exists($p2)) @unlink($p2);
            }
        }

        $stmt = $pdo->prepare('DELETE FROM outgoing_letters WHERE id = ?');
        $stmt->execute([$delId]);
        $success = 'Surat berhasil dihapus!';
    } catch(Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

try {
    $stmt = $pdo->query('SELECT o.*, u.full_name FROM outgoing_letters o LEFT JOIN admin_users u ON o.created_by = u.id ORDER BY o.created_at DESC');
    $letters = $stmt->fetchAll();
} catch(Exception $e) {
    $error = 'Error: ' . $e->getMessage();
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Surat Keluar</h2>
    <div style="display:flex;gap:0.5rem;align-items:center;">
        <a href="./create.php" class="btn btn-primary">+ Buat Surat Baru</a>
        <button id="btnSetMyTTD" class="btn btn-secondary">Set TTD Saya</button>
    </div>
</div>

<div style="background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); overflow: hidden;">
    <?php if (empty($letters)): ?>
        <div style="padding: 2rem; text-align: center; color: #999;">
            <p>Tidak ada surat. <a href="./create.php">Buat surat sekarang</a></p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nomor Surat</th>
                    <th>Judul</th>
                    <th>Recipient</th>
                    <th>Status</th>
                    <th>Dibuat Oleh</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($letters as $l): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($l['letter_number']) ?></strong></td>
                        <td><?= htmlspecialchars($l['title']) ?></td>
                        <td><?= htmlspecialchars($l['recipient'] ?? '-') ?></td>
                        <td><?= htmlspecialchars(ucfirst($l['status'])) ?></td>
                        <td><?= htmlspecialchars($l['full_name'] ?? '-') ?></td>
                        <td><?= date('d M Y', strtotime($l['created_at'])) ?></td>
                        <td>
                                <div class="action-btns">
                                    <a href="./edit.php?id=<?= $l['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?delete=<?= $l['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus surat ini?');">Hapus</a>
                                    <button class="btn btn-secondary btn-sm btn-ttd" data-id="<?= $l['id'] ?>" data-sign="<?= htmlspecialchars($l['signature_file'] ?? '') ?>">TTD</button>
                                    <a href="./preview.php?id=<?= $l['id'] ?>" target="_blank" class="btn btn-primary btn-sm">Preview</a>
                                </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>

<div id="ttdModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;padding:1rem;border-radius:8px;max-width:600px;width:100%;">
        <h3 style="margin-top:0">Upload / Ganti Tanda Tangan</h3>
        <div id="ttdPreviewArea" style="margin-bottom:0.5rem;color:#555"></div>
        <form id="ttdForm" enctype="multipart/form-data">
            <input type="file" name="signature" accept="image/*" required>
            <input type="hidden" name="id" id="ttdLetterId">
            <div style="margin-top:0.75rem;display:flex;gap:0.5rem;">
                <button type="submit" class="btn btn-primary">Unggah</button>
                <button type="button" id="ttdCancel" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Modal elements
    var modal = document.getElementById('ttdModal');
    var ttdForm = document.getElementById('ttdForm');
    var ttdPreview = document.getElementById('ttdPreviewArea');
    var ttdId = document.getElementById('ttdLetterId');

    document.querySelectorAll('.btn-ttd').forEach(function(btn){
        btn.addEventListener('click', function(){
            var id = this.dataset.id;
            var sign = this.dataset.sign;
            ttdId.value = id;
            if (sign) {
                ttdPreview.innerHTML = 'Current signature: <a href="'+sign+'" target="_blank">lihat</a>';
            } else {
                ttdPreview.textContent = '(Belum ada tanda tangan)';
            }
            modal.style.display = 'flex';
        });
    });

    document.getElementById('ttdCancel').addEventListener('click', function(){ modal.style.display = 'none'; });

    ttdForm.addEventListener('submit', function(e){
        e.preventDefault();
        var formData = new FormData(ttdForm);
        fetch('./upload_signature.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(json => {
                if (json.success) {
                    alert('Tanda tangan berhasil diunggah');
                    modal.style.display = 'none';
                    // update dataset for the button and preview link
                    var btn = document.querySelector('.btn-ttd[data-id="'+ttdId.value+'"]');
                    if (btn) btn.dataset.sign = json.url;
                } else {
                    alert('Error: ' + json.message);
                }
            }).catch(err => { alert('Upload gagal'); });
    });
});
</script>

<script>
// Set TTD Saya modal (global signature for current admin)
document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('btnSetMyTTD');
    if (!btn) return;
    var modal = document.createElement('div');
    modal.id = 'myTtdModal';
    modal.style = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;padding:1rem;';
    modal.innerHTML = '\n        <div style="background:#fff;padding:1rem;border-radius:8px;max-width:600px;width:100%;">\n            <h3 style="margin-top:0">Set Tanda Tangan Saya</h3>\n            <div id="myTtdPreview" style="margin-bottom:0.5rem;color:#555">Memuat...</div>\n            <form id="myTtdForm" enctype="multipart/form-data">\n                <input type="file" name="signature" accept="image/*" required>\n                <div style="margin-top:0.75rem;display:flex;gap:0.5rem;">\n                    <button type="submit" class="btn btn-primary">Unggah</button>\n                    <button type="button" id="myTtdCancel" class="btn btn-secondary">Batal</button>\n                </div>\n            </form>\n        </div>';
    document.body.appendChild(modal);

    // fetch current admin signature
    fetch('./get_my_signature.php').then(r=>r.json()).then(j=>{
        var el = document.getElementById('myTtdPreview');
        if (!el) return;
        if (j.success && j.url) el.innerHTML = 'Current: <a href="'+j.url+'" target="_blank">lihat</a>'; else el.textContent = '(Belum ada tanda tangan)';
    }).catch(()=>{});

    btn.addEventListener('click', function(){ modal.style.display = 'flex'; });
    document.getElementById('myTtdCancel').addEventListener('click', function(){ modal.style.display = 'none'; });

    document.getElementById('myTtdForm').addEventListener('submit', function(e){
        e.preventDefault();
        var fd = new FormData(this);
        fetch('./upload_my_signature.php', { method: 'POST', body: fd }).then(r=>r.json()).then(j=>{
            if (j.success) {
                alert('Tanda tangan tersimpan');
                modal.style.display='none';
            } else {
                var msg = j.message || 'Gagal mengunggah.';
                try { if (j.debug) msg += '\nDebug: ' + j.debug; } catch(e){}
                alert('Error: ' + msg);
            }
        }).catch(err=>{ alert('Upload gagal: ' + (err.message || 'network error')); });
    });
});
</script>
