<?php
namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $html = View::render($view, $data, $layout);
        $this->response->setContent($html);
        $this->response->send();
    }

    protected function view(string $view, array $data = []): void
    {
        $html = View::render($view, $data, '');
        $this->response->setContent($html);
        $this->response->send();
    }

    protected function json($data, int $statusCode = 200): void
    {
        $this->response->json($data, $statusCode);
    }

    protected function redirect(string $url, int $statusCode = 302): void
    {
        $this->response->redirect($url, $statusCode);
    }

    protected function back(string $anchor = '', array $params = []): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? ($this->request->getBaseDir() ?: '/');
        $parts = explode('?', $ref);
        $cleanRef = $parts[0];
        
        $url = $cleanRef;
        if ($anchor && strpos($cleanRef, '#') === false) {
            $url .= (strpos($anchor, '#') === 0 ? '' : '#') . $anchor;
        }
        
        if (!empty($params)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
        }

        $this->redirect($url);
    }

    protected function setFlash(string $key, $value): void
    {
        $_SESSION['flash_' . $key] = $value;
    }

    protected function getFlash(string $key)
    {
        $sessionKey = 'flash_' . $key;
        if (isset($_SESSION[$sessionKey])) {
            $value = $_SESSION[$sessionKey];
            unset($_SESSION[$sessionKey]);
            return $value;
        }
        return null;
    }
}
