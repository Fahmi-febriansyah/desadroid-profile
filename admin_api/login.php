<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$user = null;
$role = null;
$source = null;

// Check in proyekdesa (Internal Projects) First
try {
    $stmt = $pdo_proyek->prepare('SELECT * FROM users WHERE email = ? OR username = ?');
    $stmt->execute([$email, $email]);
    $project_user = $stmt->fetch();

    if ($project_user) {
        // Check password (handle both hash and plaintext just in case)
        $is_valid = password_verify($password, $project_user['password']) || $password === $project_user['password'];
        if ($is_valid) {
            $user = $project_user;
            $role = 'project_manager';
            $source = 'proyekdesa';
        }
    }
} catch (Exception $e) {
    // Ignore and proceed to portfolio
}

// If not found in proyekdesa, check in portfolio admin_users
if (!$user) {
    try {
        $stmt = $pdo_portofolio->prepare('SELECT * FROM admin_users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $email]);
        $portfolio_user = $stmt->fetch();

        if ($portfolio_user) {
            $is_valid = password_verify($password, $portfolio_user['password']) || $password === $portfolio_user['password'];
            if ($is_valid) {
                $user = $portfolio_user;
                $role = 'portfolio_admin';
                $source = 'desadroid_portfolio';
            }
        }
    } catch (Exception $e) {
        // Ignore
    }
}

if ($user) {
    // Generate a simple token (In production, use JWT)
    $token = bin2hex(random_bytes(32));
    
    // Return user info and token
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $role,
            'source' => $source
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
}
?>
