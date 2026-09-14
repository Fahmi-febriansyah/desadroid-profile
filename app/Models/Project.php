<?php
namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    public static function getFeatured(int $limit = 3): array
    {
        $limit = max(1, (int)$limit);
        return self::query("SELECT * FROM projects ORDER BY order_num ASC, created_at DESC LIMIT {$limit}");
    }

    public static function getAll(): array
    {
        return self::query("SELECT * FROM projects ORDER BY order_num ASC, created_at DESC");
    }
}
