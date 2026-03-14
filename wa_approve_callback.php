<?php
// Endpoint untuk menerima callback Fonnte (balasan WA admin)
// Pastikan endpoint ini diatur di dashboard Fonnte sebagai webhook/callback
require_once __DIR__ . '/config/db.php';

// Konfigurasi API
define('FONNTE_TOKEN', 'yTQYghDMCByYnK18nz5k');

// Fungsi: Ubah nomor WA ke format internasional (62)
function formatWaNumber($number) {
    $number = preg_replace('/\D/', '', $number);
    if (strpos($number, '62') === 0) return $number;
    if (strpos($number, '0') === 0) return '62' . substr($number, 1);
    return $number;
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

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Ambil data dari Fonnte (cek dokumentasi Fonnte untuk format pasti)
$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!$data) {
    parse_str($input, $data); // fallback jika x-www-form-urlencoded
}

// Cek pesan WA
$message = strtolower(trim($data['message'] ?? ''));
$from = $data['number'] ?? '';

if (preg_match('/^(approve|reject)\s*(\d+)/i', $message, $matches)) {
    $action = strtolower($matches[1]);
    $article_id = intval($matches[2]);

    // Cek artikel
    $stmt = $pdo->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();
    if (!$article) {
        sendWhatsApp($from, 'Artikel tidak ditemukan.');
        exit('Artikel tidak ditemukan');
    }

    if ($action === 'approve') {
        // Publish artikel
        $stmt = $pdo->prepare('UPDATE articles SET status = ?, published_date = ? WHERE id = ?');
        $stmt->execute(['published', date('Y-m-d H:i:s'), $article_id]);
        $resultMsg = "Artikel ID $article_id berhasil dipublish.";
    } else {
        // Tolak artikel
        $stmt = $pdo->prepare('UPDATE articles SET status = ? WHERE id = ?');
        $stmt->execute(['draft', $article_id]);
        $resultMsg = "Artikel ID $article_id ditolak dan diubah ke draft.";
    }
    // Balas ke admin
    sendWhatsApp($from, $resultMsg);
    echo $resultMsg;
    exit;
}

echo 'Format pesan tidak dikenali.';
