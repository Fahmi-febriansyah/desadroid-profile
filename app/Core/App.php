<?php
namespace App\Core;

class App
{
    private static ?App $instance = null;
    private Request $request;
    private Response $response;
    private Router $router;

    public function __construct()
    {
        self::$instance = $this;

        // Session initialization
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize Request, Response, Router
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function registerAutoloader(): void
    {
        spl_autoload_register(function ($class) {
            $prefix = 'App\\';
            $baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function run(): void
    {
        $this->router->resolve();
    }
}
