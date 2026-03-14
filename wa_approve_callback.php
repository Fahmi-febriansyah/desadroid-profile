<?php
require_once __DIR__ . '/config/db.php';

define('FONNTE_TOKEN', 'TOKEN_KAMU');

// ambil raw input dari webhook
$input = file_get_contents("php://input");

// parse data
$data = json_decode($input, true);
if (!$data) {
    parse_str($input, $data);
}

// log webhook
file_put_contents(__DIR__ . '/callback_status.log',
date('c')." - Webhook dipanggil\n", FILE_APPEND);

file_put_contents(__DIR__ . '/callback_debug.log',
date('c')."\nRAW:\n".$input."\nDATA:\n".print_r($data,true)."\n\n",
FILE_APPEND);

// ambil pesan
$message = strtolower(trim($data['message'] ?? ''));
$from = $data['sender'] ?? '';

if (!$message) {
    exit("No message");
}

// fungsi kirim WA
function sendWhatsApp($number,$message){

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            "target"=>$number,
            "message"=>$message
        ],
        CURLOPT_HTTPHEADER => [
            "Authorization: ".FONNTE_TOKEN
        ]
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}


// cek command approve / reject
if(preg_match('/^(approve|reject)\s*(\d+)/',$message,$match)){

    $action = $match[1];
    $article_id = intval($match[2]);

    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id=?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();

    if(!$article){
        sendWhatsApp($from,"Artikel tidak ditemukan");
        exit;
    }

    if($action=="approve"){

        $stmt = $pdo->prepare(
        "UPDATE articles SET status='published',published_date=NOW() WHERE id=?");
        $stmt->execute([$article_id]);

        sendWhatsApp($from,"Artikel $article_id berhasil dipublish");

    }else{

        $stmt = $pdo->prepare(
        "UPDATE articles SET status='draft' WHERE id=?");
        $stmt->execute([$article_id]);

        sendWhatsApp($from,"Artikel $article_id ditolak");

    }

    echo "OK";
    exit;
}

echo "command tidak dikenali";