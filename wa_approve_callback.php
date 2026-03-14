<?php
require_once __DIR__ . '/config/db.php';

define('FONNTE_TOKEN', 'TOKEN_KAMU');

// ambil data webhook
$data = $_POST;

// fallback jika bukan POST form
if (empty($data)) {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!$data) {
        parse_str($raw, $data);
    }
} else {
    $raw = json_encode($data);
}

// log webhook dipanggil
file_put_contents(
    __DIR__ . '/callback_status.log',
    date('c') . " - Webhook dipanggil\n",
    FILE_APPEND
);

// log debug data
file_put_contents(
    __DIR__ . '/callback_debug.log',
    date('c') . "\nRAW:\n" . $raw . "\nDATA:\n" . print_r($data, true) . "\n\n",
    FILE_APPEND
);

// ambil pesan
$message = strtolower(trim($data['message'] ?? ''));
$from    = $data['sender'] ?? '';

if (!$message || !$from) {
    echo "No message";
    exit;
}

// fungsi kirim WA
function sendWhatsApp($number, $message)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "target"  => $number,
            "message" => $message
        ],
        CURLOPT_HTTPHEADER => [
            "Authorization: " . FONNTE_TOKEN
        ]
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}


// cek command approve / reject
if (preg_match('/^(approve|reject)\s*(\d+)/', $message, $match)) {

    $action = $match[1];
    $article_id = intval($match[2]);

    // cek artikel
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id=?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if (!$article) {
        sendWhatsApp($from, "Artikel tidak ditemukan.");
        exit;
    }

    if ($action == "approve") {

        $stmt = $pdo->prepare(
            "UPDATE articles 
             SET status='published', published_date=NOW() 
             WHERE id=?"
        );
        $stmt->execute([$article_id]);

        $msg = "✅ Artikel ID $article_id berhasil dipublish.";

    } else {

        $stmt = $pdo->prepare(
            "UPDATE articles 
             SET status='draft' 
             WHERE id=?"
        );
        $stmt->execute([$article_id]);

        $msg = "❌ Artikel ID $article_id ditolak.";
    }

    // kirim balasan ke WA
    sendWhatsApp($from, $msg);

    echo "OK";
    exit;
}

echo "Command tidak dikenali.";