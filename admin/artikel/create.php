<?php
$page_title = 'Buat Artikel Baru';
require_once '../../config/db.php';
require_once '../../config/upload.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    $read_time = intval($_POST['read_time'] ?? 0);
    $featured_image = '';

    if (empty($title) || empty($content)) {
        $error = 'Judul dan konten harus diisi!';
    } else {
        try {
            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['size'] > 0) {
                $upload = uploadImage($_FILES['featured_image'], 'uploads/articles');
                if ($upload['success']) {
                    $featured_image = $upload['url'];
                } else {
                    $error = $upload['message'];
                }
            }

            if (!$error) {
                // Generate slug from title
                $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $title), '-'));
                
                // Check if slug already exists
                $check = $pdo->prepare('SELECT id FROM articles WHERE slug = ?');
                $check->execute([$slug]);
                if ($check->fetch()) {
                    $slug .= '-' . time();
                }

                $stmt = $pdo->prepare('
                    INSERT INTO articles (title, slug, category, excerpt, content, featured_image, author_id, read_time, status, published_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ');

                $published_date = ($status === 'published') ? date('Y-m-d H:i:s') : NULL;
                
                $stmt->execute([
                    $title,
                    $slug,
                    $category,
                    $excerpt,
                    $content,
                    $featured_image,
                    $_SESSION['admin_id'],
                    $read_time,
                    $status,
                    $published_date
                ]);

                $success = 'Artikel berhasil dibuat!';
                // Redirect after 2 seconds
                header('Refresh: 2; url=./list.php');
            }

        } catch(Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

$categories = ['Web Development', 'Mobile Apps', 'UX/UI Design', 'Backend Development', 'E-Commerce'];
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Buat Artikel Baru</h2>
    <a href="./list.php" class="btn btn-secondary">Kembali</a>
</div>

<form method="POST" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);">
    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Judul Artikel *</label>
        <input type="text" name="title" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Kategori</label>
            <select name="category" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box; cursor: pointer;">
                <option value="">Pilih Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>"><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Estimasi Baca (menit)</label>
            <input type="number" name="read_time" value="5" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
        </div>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Gambar Featured</label>
        <input type="file" name="featured_image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
        <small style="color: #999;">Format: JPG, PNG, WEBP, GIF | Max: 5MB</small>
        <div id="preview" style="margin-top: 1rem;"></div>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Ringkasan (Excerpt)</label>
        <textarea name="excerpt" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit;"></textarea>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Konten Artikel *</label>
        <textarea name="content" required rows="12" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: 'Courier New', monospace;"></textarea>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Status</label>
        <div style="display: flex; gap: 1rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="radio" name="status" value="draft" checked> Draft
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="radio" name="status" value="published"> Publish Sekarang
            </label>
        </div>
    </div>

    <div style="display: flex; gap: 1rem;">
        <button type="submit" class="btn btn-primary">Simpan Artikel</button>
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
    input[type="file"],
    textarea,
    select {
        font-size: 16px !important;
        padding: 0.75rem !important;
        min-height: 44px !important;
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

<script src="https://cdn.tiny.cloud/1/an81at2fwzw7pk20qfbfqkseev026w2p450knfid3d53yz1p/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: 'textarea[name="content"]',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    height: 500,
    menubar: true,
    branding: false,
    promotion: false,
    image_advtab: true,
    image_caption: true,
    images_upload_url: 'upload_handler.php',
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});

document.querySelector('input[name="featured_image"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.innerHTML = '<img src="' + event.target.result + '" style="max-width: 100%; max-height: 250px; border-radius: 4px; margin-top: 1rem;">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
