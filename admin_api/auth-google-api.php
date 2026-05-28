<?php
require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['credential'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Credential is required.']);
    exit;
}

$id_token = $data['credential'];
$parts = explode('.', $id_token);

if (count($parts) !== 3) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JWT format.']);
    exit;
}

$payload = $parts[1];
$decoded_payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);

if (!$decoded_payload || !isset($decoded_payload['email'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid Google Token Payload.']);
    exit;
}

$email = $decoded_payload['email'];
$google_id = $decoded_payload['sub'] ?? '';

$user = null;
$role = null;
$source = null;

// Check in proyekdesa (Internal Projects)
try {
    $stmt = $pdo_proyek->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $project_user = $stmt->fetch();

    if ($project_user) {
        $user = $project_user;
        $role = 'project_manager';
        $source = 'proyekdesa';
        
        // Update google_id if empty
        if (empty($project_user['google_id'])) {
            $stmt = $pdo_proyek->prepare('UPDATE users SET google_id = ? WHERE id = ?');
            $stmt->execute([$google_id, $user['id']]);
        }
    }
} catch (Exception $e) {}

// If not found in proyekdesa, check in portfolio admin_users
if (!$user) {
    try {
        $stmt = $pdo_portofolio->prepare('SELECT * FROM admin_users WHERE email = ?');
        $stmt->execute([$email]);
        $portfolio_user = $stmt->fetch();

        if ($portfolio_user) {
            $user = $portfolio_user;
            $role = 'portfolio_admin';
            $source = 'desadroid_portfolio';
        }
    } catch (Exception $e) {}
}

if ($user) {
    $token = bin2hex(random_bytes(32));
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Google Login successful',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'email' => $email,
            'role' => $role,
            'source' => $source
        ]
    ]);
} else {
    // If not found in any database, reject!
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => "Unregistered Admin Email: $email"]);
}
?>
