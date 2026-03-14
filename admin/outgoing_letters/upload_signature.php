<?php
header('Content-Type: application/json');
ob_start();
require_once '../../config/db.php';
require_once '../../config/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid letter id']);
    exit;
}

if (empty($_FILES['signature'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
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
    $stmt = $pdo->prepare('UPDATE outgoing_letters SET signature_file = ? WHERE id = ?');
    $stmt->execute([$url, $id]);
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
