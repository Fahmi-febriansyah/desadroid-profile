<?php
require_once '../../config/db.php';
require_once '../../config/upload.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

reset($_FILES);
$temp = current($_FILES);

if (is_uploaded_file($temp['tmp_name'])) {
    $upload = uploadImage($temp, 'uploads/articles/content');
    
    if ($upload['success']) {
        // Compute absolute URL for TinyMCE
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        // Go up two levels since we are in admin/artikel
        $projectRoot = dirname(dirname($baseDir));
        if ($projectRoot === '/') $projectRoot = '';
        
        echo json_encode(['location' => $upload['url']]);
    } else {
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['error' => $upload['message']]);
    }
} else {
    header("HTTP/1.1 500 Internal Server Error");
}
?>
