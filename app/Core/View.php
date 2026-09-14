<?php
namespace App\Core;

class View
{
    private static string $viewsPath = '';

    public static function init(): void
    {
        self::$viewsPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views';
    }

    public static function render(string $view, array $data = [], string $layout = 'main'): string
    {
        if (empty(self::$viewsPath)) {
            self::init();
        }

        // Setup base directory variables
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = rtrim(dirname($scriptName), '/\\');
        if ($baseDir === '/' || $baseDir === '\\') $baseDir = '';
        
        $baseDirSegments = array_filter(explode('/', ltrim($baseDir, '/\\')), fn($s) => $s !== '');
        $baseDirUrl = empty($baseDirSegments) ? '' : '/' . implode('/', array_map('rawurlencode', $baseDirSegments));

        // Setup CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrfToken = $_SESSION['csrf_token'];

        // Add standard view helpers to data
        $data['baseDir'] = $baseDir;
        $data['baseDirUrl'] = $baseDirUrl;
        $data['csrf_token'] = $csrfToken;

        // Render the view file first to capture $content
        $viewFile = self::$viewsPath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $view) . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }

        // Extract data for view
        extract($data, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If no layout requested, return content directly
        if (!$layout) {
            return $content;
        }

        // Render layout wrapping the content
        $layoutFile = self::$viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';
        if (!file_exists($layoutFile)) {
            return $content;
        }

        ob_start();
        include $layoutFile;
        return ob_get_clean();
    }
}
