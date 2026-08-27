<?php

namespace Aicha\core;

class Request
{
    private function __construct() {}

    public static function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function isGet(): bool
    {
        return self::getMethod() === 'GET';
    }

    public static function isPost(): bool
    {
        return self::getMethod() === 'POST';
    }

    public static function get(
        string $key,
        mixed $default = null
    ): mixed {
        return $_GET[$key] ?? $default;
    }

    public static function post(
        string $key,
        mixed $default = null
    ): mixed {
        return $_POST[$key] ?? $default;
    }

    public static function all(): array
    {
        return match (self::getMethod()) {
            'GET' => $_GET,
            'POST' => $_POST,
            default => [],
        };
    }

    public static function uri(): string
    {
        return parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        );
    }
}
