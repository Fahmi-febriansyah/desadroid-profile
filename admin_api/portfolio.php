<?php
require 'config.php';

$action = $_GET['action'] ?? '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'list_articles') {
            $stmt = $pdo_portofolio->query("SELECT id, title, category, status, views, created_at FROM articles ORDER BY created_at DESC");
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
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($action === 'create_article') {
            $title = $data['title'] ?? '';
            $category = $data['category'] ?? '';
            $excerpt = $data['excerpt'] ?? '';
            $content = $data['content'] ?? '';
            $status = $data['status'] ?? 'draft';
            
            $stmt = $pdo_portofolio->prepare("INSERT INTO articles (title, slug, category, excerpt, content, status, views, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())");
            // Simple slug generator for now
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            
            $stmt->execute([$title, $slug, $category, $excerpt, $content, $status]);
            echo json_encode(['status' => 'success', 'message' => 'Article created successfully']);
        }
    }
    
    elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($action === 'update_article') {
            $id = $data['id'] ?? 0;
            $title = $data['title'] ?? '';
            $category = $data['category'] ?? '';
            $excerpt = $data['excerpt'] ?? '';
            $content = $data['content'] ?? '';
            $status = $data['status'] ?? 'draft';
            
            $stmt = $pdo_portofolio->prepare("UPDATE articles SET title = ?, category = ?, excerpt = ?, content = ?, status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $category, $excerpt, $content, $status, $id]);
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
