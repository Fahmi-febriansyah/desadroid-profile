<?php
header('Content-Type: application/json');
require_once '../../config/db.php';
session_start();
$admin_id = $_SESSION['admin_id'] ?? 0;
if (!$admin_id) { echo json_encode(['success'=>false]); exit; }
try {
    $stmt = $pdo->prepare('SELECT signature_file FROM admin_users WHERE id = ? LIMIT 1');
    $stmt->execute([$admin_id]);
    $row = $stmt->fetch();
    if ($row && $row['signature_file']) echo json_encode(['success'=>true,'url'=>$row['signature_file']]);
    else echo json_encode(['success'=>true,'url'=>null]);
} catch (Exception $e) { echo json_encode(['success'=>false]); }
