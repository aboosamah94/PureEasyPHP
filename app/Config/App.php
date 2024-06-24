<?php

namespace Config;

class App
{
    public static string $adminLink = 'admin';
    public static string $authLink = 'auth';
    public static array $allowedLanguages = ['en', 'ar']; // like ['en', 'ar', 'fr']
    public static string $defaultLanguage = 'en';
    public static int $sessionExpiration = 3600;
    public static string $appTimezone = 'UTC';

    public static function initialize()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            date_default_timezone_set(self::$appTimezone);
            ini_set('session.gc_maxlifetime', self::$sessionExpiration);
            session_start();
        }

        self::loadHelpers();
    }

    protected static function loadHelpers()
    {
        Helper::load('Common');
    }
}
