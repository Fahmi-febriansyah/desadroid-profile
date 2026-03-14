<?php
$page_title = 'Edit Klien';
require_once '../../config/db.php';
require_once '../includes/header.php';

$error = '';
$success = '';
$testimonial = null;

// Get testimonial ID
$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ./list.php');
    exit;
}

try {
    // Get testimonial data
    $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id = ?');
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch();

    if (!$testimonial) {
        header('Location: ./list.php');
        exit;
    }

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $client_name = trim($_POST['client_name'] ?? '');
        $company = trim($_POST['company'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $rating = intval($_POST['rating'] ?? 5);
        $image_url = $testimonial['image_url'] ?? '';

        if (empty($client_name)) {
            $error = 'Nama klien harus diisi!';
        } else {
            try {
                // Handle optional image upload
                if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    $upload = uploadImage($_FILES['image'], 'uploads/testimonials');
                    if ($upload['success']) {
                        $image_url = $upload['url'];
                    } else {
                        $error = $upload['message'];
                    }
                }

                $stmt = $pdo->prepare('
                    UPDATE testimonials 
                    SET client_name = ?, company = ?, message = ?, rating = ?, image_url = ?
                    WHERE id = ?
                ');

                $stmt->execute([
                    $client_name,
                    $company,
                    $message,
                    $rating,
                    $image_url,
                    $id
                ]);

                $success = 'Klien berhasil diperbarui!';
                
                // Refresh testimonial data
                $stmt = $pdo->prepare('SELECT * FROM testimonials WHERE id = ?');
                $stmt->execute([$id]);
                $testimonial = $stmt->fetch();
            } catch(Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }

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
    <h2>Edit Klien</h2>
    <a href="./list.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($testimonial): ?>
    <form method="POST" enctype="multipart/form-data" style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);">
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Nama Klien *</label>
            <input type="text" name="client_name" value="<?= htmlspecialchars($testimonial['client_name']) ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
        </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Perusahaan</label>
                <input type="text" name="company" value="<?= htmlspecialchars($testimonial['company'] ?? '') ?>" placeholder="Contoh: PT Contoh" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Rating (1-5)</label>
                <select name="rating" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; box-sizing: border-box; cursor: pointer;">
                    <option value="5" <?= ($testimonial['rating'] ?? 5) == 5 ? 'selected' : '' ?>>5 ★★★★★ (Excellent)</option>
                    <option value="4" <?= ($testimonial['rating'] ?? 5) == 4 ? 'selected' : '' ?>>4 ★★★★ (Very Good)</option>
                    <option value="3" <?= ($testimonial['rating'] ?? 5) == 3 ? 'selected' : '' ?>>3 ★★★ (Good)</option>
                    <option value="2" <?= ($testimonial['rating'] ?? 5) == 2 ? 'selected' : '' ?>>2 ★★ (Fair)</option>
                    <option value="1" <?= ($testimonial['rating'] ?? 5) == 1 ? 'selected' : '' ?>>1 ★ (Poor)</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Pesan Testimonial</label>
            <textarea name="message" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; box-sizing: border-box;" placeholder="Masukkan pesan dari klien..."><?= htmlspecialchars($testimonial['message'] ?? '') ?></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Foto/Klien (opsional)</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
            <?php if (!empty($testimonial['image_url'])): ?>
                <div style="margin-top:0.75rem"><img src="<?= htmlspecialchars($testimonial['image_url']) ?>" style="max-width:140px;border-radius:6px;" alt="client"></div>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem;">Status</label>
            <div style="display: flex; gap: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="radio" name="status" value="inactive" <?= ($testimonial['status'] ?? 'inactive') === 'inactive' ? 'checked' : '' ?>> Tidak Aktif
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="radio" name="status" value="active" <?= ($testimonial['status'] ?? 'inactive') === 'active' ? 'checked' : '' ?>> Aktif (Tampil di Homepage)
                </label>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f5f6fa; border-radius: 4px;">
            <small><strong>Info:</strong></small><br>
            <small>Dibuat: <?= date('d M Y H:i', strtotime($testimonial['created_at'])) ?></small>
        </div>

        <div style="display: flex; gap: 1rem;">
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
}
</style>

<?php require_once '../includes/footer.php'; ?>
