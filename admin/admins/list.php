<?php
$page_title = 'Daftar Admin';
require_once '../../config/db.php';
require_once '../includes/header.php';

try {
    $stmt = $pdo->query('SELECT id, username, email, full_name, role, status, created_at FROM admin_users ORDER BY id ASC');
    $admins = $stmt->fetchAll();
} catch (Exception $e) {
    $admins = [];
    $error = 'Error mengambil data: ' . $e->getMessage();
}
?>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Daftar Admin</h2>
    <a href="create.php" class="btn btn-primary">Tambah Admin</a>
</div>

<div style="background: white; padding: 1rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,.06);">
    <?php if (empty($admins)): ?>
        <p style="color:#666; padding:1.5rem; text-align:center;">Belum ada admin terdaftar.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['id']) ?></td>
                    <td><?= htmlspecialchars($a['username']) ?></td>
                    <td><?= htmlspecialchars($a['full_name']) ?></td>
                    <td><?= htmlspecialchars($a['email']) ?></td>
                    <td><?= htmlspecialchars($a['role']) ?></td>
                    <td><?= htmlspecialchars($a['status']) ?></td>
                    <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="../artikel/edit.php?id=<?= $a['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <a href="#" onclick="return confirm('Hapus admin ini?')" class="btn btn-danger btn-sm">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
