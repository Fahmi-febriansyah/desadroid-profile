<?php
// Image Upload Handler
function uploadImage($file, $folder = 'uploads') {
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan: JPG, PNG, WEBP, GIF'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error saat upload file'];
    }
    
    // Determine project root (one level up from config directory)
    $project_root = realpath(dirname(__DIR__));
    $base_path = $project_root . DIRECTORY_SEPARATOR . $folder;
    if (!is_dir($base_path)) {
        mkdir($base_path, 0755, true);
    }
    
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $filepath = $base_path . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Build public URL relative to document root
        $public_path = str_replace('\\', '/', $filepath);
        $doc_root = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
        $url_path = str_replace($doc_root, '', $public_path);
        $url_path = '/' . ltrim($url_path, '/');

        return [
            'success' => true,
            'filename' => $filename,
            'url' => $url_path,
            'path' => $filepath
        ];
    }
    
    return ['success' => false, 'message' => 'Gagal menyimpan file'];
}

// Delete Image
function deleteImage($filename, $folder = 'uploads') {
    $filepath = $_SERVER['DOCUMENT_ROOT'] . '/portofolio perusahaan/' . $folder . '/' . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
        return true;
    }
    return false;
}
