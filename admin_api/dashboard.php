<?php
require 'config.php';

try {
    $stats = [
        'active_projects' => 0,
        'pending_tasks' => 0,
        'published_articles' => 0,
        'new_messages' => 0
    ];

    // 1. Active Client Projects from `proyekdesa`
    $stmt1 = $pdo_proyek->query("SELECT COUNT(*) FROM projects WHERE status != 'completed' AND status != 'selesai'");
    $stats['active_projects'] = (int) $stmt1->fetchColumn();

    // 2. Pending Tasks (Since there is no tasks table, we use projects with progress < 100)
    $stmt2 = $pdo_proyek->query("SELECT COUNT(*) FROM projects WHERE progress < 100");
    $stats['pending_tasks'] = (int) $stmt2->fetchColumn();

    // 3. Published Articles from `desadroid_portfolio`
    $stmt3 = $pdo_portofolio->query("SELECT COUNT(*) FROM articles WHERE status = 'published'");
    $stats['published_articles'] = (int) $stmt3->fetchColumn();

    // 4. New Contact Messages from `desadroid_portfolio`
    $stmt4 = $pdo_portofolio->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
    $stats['new_messages'] = (int) $stmt4->fetchColumn();

    // Activity Feed (Latest items from articles and messages)
    $activities = [];

    // Get 2 latest articles
    $stmt_art = $pdo_portofolio->query("SELECT title, created_at FROM articles ORDER BY created_at DESC LIMIT 2");
    while ($row = $stmt_art->fetch()) {
        $activities[] = [
            'type' => 'article',
            'text' => 'New article published: "' . $row['title'] . '"',
            'time' => $row['created_at'],
            'icon' => 'document-text',
            'color' => '#059669',
            'bgColor' => '#ecfdf5'
        ];
    }

    // Get 2 latest messages
    $stmt_msg = $pdo_portofolio->query("SELECT name, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 2");
    while ($row = $stmt_msg->fetch()) {
        $activities[] = [
            'type' => 'message',
            'text' => 'New contact message from "' . $row['name'] . '"',
            'time' => $row['created_at'],
            'icon' => 'chatbubble-ellipses',
            'color' => '#ea580c',
            'bgColor' => '#fff7ed'
        ];
    }

    // Get 1 latest project update
    $stmt_proj = $pdo_proyek->query("SELECT title, progress FROM projects ORDER BY id DESC LIMIT 1");
    if ($row = $stmt_proj->fetch()) {
        $activities[] = [
            'type' => 'project',
            'text' => 'Project update for "' . $row['title'] . '" (Progress: ' . $row['progress'] . '%)',
            'time' => date('Y-m-d H:i:s'), // Mock time since no created_at in proyekdesa.projects? Wait, schema has no created_at for proyekdesa.projects.
            'icon' => 'briefcase',
            'color' => '#2563eb',
            'bgColor' => '#eff6ff'
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'stats' => $stats,
            'recent_activity' => $activities
        ]
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
