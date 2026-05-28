<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

$to = trim($data['to'] ?? '');
$subject = trim($data['subject'] ?? '');
$message = trim($data['message'] ?? '');

if (!$to || !$subject || !$message) {
    echo json_encode(['status' => 'error', 'message' => 'Semua kolom (Tujuan, Subjek, Pesan) wajib diisi.']);
    exit;
}

$sent = false;
$error_msg = '';

$fromEmail = 'admin@desadroid.shop';
$fromName = 'Desadroid Admin';

// Try PHPMailer if vendor exists
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isMail();
        $sent = $mail->send();
    } catch (Exception $e) {
        $sent = false;
        $error_msg = $e->getMessage();
    }
} else {
    // Fallback to basic mail()
    $headers = "From: {$fromName} <{$fromEmail}>\r\nReply-To: {$fromEmail}\r\n";
    $sent = mail($to, $subject, $message, $headers);
}

if ($sent) {
    echo json_encode(['status' => 'success', 'message' => 'Email berhasil dikirim ke ' . $to]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim email: ' . $error_msg]);
}
?>
