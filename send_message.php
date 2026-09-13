<?php
// Handler for public contact forms: saves message to DB and sends email via PHPMailer if available
require_once __DIR__ . '/config/db.php';

// Helper to redirect back with clean anchor and flash message
function back($anchor = '#contact', $params = []){
    $ref = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    // Strip previous query params from referer if redirecting to avoid query string accumulation
    $parts = explode('?', $ref);
    $cleanRef = $parts[0];
    $url = $cleanRef . (strpos($cleanRef, '#') === false ? $anchor : '');
    if ($params) {
        $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

// 1. Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back('#contact');
}

// 2. Honeypot Anti-Bot Trap (hidden field 'website_hp')
if (!empty($_POST['website_hp'])) {
    // Silently reject bots without notifying them
    back('#contact', ['success' => '1']);
}

// 3. CSRF Protection
$submitted_csrf = $_POST['csrf_token'] ?? '';
$session_csrf = $_SESSION['csrf_token'] ?? '';

if (empty($session_csrf) || empty($submitted_csrf) || !hash_equals($session_csrf, $submitted_csrf)) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Sesi Berakhir / Tidak Valid',
        'message' => 'Token keamanan tidak valid atau telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.'
    ];
    back('#contact', ['error' => 'csrf']);
}

// 4. Time-gate check (Bots submit within milliseconds, real humans take at least 2 seconds)
$form_load_time = intval($_POST['form_load_time'] ?? 0);
if ($form_load_time > 0 && (time() - $form_load_time < 2)) {
    // Submitted too quickly, likely an automated bot
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Aktivitas Mencurigakan',
        'message' => 'Pengiriman terlalu cepat terdeteksi. Silakan coba kembali dengan wajar.'
    ];
    back('#contact', ['error' => 'fast']);
}

// 5. Rate Limiting (Prevent spam flooding / DoS)
$now = time();
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (!isset($_SESSION['msg_history'])) {
    $_SESSION['msg_history'] = [];
}
// Clean up history older than 10 minutes (600 seconds)
$_SESSION['msg_history'] = array_filter($_SESSION['msg_history'], function($t) use ($now) {
    return ($now - $t) < 600;
});

// Maximum 3 submissions per 10 minutes
if (count($_SESSION['msg_history']) >= 3) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Batas Pengiriman Tercapai',
        'message' => 'Anda telah mengirim beberapa pesan. Untuk mencegah spam, mohon tunggu beberapa menit sebelum mengirim pesan baru.'
    ];
    back('#contact', ['error' => 'rate_limit']);
}

// Minimum 8 seconds cooldown between submissions
if (!empty($_SESSION['last_msg_time']) && ($now - $_SESSION['last_msg_time'] < 8)) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Harap Tunggu',
        'message' => 'Mohon tunggu beberapa detik sebelum mengirim pesan kembali.'
    ];
    back('#contact', ['error' => 'cooldown']);
}

// 6. Input Extraction & Sanitization
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// Strip carriage returns and line feeds to prevent Email Header / CRLF Injection
$name = str_replace(["\r", "\n", "%0a", "%0d"], ' ', $name);
$email = str_replace(["\r", "\n", "%0a", "%0d"], '', $email);
$phone = str_replace(["\r", "\n", "%0a", "%0d"], '', $phone);

// 7. Strict Validation
if (empty($name) || empty($email) || empty($message)) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Formulir Belum Lengkap',
        'message' => 'Nama lengkap, alamat email, dan pesan wajib diisi.'
    ];
    back('#contact', ['error' => 'missing']);
}

if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Nama Tidak Valid',
        'message' => 'Nama harus terdiri dari 2 hingga 100 karakter.'
    ];
    back('#contact', ['error' => 'invalid_name']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Email Tidak Valid',
        'message' => 'Silakan masukkan format alamat email yang benar dan valid.'
    ];
    back('#contact', ['error' => 'invalid_email']);
}

if (!empty($phone) && (!preg_match('/^[0-9+\s\-()]{6,25}$/', $phone) || mb_strlen($phone) > 25)) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Nomor Telepon Tidak Valid',
        'message' => 'Format nomor telepon tidak valid. Gunakan angka, simbol plus, atau tanda hubung.'
    ];
    back('#contact', ['error' => 'invalid_phone']);
}

if (mb_strlen($message) < 5 || mb_strlen($message) > 3000) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Pesan Terlalu Pendek / Panjang',
        'message' => 'Pesan harus berisi minimal 5 karakter dan maksimal 3000 karakter.'
    ];
    back('#contact', ['error' => 'invalid_message']);
}

// 8. Store in Database using Prepared Statement
try {
    $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message, phone, status, created_at) VALUES (?, ?, ?, ?, "new", NOW())');
    $stmt->execute([$name, $email, $message, $phone]);
} catch (Exception $e) {
    $_SESSION['contact_flash'] = [
        'type' => 'error',
        'title' => 'Gangguan Sistem',
        'message' => 'Gagal menyimpan pesan ke database. Silakan hubungi kami via WhatsApp.'
    ];
    back('#contact', ['error' => 'db']);
}

// Record successful rate limiting timestamp
$_SESSION['last_msg_time'] = $now;
$_SESSION['msg_history'][] = $now;

// Regenerate CSRF token after successful submission
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// 9. Send Email via PHPMailer or mail()
$sent = false;
$error_msg = '';

$emailBody = "Halo Tim Desadroid,\n\nAda pesan baru yang masuk melalui formulir kontak website:\n\n";
$emailBody .= "--------------------------------------------------\n";
$emailBody .= "Nama     : " . $name . "\n";
$emailBody .= "Email    : " . $email . "\n";
$emailBody .= "Telepon  : " . ($phone ? $phone : '-') . "\n";
$emailBody .= "Waktu    : " . date('d F Y, H:i') . " WIB\n";
$emailBody .= "IP       : " . $ip . "\n";
$emailBody .= "--------------------------------------------------\n\n";
$emailBody .= "Pesan:\n" . $message . "\n\n";
$emailBody .= "--------------------------------------------------\n";

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('noreply@desadroid.shop', 'Website Desadroid');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('consulting@desadroid.shop', 'Desadroid Consultant');
        $mail->Subject = 'Pesan Baru dari Website: ' . $name;
        $mail->Body = $emailBody;
        $mail->isMail();
        $sent = $mail->send();
    } catch (Exception $e) {
        $sent = false;
        $error_msg = $e->getMessage();
    }
} else {
    $to = 'consulting@desadroid.shop';
    $subject = 'Pesan Baru dari Website: ' . $name;
    $headers = "From: Website Desadroid <noreply@desadroid.shop>\r\n";
    $headers .= "Reply-To: {$name} <{$email}>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $sent = @mail($to, $subject, $emailBody, $headers);
}

// 10. User Feedback Response
// Note: Even if local mail() fails (common on local XAMPP without sendmail), message is securely saved in DB
$_SESSION['contact_flash'] = [
    'type' => 'success',
    'title' => 'Pesan Berhasil Terkirim!',
    'message' => 'Terima kasih telah menghubungi Desadroid. Tim kami akan segera meninjau pesan Anda dan merespons via email/WhatsApp.'
];

back('#contact', ['success' => '1']);

