<?php
$page_title = 'Buat Surat';
require_once '../../config/db.php';
require_once '../includes/header.php';
require_once '../../config/upload.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $letter_number = trim($_POST['letter_number'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $recipient = trim($_POST['recipient'] ?? '');
    $created_by = $_SESSION['admin_id'] ?? null;

    // Handle attachment upload (single file)
    $attachment_url = null;
    if (!$error && !empty($_FILES['attachment']) && $_FILES['attachment']['size'] > 0) {
        $res2 = uploadImage($_FILES['attachment'], 'uploads/letters');
        if ($res2['success']) {
            $attachment_url = $res2['url'];
            $attachment_filename = $res2['filename'];
        } else {
            $error = $res2['message'];
        }
    }

    if (!$error) {
        if ($letter_number === '') {
            $letter_number = 'SK-' . date('YmdHis');
        }

        try {
            $stmt = $pdo->prepare('INSERT INTO outgoing_letters (letter_number, title, content, recipient, attachments, created_by, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$letter_number, $title, $content, $recipient, $attachment_url, $created_by, 'draft']);
            $success = 'Surat berhasil dibuat.';
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Buat Surat Keluar</h2>
    <a href="./list.php" class="btn btn-secondary">← Kembali</a>
</div>

<div style="background:white;padding:1.5rem;border-radius:6px;">
    <form method="post" enctype="multipart/form-data">
        <div style="display:flex;flex-direction:column;gap:0.75rem;max-width:800px;">
            <label>Nomor Surat (kosongkan untuk auto)</label>
            <input type="text" name="letter_number" placeholder="SK-..." style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">

            <label>Judul</label>
            <input type="text" name="title" required style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">

            <label>Recipient</label>
            <input type="text" name="recipient" style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">

            <label>Isi Surat (editor WYSIWYG)</label>
            <textarea id="contentEditor" name="content" rows="12" style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;"></textarea>

            <label>File Tanda Tangan (gambar)</label>
            <div style="color:#777;font-size:0.9rem;">Tanda tangan dikelola di <strong>Set TTD Saya</strong> (menu Surat Keluar).</div>

            <label>Attachment (opsional, gambar/pdf)</label>
            <input type="file" name="attachment" accept="image/*,application/pdf">

            <div style="display:flex;gap:0.5rem;margin-top:0.5rem;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="./list.php" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

<!-- TinyMCE WYSIWYG (self-hosted CDN to avoid API key warning) -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#contentEditor',
    height: 350,
    menubar: true,
    plugins: [
        'advlist autolink lists link image charmap preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste help wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
    content_style: 'body { font-family:Arial,Helvetica,sans-serif; font-size:14px }'
});
</script>
