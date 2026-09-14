<?php
namespace App\Models;

use App\Core\Model;

class ContactMessage extends Model
{
    public static function create(string $name, string $email, string $subject, string $message): bool
    {
        $sql = "INSERT INTO contact_messages (name, email, subject, message, status, created_at) 
                VALUES (?, ?, ?, ?, 'new', NOW())";
        return self::execute($sql, [$name, $email, $subject, $message]);
    }
}
