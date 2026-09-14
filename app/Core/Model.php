<?php
namespace App\Core;

use PDO;
use Exception;

abstract class Model
{
    protected static ?PDO $pdo = null;

    public static function setPdo(?PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    public static function getPdo(): ?PDO
    {
        if (self::$pdo === null) {
            global $pdo;
            if (isset($pdo) && $pdo instanceof PDO) {
                self::$pdo = $pdo;
            }
        }
        return self::$pdo;
    }

    protected static function query(string $sql, array $params = []): array
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return [];
        }

        try {
            if (empty($params)) {
                $stmt = $pdo->query($sql);
                return $stmt ? $stmt->fetchAll() : [];
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Model query error: ' . $e->getMessage() . ' in ' . $sql);
            return [];
        }
    }

    protected static function fetchOne(string $sql, array $params = []): ?array
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return null;
        }

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (Exception $e) {
            error_log('Model fetchOne error: ' . $e->getMessage() . ' in ' . $sql);
            return null;
        }
    }

    protected static function execute(string $sql, array $params = []): bool
    {
        $pdo = self::getPdo();
        if (!$pdo) {
            return false;
        }

        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log('Model execute error: ' . $e->getMessage() . ' in ' . $sql);
            return false;
        }
    }

    protected static function lastInsertId(): string
    {
        $pdo = self::getPdo();
        return $pdo ? $pdo->lastInsertId() : '0';
    }
}
