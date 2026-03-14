<?php
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        try {
            // Check user exists (debug)
            $stmt = $pdo->prepare('SELECT id, username, email, password, full_name, role, status FROM admin_users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Debug info
            if (!$user) {
                $error = 'Username tidak ditemukan! Gunakan: admin';
            } elseif ($user['status'] !== 'active') {
                $error = 'Akun tidak aktif. Hubungi admin.';
            } elseif ($password !== $user['password']) {
                $error = 'Password salah! Default: admin123';
            } else {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['admin_role'] = $user['role'];

                // Update last login
                $update = $pdo->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = ?');
                $update->execute([$user['id']]);

                header('Location: index.php');
                exit;
            }
        } catch(Exception $e) {
            $error = 'Error Database: ' . $e->getMessage();
        }
    }
}

// Check if session exists
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Desadroid</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-header h1 {
            color: #0066cc;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input {
            padding-right: 2.5rem;
        }
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            color: #666;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: auto;
            height: auto;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #0066cc;
            background: none;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }
        button:hover {
            background: #0052a3;
        }
        .alert {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .alert-error {
            background: #fee;
            color: #cc0000;
            border: 1px solid #fcc;
        }
        .alert-success {
            background: #efe;
            color: #008000;
            border: 1px solid #cfc;
        }
        .demo-info {
            background: #f0f7ff;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #333;
        }
        .demo-info strong {
            color: #0066cc;
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 1.5rem;
                max-width: 90%;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            input {
                min-height: 44px;
                font-size: 16px;
            }
            
            button {
                min-height: 44px;
                font-size: 1rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .login-container {
                padding: 1rem;
                border-radius: 4px;
                max-width: 100%;
            }
            
            .login-header {
                margin-bottom: 1rem;
            }
            
            .login-header h1 {
                font-size: 1.3rem;
            }
            
            .login-header p {
                font-size: 0.85rem;
            }
            
            .form-group {
                margin-bottom: 0.75rem;
            }
            
            label {
                font-size: 0.9rem;
            }
            
            input {
                min-height: 44px;
                padding: 0.75rem;
                font-size: 16px;
            }
            
            button {
                min-height: 44px;
                padding: 0.75rem;
                font-size: 1rem;
            }
            
            .demo-info {
                padding: 0.75rem;
                margin-top: 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>desadroid</h1>
            <p>Admin Panel Login</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['expired'])): ?>
            <div class="alert alert-error">Session expired. Silakan login kembali.</div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="password-toggle" id="toggle-password" aria-label="Show password">
                            <span style="font-size:14px; line-height:1;">Show</span>
                        </button>
                    </div>
            </div>
            <button type="submit">Login</button>
        </form>

        <!-- Demo admin list removed for security / cleaner login -->
    </div>
    <script>
        const togglePasswordBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePasswordBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            togglePasswordBtn.innerHTML = isPassword ? '<span style="font-size:14px; line-height:1;">Hide</span>' : '<span style="font-size:14px; line-height:1;">Show</span>';
            togglePasswordBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    </script>
</body>
</html>
