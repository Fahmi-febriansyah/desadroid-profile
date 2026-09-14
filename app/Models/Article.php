<?php
namespace App\Models;

use App\Core\Model;

class Article extends Model
{
    public static function getAllPublished(): array
    {
        return self::query("SELECT * FROM articles WHERE status = 'published' ORDER BY published_date DESC");
    }

    public static function getBySlug(string $slug): ?array
    {
        return self::fetchOne("SELECT * FROM articles WHERE slug = ? AND status = 'published' LIMIT 1", [$slug]);
    }

    public static function getLatest(int $limit = 3): array
    {
        $limit = max(1, (int)$limit);
        return self::query("SELECT * FROM articles WHERE status = 'published' ORDER BY published_date DESC LIMIT {$limit}");
    }

    public static function getRelated(int $currentId, int $limit = 6): array
    {
        $limit = max(1, (int)$limit);
        return self::query("SELECT * FROM articles WHERE status = 'published' AND id != ? ORDER BY published_date DESC LIMIT {$limit}", [$currentId]);
    }

    public static function getAllSlugs(): array
    {
        return self::query("SELECT title, slug FROM articles WHERE status = 'published' ORDER BY title ASC");
    }

    public static function incrementViews(int $id): bool
    {
        return self::execute("UPDATE articles SET views = views + 1 WHERE id = ?", [$id]);
    }

    public static function getCategories(): array
    {
        $articles = self::getAllPublished();
        $categories = ['Semua'];
        foreach ($articles as $a) {
            if (!empty($a['category']) && !in_array($a['category'], $categories)) {
                $categories[] = $a['category'];
            }
        }
        return $categories;
    }
}
