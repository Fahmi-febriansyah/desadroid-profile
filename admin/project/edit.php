<?php
$page_title = 'Edit Proyek';
require_once '../../config/db.php';
require_once '../../config/upload.php';
require_once '../includes/header.php';

$error = '';
$success = '';
$project = null;

// Get project ID
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ./list.php');
    exit;
}

try {
    // Get project data
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    $project = $stmt->fetch();

    if (!$project) {
        header('Location: ./list.php');
        exit;
    }

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $code_link = trim($_POST['code_link'] ?? '');
        $progress = intval($_POST['progress'] ?? 0);
        $order_num = intval($_POST['order_num'] ?? 0);
        $image_url = $project['image_url'];

        if (empty($title) || empty($category)) {
            $error = 'Judul dan kategori harus diisi!';
        } else {
            try {
                        // Handle image upload
                if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    // Delete old image
                    if ($project['image_url']) {
                        deleteImage(basename($project['image_url']));
                    }
                    
                            $upload = uploadImage($_FILES['image'], 'uploads/projects');
                    if ($upload['success']) {
                        $image_url = $upload['url'];
                    } else {
                        $error = $upload['message'];
                    }
                }

                // Handle image delete
                if (isset($_POST['delete_image']) && $project['image_url']) {
                    deleteImage(basename($project['image_url']));
                    $image_url = '';
                }

                // Handle gallery deletions (array of image ids)
                if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                    foreach ($_POST['delete_images'] as $delId) {
                        $delId = intval($delId);
                        if ($delId) {
                            $r = $pdo->prepare('SELECT image_url FROM project_images WHERE id = ? AND project_id = ?');
                            $r->execute([$delId, $id]);
                            $row = $r->fetch();
                            if ($row && $row['image_url']) {
                                // attempt delete file
                                $filename = basename($row['image_url']);
                                deleteImage($filename, 'uploads/projects');
                            }
                            $pdo->prepare('DELETE FROM project_images WHERE id = ? AND project_id = ?')->execute([$delId, $id]);
                        }
                    }
                }

                // Handle new gallery uploads (respect max 5 images)
                if (isset($_FILES['gallery'])) {
                    // Count existing images
                    $cntStmt = $pdo->prepare('SELECT COUNT(*) as c FROM project_images WHERE project_id = ?');
                    $cntStmt->execute([$id]);
                    $existCount = intval($cntStmt->fetchColumn());
                    $allowed = max(0, 5 - $existCount);

                    $uploaded = 0;
                    for ($i=0;$i<count($_FILES['gallery']['name']);$i++) {
                        if ($uploaded >= $allowed) break;
                        if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK) continue;

                        $file = [
                            'name' => $_FILES['gallery']['name'][$i],
                            'type' => $_FILES['gallery']['type'][$i],
                            'tmp_name' => $_FILES['gallery']['tmp_name'][$i],
                            'error' => $_FILES['gallery']['error'][$i],
                            'size' => $_FILES['gallery']['size'][$i]
                        ];

                        $up = uploadImage($file, 'uploads/projects');
                        if ($up['success']) {
                            $pdo->prepare('INSERT INTO project_images (project_id, image_url) VALUES (?, ?)')
                                ->execute([$id, $up['url']]);
                            $uploaded++;
                        }
                    }
                }

                // Clear previous MOU association and set new one if provided
                try {
                    $pdo->prepare('UPDATE outgoing_letters SET project_id = NULL WHERE project_id = ?')->execute([$id]);
                    if (!empty($_POST['mou_id']) && is_numeric($_POST['mou_id'])) {
                        $mouId = intval($_POST['mou_id']);
                        $pdo->prepare('UPDATE outgoing_letters SET project_id = ? WHERE id = ?')->execute([$id, $mouId]);
                    }
                } catch (Exception $e) {
                    // ignore association errors but set message
                    $error = 'Warning: gagal meng-update asosiasi MOU: ' . $e->getMessage();
                }

                if (!$error) {
                    $stmt = $pdo->prepare('
                        UPDATE projects 
                        SET title = ?, category = ?, description = ?, 
                            image_url = ?, link = ?, code_link = ?, order_num = ?, progress = ?
                        WHERE id = ?
                    ');

                    $stmt->execute([
                        $title,
                        $category,
                        $description,
                        $image_url,
                        $link,
                        $code_link,
                        $order_num,
                        $progress,
                        $id
                    ]);

                    $success = 'Proyek berhasil diperbarui!';
                    
                    // Refresh project data
                    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
                    $stmt->execute([$id]);
                    $project = $stmt->fetch();
                }

            } catch(Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }

} catch(Exception $e) {
    $error = 'Error: ' . $e->getMessage();
}

$categories = ['Web Development', 'Mobile App', 'E-Commerce', 'Creative Tech'];
// fetch available outgoing letters
try {
    $mstmt = $pdo->query('SELECT id, letter_number, title, project_id FROM outgoing_letters ORDER BY created_at DESC');
    $outgoing_letters = $mstmt->fetchAll();
} catch (Exception $e) {
    $outgoing_letters = [];
}

