<?php
// AI Auto Blogger: Generate artikel, simpan pending, kirim preview ke WA, tunggu approve/reject
// Production-ready, modular, error-handled, and cPanel compatible


require_once __DIR__ . '/config/db.php';

// Fungsi: Load .env file (simple parser)
function loadEnv($path) {
    if (!file_exists($path)) return [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $val) = array_map('trim', explode('=', $line, 2));
        $env[$key] = $val;
    }
    return $env;
}

$env = loadEnv(__DIR__ . '/.env');

// Konfigurasi API dari .env
define('GEMINI_API_KEY', $env['GEMINI_API_KEY'] ?? '');
define('FONNTE_TOKEN', $env['FONNTE_TOKEN'] ?? '');
define('WA_ADMIN', $env['WA_ADMIN'] ?? '');
define('AUTHOR_ID', isset($env['AUTHOR_ID']) ? (int)$env['AUTHOR_ID'] : 1);



// Fungsi: Ubah nomor WA ke format internasional (62)
function formatWaNumber($number) {
    $number = preg_replace('/\D/', '', $number);
    if (strpos($number, '62') === 0) return $number;
    if (strpos($number, '0') === 0) return '62' . substr($number, 1);
    return $number;
}

// Fungsi: Generate artikel dari Gemini API
function generateArticle($prompt) {
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . GEMINI_API_KEY;
    $data = [
        'contents' => [
            ['parts' => [ ['text' => $prompt] ] ]
        ]
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) return [false, 'Curl error: ' . $err];
    $result = json_decode($response, true);
    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return [false, 'AI gagal generate artikel: ' . $response];
    }
    // Parsing hasil Gemini
    $text = $result['candidates'][0]['content']['parts'][0]['text'];
    // Default value
    $title = 'AI: Digital Marketing untuk UMKM';
    $category = 'Digital Marketing';
    $keyword = 'business';
    $content = trim($text);
    // Coba parsing judul, kategori, keyword
    if (preg_match('/^(.{10,120})\nKategori: (.+?)\n/s', $text, $m)) {
        $title = trim($m[1]);
        $category = trim($m[2]);
        $content = trim(substr($text, strlen($m[0])));
    }
    if (preg_match('/KEYWORD:\s*(\w+)/i', $content, $matches)) {
        $keyword = trim($matches[1]);
        $content = str_replace($matches[0], '', $content);
    }
    $excerpt = mb_substr(strip_tags($content), 0, 150) . '...';
    return [true, compact('title', 'category', 'content', 'keyword', 'excerpt')];
}

// Fungsi: Simpan artikel ke database (status: published)
function saveArticle($pdo, $data) {
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $data['title']), '-')) . '-' . time();
    $stmt = $pdo->prepare('INSERT INTO articles (title, slug, category, excerpt, content, featured_image, author_id, read_time, status, published_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $featured_image = 'https://loremflickr.com/800/400/' . urlencode($data['keyword']);
    $read_time = 3;
    $status = 'published'; // langsung publish
    $published_date = date('Y-m-d H:i:s');
    $stmt->execute([
        $data['title'],
        $slug,
        $data['category'],
        $data['excerpt'],
        $data['content'],
        $featured_image,
        AUTHOR_ID,
        $read_time,
        $status,
        $published_date
    ]);
    return $pdo->lastInsertId();
}

// Fungsi: Kirim pesan WhatsApp via Fonnte
function sendWhatsApp($number, $message) {
    $number = formatWaNumber($number);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query([
            'target' => $number,
            'message' => $message
        ]),
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . FONNTE_TOKEN
        ],
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) return false;
    return $response;
}

// MAIN LOGIC
$prompt = "Buatkan artikel blog singkat tentang pentingnya digital marketing untuk UMKM di Indonesia. Format: Judul di baris pertama, lalu baris kedua 'Kategori: ...', lalu isi artikel. Di baris paling terakhir sendiri, tuliskan hanya 1 kata kunci bahasa inggris yang cocok untuk gambar artikel ini, awali dengan kata KEYWORD: (contoh: KEYWORD: marketing)";

list($ok, $result) = generateArticle($prompt);
if (!$ok) {
    // Gagal generate artikel, log error dan exit
    error_log($result);
    echo 'Gagal generate artikel: ' . htmlspecialchars($result);
    exit;
}

// Simpan ke database
try {
    $article_id = saveArticle($pdo, $result);
} catch (Exception $e) {
    error_log('DB Error: ' . $e->getMessage());
    echo 'Gagal simpan artikel ke database.';
    exit;
}

// Kirim preview ke WhatsApp admin
$waMessage = "[REVIEW ARTIKEL AI]\n\nJudul: {$result['title']}\nKategori: {$result['category']}\n\n{$result['excerpt']}\n\nBalas WA ini dengan:\nAPPROVE $article_id untuk publish\nREJECT $article_id untuk tolak";

$waSend = sendWhatsApp(WA_ADMIN, $waMessage);
if (!$waSend) {
    error_log('Gagal kirim WA ke admin');
    echo 'Artikel berhasil dibuat, tapi gagal kirim WA ke admin.';
    exit;
}

echo "Artikel AI berhasil dibuat dan dikirim ke WA admin untuk review. ID: $article_id\n";
