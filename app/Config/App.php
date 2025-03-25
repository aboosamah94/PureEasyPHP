<?php

namespace Config;

class App
{
    public static string $adminLink = 'admin';
    public static string $authLink = 'auth';
    public static string $noTemplate = 'no_t'; // No template file included
    public static bool $activeAPI = true; // true or false
    public static string $apiLink = 'api';
    public static array $allowedLanguages = ['en', 'ar']; // like ['en', 'ar', 'fr']
    public static string $defaultLanguage = 'en';
    public static int $sessionExpiration = 3600;
    public static string $appTimezone = 'UTC';
    public static bool $activeCSRF = true; // true or false
    protected static array $helpers = ['Common']; // Load helper for all project files - like ['Common', 'form']

    public static function initialize()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            date_default_timezone_set(self::$appTimezone);
            ini_set('session.gc_maxlifetime', self::$sessionExpiration);
            session_start();
        }

        \Pureeasyphp\Helper::getHelpers(self::$helpers);
    }
}
