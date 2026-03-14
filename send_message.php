<?php
// Handler for public contact forms: saves message to DB and sends email via PHPMailer if available
require_once __DIR__ . '/config/db.php';

// Simple helper to redirect back
function back($anchor = '#contact', $params = []){
    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    $url = $ref . (strpos($ref, '#') === false ? $anchor : '');
    if ($params) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back('#contact');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    back('#contact', ['error' => 'missing']);
}

try {
    // Insert into DB
    $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message, phone) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $message, $phone]);
} catch (Exception $e) {
    back('#contact', ['error' => 'db']);
}

$sent = false;
$error_msg = '';

// Try to use PHPMailer if available via Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        // Use mail() transport by default; if user configures SMTP, they can modify below
        $mail->setFrom($email, $name);
        $mail->addAddress('consulting@desadroid.shop', 'Desadroid');
        $mail->Subject = 'Pesan dari website: ' . $name;
        $body = "Nama: {$name}\nEmail: {$email}\nTelepon: {$phone}\n\nPesan:\n{$message}";
        $mail->Body = $body;
        $mail->isMail();
        $sent = $mail->send();
    } catch (Exception $e) {
        $sent = false;
        $error_msg = $e->getMessage();
    }
} else {
    // Fallback to PHP mail()
    $to = 'consulting@desadroid.shop';
    $subject = 'Pesan dari website: ' . $name;
    $headers = "From: {$name} <{$email}>\r\nReply-To: {$email}\r\n";
    $body = "Nama: {$name}\nEmail: {$email}\nTelepon: {$phone}\n\nPesan:\n{$message}";
    $sent = mail($to, $subject, $body, $headers);
}

if ($sent) {
    // Set session for SweetAlert success
    session_start();
    $_SESSION['swal'] = [
        'type' => 'success',
        'title' => 'Pesan Terkirim!',
        'text' => 'Terima kasih, pesan Anda berhasil dikirim. Kami akan segera menghubungi Anda.'
    ];
    back('#contact', ['success' => '1']);
} else {
    // Set session for SweetAlert error
    session_start();
    $_SESSION['swal'] = [
        'type' => 'error',
        'title' => 'Gagal Mengirim Pesan',
        'text' => 'Maaf, terjadi kesalahan saat mengirim pesan. Silakan coba lagi.'
    ];
    back('#contact', ['error' => 'mail', 'msg' => $error_msg]);
}
