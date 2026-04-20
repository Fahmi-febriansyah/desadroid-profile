<?php
$page_title = 'Proyek';
require_once '../../config/db.php';
require_once '../includes/header.php';

$success = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        // Get project to delete image
        $stmt = $pdo->prepare('SELECT image_url FROM projects WHERE id = ?');
        $stmt->execute([$_GET['delete']]);
        $project = $stmt->fetch();
        
        if ($project && $project['image_url']) {
            $filepath = $_SERVER['DOCUMENT_ROOT'] . '/portofolio perusahaan/' . ltrim($project['image_url'], '/');
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
        // Delete gallery images files and rows
        $gstmt = $pdo->prepare('SELECT image_url FROM project_images WHERE project_id = ?');
        $gstmt->execute([$_GET['delete']]);
        $gimgs = $gstmt->fetchAll();
        foreach ($gimgs as $gi) {
            if (!empty($gi['image_url'])) {
                $gfile = $_SERVER['DOCUMENT_ROOT'] . '/portofolio perusahaan/' . ltrim($gi['image_url'], '/');
                if (file_exists($gfile)) {
                    @unlink($gfile);
                }
            }
        }
        $pdo->prepare('DELETE FROM project_images WHERE project_id = ?')->execute([$_GET['delete']]);
        
        // Delete project
        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->execute([$_GET['delete']]);
        $success = 'Proyek berhasil dihapus!';
    } catch(Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all projects
try {
    $search = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? '';
    
    $query = 'SELECT * FROM projects WHERE 1=1';
    $params = [];
    
    if ($search) {
        $query .= ' AND (title LIKE ? OR description LIKE ?)';
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    
    if ($category) {
        $query .= ' AND category = ?';
        $params[] = $category;
    }
    
    $query .= ' ORDER BY order_num ASC, created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $projects = $stmt->fetchAll();
    
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
    <h2>Daftar Proyek</h2>
    <a href="./create.php" class="btn btn-primary">+ Buat Proyek Baru</a>
</div>

<div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" placeholder="Cari proyek..." value="<?= htmlspecialchars($search) ?>" style="flex: 1; min-width: 200px; padding: 0.6rem; border: 1px solid #ddd; border-radius: 4px;">
        <select name="category" style="padding: 0.6rem; border: 1px solid #ddd; border-radius: 4px;">
            <option value="">Semua Kategori</option>
            <option value="Web Development" <?= $category === 'Web Development' ? 'selected' : '' ?>>Web Development</option>
            <option value="Mobile App" <?= $category === 'Mobile App' ? 'selected' : '' ?>>Mobile App</option>
            <option value="E-Commerce" <?= $category === 'E-Commerce' ? 'selected' : '' ?>>E-Commerce</option>
            <option value="Creative Tech" <?= $category === 'Creative Tech' ? 'selected' : '' ?>>Creative Tech</option>
        </select>
        <button type="submit" class="btn btn-primary">Cari</button>
    </form>
</div>

<div style="background: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); overflow: hidden;">
    <?php if (empty($projects)): ?>
        <div style="padding: 2rem; text-align: center; color: #999;">
            <p>Tidak ada proyek. <a href="./create.php">Buat proyek sekarang</a></p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px;">Gambar</th>
                        <th>Judul</th>
                        <th style="width:90px">Progress</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Link</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td>
                                <?php if ($project['image_url']): ?>
                                    <img src="<?= htmlspecialchars($project['image_url']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #999;">N/A</div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars(substr($project['title'], 0, 30)) ?></strong></td>
                            <td><?= intval($project['progress'] ?? 0) ?>%</td>
                            <td><?= htmlspecialchars($project['category']) ?></td>
                            <td><?= htmlspecialchars(substr($project['description'] ?? '', 0, 40)) ?></td>
                            <td>
                                <?php if ($project['link']): ?>
                                    <a href="<?= htmlspecialchars($project['link']) ?>" target="_blank" style="color: #0066cc; text-decoration: none;">Link</a>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="./edit.php?id=<?= $project['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?delete=<?= $project['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus proyek ini?');">Hapus</a>
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
