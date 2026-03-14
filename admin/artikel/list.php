<?php
$page_title = 'Artikel';
require_once '../../config/db.php';
require_once '../includes/header.php';

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare('DELETE FROM articles WHERE id = ?');
        $stmt->execute([$_GET['delete']]);
        $success = 'Artikel berhasil dihapus!';
    } catch(Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all articles
try {
    $search = $_GET['search'] ?? '';
    $status = $_GET['status'] ?? '';
    
    $query = 'SELECT a.*, u.full_name FROM articles a JOIN admin_users u ON a.author_id = u.id WHERE 1=1';
    $params = [];
    
    if ($search) {
        $query .= ' AND (a.title LIKE ? OR a.category LIKE ?)';
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    
    if ($status) {
        $query .= ' AND a.status = ?';
        $params[] = $status;
    }
    
    $query .= ' ORDER BY a.created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $articles = $stmt->fetchAll();
    
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
    <h2>Daftar Artikel</h2>
    <a href="./create.php" class="btn btn-primary">+ Buat Artikel Baru</a>
</div>

<div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>" style="flex: 1; min-width: 200px; padding: 0.6rem; border: 1px solid #ddd; border-radius: 4px;">
        <select name="status" style="padding: 0.6rem; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">Semua Status</option>
            <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>

<div style="background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); overflow: hidden;">
    <?php if (empty($articles)): ?>
        <div style="padding: 2rem; text-align: center; color: #999;">
            <p>Tidak ada artikel. <a href="./create.php">Buat artikel sekarang</a></p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td>
                            <?php if ($article['featured_image']): ?>
                                <img src="<?= htmlspecialchars($article['featured_image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #999;">N/A</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars(substr($article['title'], 0, 40)) ?></strong></td>
                        <td><?= htmlspecialchars($article['category']) ?></td>
                        <td><?= htmlspecialchars($article['full_name']) ?></td>
                        <td>
                            <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.85rem; font-weight: 600; background: <?= $article['status'] === 'published' ? '#d4edda' : '#fff3cd' ?>; color: <?= $article['status'] === 'published' ? '#155724' : '#856404' ?>;">
                                <?= ucfirst($article['status']) ?>
                            </span>
                        </td>
                        <td><?= number_format($article['views']) ?></td>
                        <td><?= date('d M Y', strtotime($article['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="./edit.php?id=<?= $article['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="?delete=<?= $article['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus artikel ini?');">Hapus</a>
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
    
    div[style*="padding: 1.5rem"] {
        padding: 0.75rem !important;
    }
    
    table {
        font-size: 0.75rem !important;
    }
    
    table th,
    table td {
        padding: 0.35rem !important;
        white-space: nowrap !important;
    }
    
    img {
        width: 40px !important;
        height: 40px !important;
    }
    
    div[style*="width: 60px"] {
        width: 40px !important;
        height: 40px !important;
        font-size: 0.7rem !important;
    }
    
    input[type="text"],
    select {
        font-size: 16px !important;
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
