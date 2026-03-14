<?php
$page_title = 'Tambah Admin';
require_once '../../config/db.php';
require_once '../includes/header.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? 'editor');
    $status = trim($_POST['status'] ?? 'active');

    if ($username === '' || $password === '' || $full_name === '') {
        $errors[] = 'Username, Nama Lengkap, dan Password wajib diisi.';
    }

    // Check unique username
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM admin_users WHERE username = ?');
            $stmt->execute([$username]);
            $exists = $stmt->fetch()['c'] ?? 0;
            if ($exists) {
                $errors[] = 'Username sudah digunakan. Pilih yang lain.';
            }
        } catch (Exception $e) {
            $errors[] = 'Error DB: ' . $e->getMessage();
        }
    }

    if (empty($errors)) {
        try {
            $insert = $pdo->prepare('INSERT INTO admin_users (username, email, password, full_name, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
            $insert->execute([$username, $email, $password, $full_name, $role, $status]);
            $success = 'Admin berhasil ditambahkan.';
            header('Location: list.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Gagal menyimpan: ' . $e->getMessage();
        }
    }
}
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="page-header">
    <h2>Tambah Admin</h2>
    <a href="list.php" class="btn btn-secondary">Kembali ke Daftar</a>
</div>

<div style="background:white;padding:1rem;border-radius:6px;box-shadow:0 2px 4px rgba(0,0,0,.06);max-width:720px;">
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required style="width:100%;padding:.6rem;border:1px solid #ddd;border-radius:4px;">
        </div>
        <div class="form-group" style="margin-top:.75rem;">
            <label>Nama Lengkap</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required style="width:100%;padding:.6rem;border:1px solid #ddd;border-radius:4px;">
        </div>
        <div class="form-group" style="margin-top:.75rem;">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" style="width:100%;padding:.6rem;border:1px solid #ddd;border-radius:4px;">
        </div>
        <div class="form-group" style="margin-top:.75rem;">
            <label>Password (plain text for demo)</label>
            <input type="text" name="password" required style="width:100%;padding:.6rem;border:1px solid #ddd;border-radius:4px;">
        </div>
        <div style="display:flex;gap:1rem;margin-top:.75rem;align-items:center;">
            <div style="flex:1">
                <label>Role</label>
                <input type="text" name="role" value="<?= htmlspecialchars($_POST['role'] ?? 'editor') ?>" style="width:100%;padding:.5rem;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div style="width:160px;">
                <label>Status</label>
                <select name="status" style="width:100%;padding:.5rem;border:1px solid #ddd;border-radius:4px;">
                    <option value="active" <?= (($_POST['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= (($_POST['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Buat Admin</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
