<?php
namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private string $path;
    private array $get;
    private array $post;
    private array $server;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        $this->uri = $this->server['REQUEST_URI'] ?? '/';
        $this->get = $_GET;
        $this->post = $_POST;
        $this->path = $this->resolvePath();
    }

    private function resolvePath(): string
    {
        $uri = parse_url($this->uri, PHP_URL_PATH) ?? '/';
        $scriptName = $this->server['SCRIPT_NAME'] ?? '';
        $baseDir = rtrim(dirname($scriptName), '/\\');
        
        // Remove base directory from path if running in a subdirectory (e.g. /desadroid/portofolio perusahaan)
        if (!empty($baseDir) && $baseDir !== '/' && strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }

        // Decode URL components for matching
        $uri = rawurldecode($uri);

        // Normalize leading and trailing slashes
        $uri = '/' . trim($uri, '/');
        if ($uri === '//' || empty($uri)) {
            $uri = '/';
        }

        return $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method === 'GET';
    }

    public function isAjax(): bool
    {
        return (!empty($this->server['HTTP_X_REQUESTED_WITH']) && 
            strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    public function getIp(): string
    {
        if (!empty($this->server['HTTP_CLIENT_IP'])) {
            return $this->server['HTTP_CLIENT_IP'];
        }
        if (!empty($this->server['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $this->server['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function getBaseDir(): string
    {
        $baseDir = rtrim(dirname($this->server['SCRIPT_NAME'] ?? ''), '/\\');
        return ($baseDir === '/' || $baseDir === '\\') ? '' : $baseDir;
    }

    public function getBaseDirUrl(): string
    {
        $baseDir = $this->getBaseDir();
        if (empty($baseDir)) return '';
        $segments = array_filter(explode('/', ltrim($baseDir, '/\\')), fn($s) => $s !== '');
        return '/' . implode('/', array_map('rawurlencode', $segments));
    }

    public function getScheme(): string
    {
        return (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ? 'https' : 'http';
    }

    public function getHost(): string
    {
        return $this->server['HTTP_HOST'] ?? 'localhost';
    }

    public function getFullUrl(): string
    {
        return $this->getScheme() . '://' . $this->getHost() . $this->uri;
    }
}
