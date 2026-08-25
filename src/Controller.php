<?php

namespace App\Core;

use RuntimeException;

class Controller
{
    private function __construct() {}

    public static function renderView(string $folder, array $data = [], string $viewsPath = 'views'): void
    {
        $viewPath = rtrim($viewsPath, '/') . '/' . trim($folder, '/') . '/index.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("Vue introuvable : {$viewPath}");
        }

        $viewData = $data;
        require $viewPath;
    }

    public static function redirectToRoute(string $uri, string $baseUrl = ''): never
    {
        $baseUrl = rtrim($baseUrl, '/');
        $uri = ltrim($uri, '/');
        $target = $baseUrl . ($uri !== '' ? '/' . $uri : '/');

        header("Location: {$target}");
        exit;
    }

    public static function renderViewLayout(string $folder, string $layout, array $data = [], string $viewsPath = 'views'): void
    {
        $viewPath = rtrim($viewsPath, '/') . '/' . trim($folder, '/') . '/index.php';
        $layoutPath = rtrim($viewsPath, '/') . '/layout/' . trim($layout, '/') . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("Vue introuvable : {$viewPath}");
        }

        if (!file_exists($layoutPath)) {
            throw new RuntimeException("Layout introuvable : {$layoutPath}");
        }

        $viewData = $data;

        ob_start();
        require $viewPath;
        $contentView = ob_get_clean();

        require $layoutPath;
    }
}