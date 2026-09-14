<?php
/**
 * Desadroid — Front Controller (MVC Entry Point)
 * 
 * All public web requests are routed through this file.
 */

declare(strict_types=1);

// 1. Load Database Configuration
require_once __DIR__ . '/config/db.php';

// 2. Register Application PSR-4 Autoloader
require_once __DIR__ . '/app/Core/App.php';
\App\Core\App::registerAutoloader();

// 3. Inject Database Connection into Base Model
if (isset($pdo)) {
    \App\Core\Model::setPdo($pdo);
}

// 4. Initialize Core Application & Router
$app = \App\Core\App::getInstance();
$router = $app->getRouter();

// 5. Register Public Routes
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ServiceController;
use App\Controllers\ArticleController;
use App\Controllers\ContactController;
use App\Controllers\PrivacyController;
use App\Controllers\ErrorController;

// Homepage
$router->get('/', [HomeController::class, 'index']);
$router->get('/index.php', function($req, $res) {
    $res->redirect(($req->getBaseDirUrl() ?: '') . '/', 301);
});

// Tentang Kami (About)
$router->get('/tentang', [AboutController::class, 'index']);
$router->get('/tentang.php', function($req, $res) {
    $res->redirect(($req->getBaseDirUrl() ?: '') . '/tentang', 301);
});

// Layanan (Services)
$router->get('/layanan', [ServiceController::class, 'index']);
$router->get('/layanan.php', function($req, $res) {
    $res->redirect(($req->getBaseDirUrl() ?: '') . '/layanan', 301);
});

// Proyek (Redirect to Subdomain)
$router->get('/proyek', function($req, $res) {
    $res->redirect('https://project.desadroid.shop', 301);
});
$router->get('/proyek.php', function($req, $res) {
    $res->redirect('https://project.desadroid.shop', 301);
});

// Artikel & Blog
$router->get('/artikel', [ArticleController::class, 'index']);
$router->get('/artikel.php', function($req, $res) {
    $res->redirect(($req->getBaseDirUrl() ?: '') . '/artikel', 301);
});
$router->get('/artikel/{slug}', [ArticleController::class, 'detail']);

// Kontak (Contact)
$router->get('/kontak', [ContactController::class, 'index']);
$router->get('/kontak.php', function($req, $res) {
    $res->redirect(($req->getBaseDirUrl() ?: '') . '/kontak', 301);
});
$router->post('/kontak', [ContactController::class, 'sendMessage']);

// Contact Form Submissions (Supporting both clean and legacy POST targets)
$router->post('/send-message', [ContactController::class, 'sendMessage']);
$router->post('/send_message', [ContactController::class, 'sendMessage']);
$router->post('/send_message.php', [ContactController::class, 'sendMessage']);

// Kebijakan Privasi (Privacy Policy)
$router->get('/privacy', [PrivacyController::class, 'index']);
$router->get('/privacy.php', [PrivacyController::class, 'index']);
$router->get('/kebijakan-privasi', [PrivacyController::class, 'index']);

// Error Routes
$router->get('/404', [ErrorController::class, 'notFound']);
$router->get('/404.php', [ErrorController::class, 'notFound']);
$router->get('/403', [ErrorController::class, 'forbidden']);
$router->get('/403.php', [ErrorController::class, 'forbidden']);
$router->get('/500', [ErrorController::class, 'serverError']);
$router->get('/500.php', [ErrorController::class, 'serverError']);

// 6. Execute Router Dispatch
$app->run();
