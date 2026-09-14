<?php
namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function redirect(string $url, int $code = 302): void
    {
        http_response_code($code);
        header('Location: ' . $url);
        exit;
    }

    public function json($data, int $code = 200): void
    {
        $this->setStatusCode($code);
        $this->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->setContent(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $this->send();
        exit;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
        echo $this->content;
    }
}
