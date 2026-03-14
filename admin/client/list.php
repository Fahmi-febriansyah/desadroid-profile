<?php
$page_title = 'Klien & Testimonial';
require_once '../../config/db.php';
require_once '../includes/header.php';

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
        $stmt->execute([$_GET['delete']]);
        $success = 'Testimonial berhasil dihapus!';
    } catch(Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all testimonials
try {
    $search = $_GET['search'] ?? '';
    $query = 'SELECT * FROM testimonials WHERE 1=1';
    $params = [];
    
    if ($search) {
        $query .= ' AND (client_name LIKE ? OR message LIKE ?)';
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    
    $query .= ' ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $testimonials = $stmt->fetchAll();
    
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
    <h2>Klien & Testimonial</h2>
    <a href="./create.php" class="btn btn-primary">+ Tambah Klien</a>
</div>

<div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" placeholder="Cari klien..." value="<?= htmlspecialchars($search) ?>" style="flex: 1; min-width: 200px; padding: 0.6rem; border: 1px solid #ddd; border-radius: 4px;">
        <select name="status" style="padding: 0.6rem; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">Semua Status</option>
            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>

<div style="background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); overflow: hidden;">
    <?php if (empty($testimonials)): ?>
        <div style="padding: 2rem; text-align: center; color: #999;">
            <p>Tidak ada klien. <a href="./create.php">Tambah klien sekarang</a></p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Nama Klien</th>
                    <th>Perusahaan</th>
                    <th>Pesan</th>
                    <th>Rating</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($testimonials as $testimonial): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($testimonial['client_name']) ?></strong></td>
                        <td><?= htmlspecialchars($testimonial['company'] ?? '-') ?></td>
                        <td><?= htmlspecialchars(substr($testimonial['message'] ?? '', 0, 50)) ?></td>
                        <td>
                            <span style="color: #f39c12;">
                                <?php for ($i = 0; $i < ($testimonial['rating'] ?? 0); $i++): ?>
                                    ★
                                <?php endfor; ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($testimonial['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="./edit.php?id=<?= $testimonial['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="?delete=<?= $testimonial['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus klien ini?');">Hapus</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
</div>

<style>
@media (max-width: 768px) {
    div[style*="display: flex; gap: 1rem; flex-wrap"] {
        flex-direction: column !important;
    }
    
    input[type="text"],
    select,
    .btn {
        min-height: 44px !important;
        font-size: 1rem !important;
        width: 100% !important;
    }
    
    table {
        font-size: 0.85rem !important;
    }
    
    table th,
    table td {
        padding: 0.5rem !important;
    }
    
    .btn-sm {
        padding: 0.35rem 0.75rem !important;
        font-size: 0.8rem !important;
        min-height: auto !important;
    }
    
    .action-btns {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.25rem !important;
    }
}

@media (max-width: 480px) {
    .page-header {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .page-header h2 {
        font-size: 1.5rem !important;
        margin: 0 !important;
    }
    
    .page-header .btn {
        width: 100% !important;
    }
    
    table {
        font-size: 0.75rem !important;
    }
    
    table th,
    table td {
        padding: 0.35rem !important;
        white-space: nowrap !important;
    }
    
    .btn-sm {
        padding: 0.3rem 0.5rem !important;
        font-size: 0.7rem !important;
    }
    
    .action-btns {
        display: flex !important;
        gap: 0.25rem !important;
        flex-direction: row !important;
    }
}
</style>

<?php require_once '../includes/footer.php'; ?>
