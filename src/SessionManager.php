<?php

namespace App\Core;

class SessionManager
{
   
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

   

    public static function initSession(): void
    {
        self::startSession();
    }

  

    public static function getSession(string $key, mixed $default = null): mixed
    {
        self::startSession();
        return $_SESSION[$key] ?? $default;
    }

   

    public static function setSession(string $key, mixed $value): mixed
    {
        self::startSession();
        return $_SESSION[$key] = $value;
    }


    public static function hasSession(string $key): bool
    {
        self::startSession();
        return isset($_SESSION[$key]);
    }

    public static function unsetSession(string $key): void
    {
        self::startSession();
        unset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::unsetSession($key);
    }

    public static function destroySession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

   
    public static function all(): array
    {
        self::startSession();
        return $_SESSION ?? [];
    }


    public static function clear(): void
    {
        self::startSession();
        $_SESSION = [];
    }
}