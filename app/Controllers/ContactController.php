<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index(): void
    {
        $form_flash = $_SESSION['contact_flash'] ?? null;
        unset($_SESSION['contact_flash']);

        $canonical = $this->request->getScheme() . '://' . $this->request->getHost() . $this->request->getBaseDir() . '/kontak';

        $this->render('contact/index', [
            'pageTitle' => 'Hubungi Kami & Konsultasi IT Bogor — Desadroid',
            'metaDescription' => 'Hubungi tim Desadroid di Bogor untuk konsultasi proyek pembuatan website, aplikasi mobile, maupun arsitektur sistem. Respons cepat dan ramah.',
            'metaKeywords' => 'kontak desadroid, it consultant bogor, jasa pembuatan web bogor, konsultasi it bogor, kantor desadroid bogor, whatsapp desadroid',
            'metaImage' => 'src/img/DESADROID.jpg',
            'canonical' => $canonical,
            'form_flash' => $form_flash
        ]);
    }

    public function sendMessage(): void
    {
        // 1. Only allow POST requests
        if (!$this->request->isPost()) {
            $this->back('#contact');
            return;
        }

        // 2. Honeypot Anti-Bot Trap
        if (!empty($this->request->post('website_hp'))) {
            // Silently reject bots
            $this->back('#contact', ['success' => '1']);
            return;
        }

        // 3. CSRF Protection
        $submittedCsrf = $this->request->post('csrf_token', '');
        $sessionCsrf = $_SESSION['csrf_token'] ?? '';
        if (empty($sessionCsrf) || empty($submittedCsrf) || !hash_equals($sessionCsrf, $submittedCsrf)) {
            $_SESSION['contact_flash'] = [
                'type' => 'error',
                'title' => 'Sesi Berakhir / Tidak Valid',
                'message' => 'Token keamanan tidak valid atau telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.'
            ];
            $this->back('#contact', ['error' => 'csrf']);
            return;
        }

        // 4. Time-gate check (real humans take at least 2 seconds)
        $formLoadTime = intval($this->request->post('form_load_time', 0));
        if ($formLoadTime > 0 && (time() - $formLoadTime < 2)) {
            $_SESSION['contact_flash'] = [
                'type' => 'error',
                'title' => 'Aktivitas Mencurigakan',
                'message' => 'Pengiriman terlalu cepat terdeteksi. Silakan coba kembali dengan wajar.'
            ];
            $this->back('#contact', ['error' => 'fast']);
            return;
        }

        // 5. Rate Limiting (Prevent spam flooding)
        $now = time();
        if (!isset($_SESSION['msg_history'])) {
            $_SESSION['msg_history'] = [];
        }
        $_SESSION['msg_history'] = array_filter($_SESSION['msg_history'], fn($t) => ($now - $t) < 600);
        if (count($_SESSION['msg_history']) >= 5) {
            $_SESSION['contact_flash'] = [
                'type' => 'error',
                'title' => 'Batas Pengiriman Tercapai',
                'message' => 'Anda telah mengirim terlalu banyak pesan dalam waktu singkat. Silakan tunggu 10 menit atau hubungi kami langsung via WhatsApp.'
            ];
            $this->back('#contact', ['error' => 'rate_limit']);
            return;
        }

        // 6. Sanitize and validate inputs
        $name = trim(strip_tags($this->request->post('name', '')));
        $email = trim(filter_var($this->request->post('email', ''), FILTER_SANITIZE_EMAIL));
        $phone = trim(strip_tags($this->request->post('phone', '')));
        $message = trim(strip_tags($this->request->post('message', '')));

        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['contact_flash'] = [
                'type' => 'error',
                'title' => 'Formulir Belum Lengkap',
                'message' => 'Harap lengkapi nama, email, dan pesan Anda sebelum mengirimkan formulir.'
            ];
            $this->back('#contact', ['error' => 'empty']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['contact_flash'] = [
                'type' => 'error',
                'title' => 'Format Email Tidak Valid',
                'message' => 'Harap masukkan alamat email yang benar agar kami dapat membalas pesan Anda.'
            ];
            $this->back('#contact', ['error' => 'invalid_email']);
            return;
        }

        // 7. Save to database
        $subject = 'Inquiry from ' . $name . (!empty($phone) ? ' (' . $phone . ')' : '');
        $saved = ContactMessage::create($name, $email, $subject, $message);

        // Record submission time for rate-limiting
        $_SESSION['msg_history'][] = $now;

        // 8. Attempt email notification via PHPMailer if vendor is loaded
        $this->sendEmailNotification($name, $email, $phone, $message);

        $_SESSION['contact_flash'] = [
            'type' => 'success',
            'title' => 'Pesan Berhasil Terkirim!',
            'message' => 'Terima kasih telah menghubungi Desadroid. Tim kami akan segera meninjau pesan Anda dan merespons dalam waktu 1x24 jam kerja.'
        ];

        // Regenerate CSRF token for next request
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $this->back('#contact', ['success' => '1']);
    }

    private function sendEmailNotification(string $name, string $email, string $phone, string $message): void
    {
        $vendorAutoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
        if (!file_exists($vendorAutoload)) {
            return;
        }

        require_once $vendorAutoload;
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return;
        }

        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(false);
            $mail->isMail();
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('noreply@desadroid.shop', 'Desadroid Website');
            $mail->addAddress('consulting@desadroid.shop', 'Desadroid Consulting');
            $mail->addReplyTo($email, $name);
            $mail->Subject = 'Pesan Baru dari Website Desadroid: ' . $name;
            $mail->isHTML(true);
            $mail->Body = "
                <h3>Pesan Baru dari Website Desadroid</h3>
                <p><strong>Nama:</strong> " . htmlspecialchars($name) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                <p><strong>Telepon/WhatsApp:</strong> " . htmlspecialchars($phone ?: '-') . "</p>
                <p><strong>Pesan:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
                <hr>
                <small>Terkirim otomatis dari sistem portal Desadroid.</small>
            ";
            @$mail->send();
        } catch (\Throwable $e) {
            error_log('Contact notification email error: ' . $e->getMessage());
        }
    }
}
