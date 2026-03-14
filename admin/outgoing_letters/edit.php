<?php
$page_title = 'Edit Surat';
require_once '../../config/db.php';
require_once '../includes/header.php';
require_once '../../config/upload.php';

$success = '';
$error = '';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM outgoing_letters WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $letter = $stmt->fetch();
    if (!$letter) {
        header('Location: list.php');
        exit;
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $letter_number = trim($_POST['letter_number'] ?? $letter['letter_number']);
    $title = trim($_POST['title'] ?? $letter['title']);
    $content = $_POST['content'] ?? $letter['content'];
    $recipient = trim($_POST['recipient'] ?? $letter['recipient']);
    $status = $_POST['status'] ?? $letter['status'];

    // helper to convert stored URL to local file path
    function url_to_local_path($url) {
        if (!$url) return false;
        if (strpos($url, 'file://') === 0) return substr($url, 7);
        // if already absolute
        if (DIRECTORY_SEPARATOR === '\\' && preg_match('#^[A-Za-z]:\\#', $url)) return $url;
        if (strpos($url, $_SERVER['DOCUMENT_ROOT']) === 0) return $url;
        // web root relative
        $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
        $candidate = $docRoot . '/' . ltrim($url, '/');
        if (file_exists($candidate)) return $candidate;
        // project relative
        $proj = realpath(__DIR__ . '/../../');
        $candidate2 = $proj . '/' . ltrim($url, '/');
        if (file_exists($candidate2)) return $candidate2;
        return false;
    }

    // signature
    $signature_url = $letter['signature_file'];
    if (!empty($_FILES['signature_file']) && $_FILES['signature_file']['size'] > 0) {
        $res = uploadImage($_FILES['signature_file'], 'uploads/signatures');
        if ($res['success']) {
            // delete old file if exists
            if (!empty($letter['signature_file'])) {
                $oldPath = url_to_local_path($letter['signature_file']);
                if ($oldPath && file_exists($oldPath)) @unlink($oldPath);
            }
            $signature_url = $res['url'];
        } else {
            $error = $res['message'];
        }
    }

    // attachment
    $attachment_url = $letter['attachments'];
    if (!$error && !empty($_FILES['attachment']) && $_FILES['attachment']['size'] > 0) {
        $res2 = uploadImage($_FILES['attachment'], 'uploads/letters');
        if ($res2['success']) {
            // delete old attachment file
            if (!empty($letter['attachments'])) {
                $oldAttach = url_to_local_path($letter['attachments']);
                if ($oldAttach && file_exists($oldAttach)) @unlink($oldAttach);
            }
            $attachment_url = $res2['url'];
        } else {
            $error = $res2['message'];
        }
    }

    if (!$error) {
        try {
            $stmt = $pdo->prepare('UPDATE outgoing_letters SET letter_number = ?, title = ?, content = ?, recipient = ?, attachments = ?, signature_file = ?, status = ? WHERE id = ?');
            $stmt->execute([$letter_number, $title, $content, $recipient, $attachment_url, $signature_url, $status, $id]);
            $success = 'Surat berhasil diperbarui.';
            // refresh data
            $stmt2 = $pdo->prepare('SELECT * FROM outgoing_letters WHERE id = ?');
            $stmt2->execute([$id]);
            $letter = $stmt2->fetch();
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
    <h2>Edit Surat Keluar</h2>
    <a href="./list.php" class="btn btn-secondary">← Kembali</a>
</div>

<div style="background:white;padding:1.5rem;border-radius:6px;">
    <form method="post" enctype="multipart/form-data">
        <div style="display:flex;flex-direction:column;gap:0.75rem;max-width:800px;">
            <label>Nomor Surat</label>
            <input type="text" name="letter_number" value="<?= htmlspecialchars($letter['letter_number']) ?>" style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">

            <label>Judul</label>
            <input type="text" name="title" value="<?= htmlspecialchars($letter['title']) ?>" required style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">

            <label>Recipient</label>
            <input type="text" name="recipient" value="<?= htmlspecialchars($letter['recipient']) ?>" style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">

            <label>Isi Surat (HTML diperbolehkan)</label>
            <textarea name="content" rows="8" style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;"><?= htmlspecialchars($letter['content']) ?></textarea>

            <label>Status</label>
            <select name="status" style="padding:0.6rem;border:1px solid #ddd;border-radius:4px;">
                <option value="draft" <?= $letter['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="sent" <?= $letter['status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="archived" <?= $letter['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
            </select>

            <label>File Tanda Tangan (gambar) - saat ini: <?= $letter['signature_file'] ? '<a href="' . htmlspecialchars($letter['signature_file']) . '" target="_blank">lihat</a>' : 'tidak ada' ?></label>
            <input type="file" name="signature_file" accept="image/*">

            <label>Attachment (opsional) - saat ini: <?= $letter['attachments'] ? '<a href="' . htmlspecialchars($letter['attachments']) . '" target="_blank">lihat</a>' : 'tidak ada' ?></label>
            <input type="file" name="attachment" accept="image/*,application/pdf">

            <div style="display:flex;gap:0.5rem;margin-top:0.5rem;">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="./list.php" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
