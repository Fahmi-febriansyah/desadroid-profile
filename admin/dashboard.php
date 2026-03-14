<?php
$page_title = 'Dashboard';
require_once '../config/db.php';
require_once './includes/header.php';

try {
    // Get statistics
    $artikel_count = $pdo->query('SELECT COUNT(*) as count FROM articles WHERE status = "published"')->fetch()['count'];
    $draft_count = $pdo->query('SELECT COUNT(*) as count FROM articles WHERE status = "draft"')->fetch()['count'];
    $total_views = $pdo->query('SELECT SUM(views) as total FROM articles')->fetch()['total'] ?? 0;
    $project_count = $pdo->query('SELECT COUNT(*) as count FROM projects')->fetch()['count'];
    $messages_count = $pdo->query('SELECT COUNT(*) as count FROM contact_messages WHERE status = "new"')->fetch()['count'];

    // Get recent articles
    $recent_articles = $pdo->query('
        SELECT a.*, u.full_name 
        FROM articles a 
        JOIN admin_users u ON a.author_id = u.id 
        ORDER BY a.created_at DESC 
        LIMIT 5
    ')->fetchAll();

} catch(Exception $e) {
    $error = 'Error: ' . $e->getMessage();
}
?>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #0066cc; text-align: center;">
        <h3 style="color: #666; font-size: 0.85rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Artikel</h3>
        <p style="font-size: 1.8rem; color: #0066cc; font-weight: 700; margin: 0;"><?= $artikel_count ?></p>
        <small style="color: #999; display: block; margin-top: 0.25rem;">Published</small>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #f39c12; text-align: center;">
        <h3 style="color: #666; font-size: 0.85rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Draft</h3>
        <p style="font-size: 1.8rem; color: #f39c12; font-weight: 700; margin: 0;"><?= $draft_count ?></p>
        <small style="color: #999; display: block; margin-top: 0.25rem;">Article</small>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #3498db; text-align: center;">
        <h3 style="color: #666; font-size: 0.85rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Proyek</h3>
        <p style="font-size: 1.8rem; color: #3498db; font-weight: 700; margin: 0;"><?= $project_count ?></p>
        <small style="color: #999; display: block; margin-top: 0.25rem;">Total</small>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #27ae60; text-align: center;">
        <h3 style="color: #666; font-size: 0.85rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Views</h3>
        <p style="font-size: 1.8rem; color: #27ae60; font-weight: 700; margin: 0;"><?= number_format($total_views) ?></p>
        <small style="color: #999; display: block; margin-top: 0.25rem;">Total</small>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08); border-left: 4px solid #e74c3c; text-align: center;">
        <h3 style="color: #666; font-size: 0.85rem; margin-bottom: 0.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Pesan</h3>
        <p style="font-size: 1.8rem; color: #e74c3c; font-weight: 700; margin: 0;"><?= $messages_count ?></p>
        <small style="color: #999; display: block; margin-top: 0.25rem;">Baru</small>
    </div>
</div>

<div style="background: white; padding: 1.5rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="color: #0066cc; font-size: 1.2rem;">Artikel Terbaru</h3>
        <a href="./artikel/list.php" class="btn btn-primary">Lihat Semua</a>
    </div>
    
    <?php if (empty($recent_articles)): ?>
        <p style="color: #999; text-align: center; padding: 2rem;">Belum ada artikel. <a href="./artikel/create.php">Buat artikel sekarang</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_articles as $article): ?>
                    <tr>
                        <td><?= htmlspecialchars(substr($article['title'], 0, 30)) ?>...</td>
                        <td><?= htmlspecialchars($article['category']) ?></td>
                        <td><?= htmlspecialchars($article['full_name']) ?></td>
                        <td>
                            <span style="padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.85rem; font-weight: 600; background: <?= $article['status'] === 'published' ? '#d4edda' : '#fff3cd' ?>; color: <?= $article['status'] === 'published' ? '#155724' : '#856404' ?>;">
                                <?= ucfirst($article['status']) ?>
                            </span>
                        </td>
                        <td><?= number_format($article['views']) ?></td>
                        <td><?= date('d M Y', strtotime($article['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: repeat(auto-fit"] {
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)) !important;
        gap: 1rem !important;
        margin-bottom: 1.5rem !important;
    }
    
    div[style*="padding: 1.5rem; border-radius: 6px"] {
        padding: 1rem !important;
    }
    
    div[style*="display: flex; justify-content: space-between"] {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    h3 {
        font-size: 1rem !important;
    }
    
    table {
        font-size: 0.85rem !important;
    }
    
    table th,
    table td {
        padding: 0.5rem !important;
    }
    
    .btn {
        min-height: 40px !important;
        width: 100% !important;
    }
}

@media (max-width: 480px) {
    div[style*="grid-template-columns: repeat(auto-fit"] {
        grid-template-columns: 1fr !important;
        gap: 0.75rem !important;
        margin-bottom: 1rem !important;
    }
    
    div[style*="padding: 1.5rem; border-radius: 6px"] {
        padding: 0.75rem !important;
    }
    
    h3 {
        font-size: 0.9rem !important;
    }
    
    p[style*="font-size: 1.8rem"] {
        font-size: 1.5rem !important;
    }
    
    table {
        font-size: 0.75rem !important;
    }
    
    table th,
    table td {
        padding: 0.35rem !important;
        white-space: nowrap !important;
    }
    
    .btn {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.9rem !important;
    }
}
</style>

<?php require_once './includes/footer.php'; ?>