// get current associated mou id (if any)
try {
    $cur = $pdo->prepare('SELECT id FROM outgoing_letters WHERE project_id = ? LIMIT 1');
    $cur->execute([$project['id']]);
    $current_mou = $cur->fetchColumn() ?: '';
} catch (Exception $e) {
    $current_mou = '';
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Edit Proyek</h2>
    <a href="./list.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($project): ?>
    <form method="POST" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);">
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Judul Proyek *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Kategori *</label>
                <select name="category" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box; cursor: pointer;">
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= $project['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Urutan</label>
                <input type="number" name="order_num" value="<?= $project['order_num'] ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;font-size:0.95rem;">Progress (%)</label>
            <input type="number" name="progress" min="0" max="100" value="<?= intval($project['progress'] ?? 0) ?>" style="width:120px;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
            <small style="display:block;color:#999;margin-top:6px">Masukkan persentase progres proyek (0-100)</small>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;font-size:0.95rem;">Associated MOU (opsional)</label>
            <select name="mou_id" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:4px">
                <option value="">-- Tidak ada MOU --</option>
                <?php foreach ($outgoing_letters as $ol): ?>
                    <option value="<?= $ol['id'] ?>" <?= $ol['id'] == $current_mou ? 'selected' : '' ?>><?= htmlspecialchars($ol['letter_number'] . ' — ' . $ol['title']) ?><?= $ol['project_id'] && $ol['project_id'] != $project['id'] ? ' (assigned)' : '' ?></option>
                <?php endforeach; ?>
            </select>
            <small style="color:#999;display:block;margin-top:6px">Buat surat baru di <a href="../outgoing_letters/create.php">Surat Keluar</a> lalu pilih di sini.</small>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Deskripsi</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; box-sizing: border-box;"><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Gambar Proyek</label>
            <?php if ($project['image_url']): ?>
                <div style="margin-bottom: 1rem;">
                    <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" style="max-width: 100%; max-height: 250px; border-radius: 4px; margin-bottom: 0.5rem; display: block;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="delete_image"> Hapus gambar ini
                    </label>
                </div>
            <?php endif; ?>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Upload Gambar Baru</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
            <small style="color: #999;">Format: JPG, PNG, WEBP, GIF | Max: 5MB</small>
            <div id="preview" style="margin-top: 1rem;"></div>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;font-size:0.95rem;">Gallery (max 5 gambar)</label>

            <?php
            // fetch existing gallery
            $gstmt = $pdo->prepare('SELECT * FROM project_images WHERE project_id = ? ORDER BY id ASC');
            $gstmt->execute([$project['id']]);
            $gallery = $gstmt->fetchAll();
            ?>

            <?php if (!empty($gallery)): ?>
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                    <?php foreach ($gallery as $g): ?>
                        <div style="width:120px;border:1px solid #eee;padding:6px;border-radius:6px;text-align:center;">
                            <img src="<?= htmlspecialchars($g['image_url']) ?>" style="width:100%;height:70px;object-fit:cover;border-radius:4px;margin-bottom:6px;">
                            <div style="font-size:12px;color:#666;margin-bottom:6px;">ID: <?= $g['id'] ?></div>
                            <label style="font-size:13px;display:block;">
                                <input type="checkbox" name="delete_images[]" value="<?= $g['id'] ?>"> Hapus
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="color:#999;margin-bottom:8px">Belum ada gambar gallery</div>
            <?php endif; ?>

            <input type="file" name="gallery[]" accept="image/*" multiple style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
            <small style="color:#999;display:block;margin-top:6px">Unggah gambar gallery tambahan (maks total 5 gambar).</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Link Proyek</label>
                <input type="url" name="link" placeholder="https://..." value="<?= htmlspecialchars($project['link'] ?? '') ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Link Code/Repository</label>
                <input type="url" name="code_link" placeholder="https://github.com/..." value="<?= htmlspecialchars($project['code_link'] ?? '') ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f5f6fa; border-radius: 4px;">
            <small><strong>Info:</strong></small><br>
            <small>Dibuat: <?= date('d M Y H:i', strtotime($project['created_at'])) ?></small>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="./list.php" class="btn btn-secondary" style="text-align: center;">Batal</a>
        </div>
    </form>
<?php endif; ?>

<style>
@media (max-width: 768px) {
    form {
        padding: 1rem !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    
    label {
        font-size: 0.9rem !important;
    }
    
    input[type="text"],
    input[type="number"],
    input[type="url"],
    input[type="file"],
    textarea,
    select {
        font-size: 16px !important;
        padding: 0.75rem !important;
        min-height: 44px !important;
        box-sizing: border-box !important;
    }
    
    textarea {
        min-height: 100px !important;
    }
    
    .btn {
        min-height: 44px !important;
        min-width: 100% !important;
        font-size: 1rem !important;
        padding: 0.75rem 1rem !important;
    }
    
    div[style*="display: flex; gap: 1rem"] {
        flex-direction: column !important;
    }
    
    img {
        max-width: 100% !important;
    }
}

@media (max-width: 480px) {
    form {
        padding: 0.75rem !important;
        border-radius: 0 !important;
    }
    
    input[type="text"],
    input[type="number"],
    input[type="url"],
    input[type="file"],
    textarea,
    select {
        font-size: 16px !important;
        padding: 10px !important;
        min-height: 44px !important;
    }
    
    .page-header {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .page-header .btn {
        width: 100% !important;
    }
    
    img {
        max-width: 100% !important;
        max-height: 200px !important;
    }
    
    #preview img {
        max-width: 100% !important;
        max-height: 200px !important;
    }
}
</style>

<script>
document.querySelector('input[name="image"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.innerHTML = '<img src="' + event.target.result + '" style="max-width: 100%; max-height: 200px; border-radius: 4px; margin-top: 1rem;">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
