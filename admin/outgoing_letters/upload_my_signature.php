<?php
header('Content-Type: application/json');
// Buffer output to capture any unexpected warnings and return them in JSON
ob_start();
require_once '../../config/db.php';
require_once '../../config/upload.php';

session_start();
$admin_id = $_SESSION['admin_id'] ?? 0;
if (!$admin_id) {
    $buf = ob_get_clean();
    $resp = ['success' => false, 'message' => 'Not authenticated'];
    if ($buf) $resp['debug_output'] = trim(strip_tags($buf));
    echo json_encode($resp);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $buf = ob_get_clean();
    $resp = ['success' => false, 'message' => 'Invalid method'];
    if ($buf) $resp['debug_output'] = trim(strip_tags($buf));
    echo json_encode($resp);
    exit;
}

if (empty($_FILES['signature'])) {
    $buf = ob_get_clean();
    $resp = ['success' => false, 'message' => 'No file uploaded'];
    if ($buf) $resp['debug_output'] = trim(strip_tags($buf));
    echo json_encode($resp);
    exit;
}

$res = uploadImage($_FILES['signature'], 'uploads/signatures');
if (!$res['success']) {
    $buf = ob_get_clean();
    $resp = ['success' => false, 'message' => $res['message']];
    if ($buf) $resp['debug_output'] = trim(strip_tags($buf));
    echo json_encode($resp);
    exit;
}

$url = $res['url'];
try {
    // delete old signature file if exists
    $oldStmt = $pdo->prepare('SELECT signature_file FROM admin_users WHERE id = ? LIMIT 1');
    $oldStmt->execute([$admin_id]);
    $oldRow = $oldStmt->fetch();
    if ($oldRow && !empty($oldRow['signature_file'])) {
        $oldUrl = $oldRow['signature_file'];
        // try resolve local path
        if (strpos($oldUrl, 'file://') === 0) {
            $oldPath = substr($oldUrl, 7);
        } else {
            $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
            $cand = $docRoot . '/' . ltrim($oldUrl, '/');
            if (file_exists($cand)) $oldPath = $cand; else {
                $proj = realpath(__DIR__ . '/../../');
                $cand2 = $proj . '/' . ltrim($oldUrl, '/');
                if (file_exists($cand2)) $oldPath = $cand2; else $oldPath = false;
            }
        }
        if (!empty($oldPath) && file_exists($oldPath)) @unlink($oldPath);
    }

    $stmt = $pdo->prepare('UPDATE admin_users SET signature_file = ? WHERE id = ?');
    $stmt->execute([$url, $admin_id]);
    $buf = ob_get_clean();
    $resp = ['success' => true, 'url' => $url];
    if ($buf) $resp['debug_output'] = trim(strip_tags($buf));
    echo json_encode($resp);
} catch (Exception $e) {
    $buf = ob_get_clean();
    $resp = ['success' => false, 'message' => 'DB error', 'debug' => $e->getMessage()];
    if ($buf) $resp['debug_output'] = trim(strip_tags($buf));
    echo json_encode($resp);
}
