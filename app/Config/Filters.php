<?php

namespace Config;

use Pureeasyphp\Paths;

class Filters
{
    public static function checkLogin()
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . Paths::baseUrl(App::$authLink));
            exit;
        }
    }

    public static function checkLoginDone()
    {
        if (self::isLoggedIn()) {
            header('Location: ' . Paths::baseUrl(App::$adminLink));
            exit;
        }
    }

    public static function checkCsrf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

            if (!self::validateCsrfToken($token)) {
                echo "CSRF Token is not valid!";
                exit;
            }
        }
    }

    private static function validateCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    private static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
