<?php
namespace App\Models;

use App\Core\Model;

class Service extends Model
{
    public static function getAll(): array
    {
        return self::query("SELECT * FROM services ORDER BY id ASC");
    }

    public static function getFeatured(int $limit = 6): array
    {
        $limit = max(1, (int)$limit);
        return self::query("SELECT * FROM services ORDER BY id ASC LIMIT {$limit}");
    }

    public static function getDefaultFeatures(): array
    {
        return [
            ['Desain Responsif & Modern', 'SEO On-Page & Kecepatan Tinggi', 'Panel Admin / CMS Kustom'],
            ['Android & iOS (Flutter / Native)', 'UI Interaktif & Performa Mulus', 'Integrasi API & Push Notifikasi'],
            ['Riset UX & User Persona', 'Figma Design System Komprehensif', 'Prototipe Interaktif Siap Uji'],
            ['RESTful API Berkeamanan Ketat', 'Database Scalable & Teroptimasi', 'Proteksi CSRF, XSS & SQL Injection'],
            ['Integrasi Payment Gateway Aman', 'Sistem Order & Manajemen Stok', 'Fitur Diskon, Voucher & Laporan'],
            ['Audit Sistem & Analisis Keamanan', 'Roadmap Arsitektur Teknologi', 'Rekomendasi Efisiensi Server Cloud']
        ];
    }
}
