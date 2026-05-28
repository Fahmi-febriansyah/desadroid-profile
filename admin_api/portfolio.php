<?php
require 'config.php';

$action = $_GET['action'] ?? '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'list_articles') {
            $stmt = $pdo_portofolio->query("SELECT id, title, category, status, views, created_at, featured_image FROM articles ORDER BY created_at DESC");
            $articles = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $articles]);
        } 
        elseif ($action === 'get_article') {
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo_portofolio->prepare("SELECT * FROM articles WHERE id = ?");
            $stmt->execute([$id]);
            $article = $stmt->fetch();
            if ($article) {
                echo json_encode(['status' => 'success', 'data' => $article]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Article not found']);
            }
        }
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // We now support both JSON and multipart/form-data
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        
        $data = [];
        if (strpos($contentType, 'application/json') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            $data = $_POST; // For multipart/form-data
        }
        
        if ($action === 'create_article') {
            $title = $data['title'] ?? '';
            $category = $data['category'] ?? '';
            $excerpt = $data['excerpt'] ?? '';
            $content = $data['content'] ?? '';
            $status = $data['status'] ?? 'draft';
            
            // Handle image upload
            $featured_image = '';
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/'; // Ensure this folder exists in your public_html
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['featured_image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetFilePath)) {
                    $featured_image = 'uploads/' . $fileName;
                }
            }
            
            $stmt = $pdo_portofolio->prepare("INSERT INTO articles (title, category, excerpt, content, featured_image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            
            $stmt->execute([$title, $category, $excerpt, $content, $featured_image, $status]);
            echo json_encode(['status' => 'success', 'message' => 'Article created successfully']);
        }
        
        elseif ($action === 'update_article') {
            // Note: HTML forms only support POST/GET natively. React Native fetch can send PUT with FormData, but PHP doesn't populate $_POST/$_FILES for PUT requests natively.
            // So for updates with images, we send a POST request with action=update_article
            $id = $data['id'] ?? 0;
            $title = $data['title'] ?? '';
            $category = $data['category'] ?? '';
            $excerpt = $data['excerpt'] ?? '';
            $content = $data['content'] ?? '';
            $status = $data['status'] ?? 'draft';
            
            // Get existing image
            $stmt = $pdo_portofolio->prepare("SELECT featured_image FROM articles WHERE id = ?");
            $stmt->execute([$id]);
            $existing = $stmt->fetch();
            $featured_image = $existing ? $existing['featured_image'] : '';
            
            // Handle new image upload if provided
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['featured_image']['name']);
                $targetFilePath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $targetFilePath)) {
                    $featured_image = 'uploads/' . $fileName;
                }
            }
            
            $stmt = $pdo_portofolio->prepare("UPDATE articles SET title = ?, category = ?, excerpt = ?, content = ?, featured_image = ?, status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $category, $excerpt, $content, $featured_image, $status, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Article updated successfully']);
        }
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if ($action === 'delete_article') {
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo_portofolio->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Article deleted successfully']);
        }
    }
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
