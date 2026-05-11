<?php
$page_title = 'Buat Proyek Baru';
require_once '../../config/db.php';
require_once '../../config/upload.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $code_link = trim($_POST['code_link'] ?? '');
    $order_num = intval($_POST['order_num'] ?? 0);
    $progress = intval($_POST['progress'] ?? 0);
    $image_url = '';

    if (empty($title) || empty($category)) {
        $error = 'Judul dan kategori harus diisi!';
    } else {
        try {
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                $upload = uploadImage($_FILES['image'], 'uploads/projects');
                if ($upload['success']) {
                    $image_url = $upload['url'];
                } else {
                    $error = $upload['message'];
                }
            }

                if (!$error) {
                    // Generate slug from title
                    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $title), '-'));
                    
                    // Check if slug already exists
                    $check = $pdo->prepare('SELECT id FROM projects WHERE slug = ?');
                    $check->execute([$slug]);
                    if ($check->fetch()) {
                        $slug .= '-' . time();
                    }

                    $stmt = $pdo->prepare('
                        INSERT INTO projects (title, slug, category, description, image_url, link, code_link, order_num, progress)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ');

                    $stmt->execute([
                        $title,
                        $slug,
                        $category,
                        $description,
                        $image_url,
                        $link,
                        $code_link,
                        $order_num,
                        $progress
                    ]);

                    $projectId = $pdo->lastInsertId();

                    // Handle gallery uploads (max 5 images)
                    if (isset($_FILES['gallery'])) {
                        $uploaded = 0;
                        for ($i=0;$i<count($_FILES['gallery']['name']);$i++) {
                            if ($uploaded >= 5) break;
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
                                    ->execute([$projectId, $up['url']]);
                                $uploaded++;
                            }
                        }
                    }

                    // Associate selected MOU (outgoing_letter) with this project
                    if (!empty($_POST['mou_id']) && is_numeric($_POST['mou_id'])) {
                        $mouId = intval($_POST['mou_id']);
                        $pdo->prepare('UPDATE outgoing_letters SET project_id = ? WHERE id = ?')->execute([$projectId, $mouId]);
                    }

                    $success = 'Proyek berhasil dibuat!';
                    header('Refresh: 2; url=./list.php');
                }

        } catch(Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

$categories = ['Web Development', 'Mobile App', 'E-Commerce', 'Creative Tech'];
// fetch outgoing letters for optional association
try {
    $mstmt = $pdo->query('SELECT id, letter_number, title FROM outgoing_letters ORDER BY created_at DESC');
    $outgoing_letters = $mstmt->fetchAll();
} catch (Exception $e) {
    $outgoing_letters = [];
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Buat Proyek Baru</h2>
    <a href="./list.php" class="btn btn-secondary">Kembali</a>
</div>

<form method="POST" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);">
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Judul Proyek *</label>
        <input type="text" name="title" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Kategori *</label>
            <select name="category" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box; cursor: pointer;">
                <option value="">Pilih Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>"><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Urutan</label>
            <input type="number" name="order_num" value="0" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
        </div>
    </div>

    <div style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;font-size:0.95rem;">Progress (%)</label>
        <input type="number" name="progress" min="0" max="100" value="0" style="width:120px;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
        <small style="display:block;color:#999;margin-top:6px">Masukkan persentase progres proyek (0-100)</small>
    </div>

    <div style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;font-size:0.95rem;">Associated MOU (opsional)</label>
        <select name="mou_id" style="width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:4px">
            <option value="">-- Pilih MOU yang terkait --</option>
            <?php foreach ($outgoing_letters as $ol): ?>
                <option value="<?= $ol['id'] ?>"><?= htmlspecialchars($ol['letter_number'] . ' — ' . $ol['title']) ?></option>
            <?php endforeach; ?>
        </select>
        <small style="color:#999;display:block;margin-top:6px">Pilih surat MOU yang sudah dibuat di <a href="../outgoing_letters/list.php">Surat Keluar</a>.</small>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Deskripsi</label>
        <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; box-sizing: border-box;"></textarea>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Upload Gambar</label>
        <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        <small style="color: #999;">Format: JPG, PNG, WEBP, GIF | Max: 5MB</small>
        <div id="preview" style="margin-top: 1rem;"></div>
    </div>

    <div style="margin-bottom:1.5rem;">
        <label style="display:block;margin-bottom:0.5rem;font-weight:600;color:#333;font-size:0.95rem;">Gallery (max 5 gambar)</label>
        <input type="file" name="gallery[]" accept="image/*" multiple style="width:100%;padding:0.5rem;border:1px solid #ddd;border-radius:4px;">
        <small style="color:#999;display:block;margin-top:6px">Pilih hingga 5 gambar untuk galeri proyek.</small>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Link Proyek</label>
            <input type="url" name="link" placeholder="https://..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
        </div>
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Link Code/Repository</label>
            <input type="url" name="code_link" placeholder="https://github.com/..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
        </div>
    </div>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <button type="submit" class="btn btn-primary">Simpan Proyek</button>
        <a href="./list.php" class="btn btn-secondary" style="text-align: center;">Batal</a>
    </div>
</form>

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
