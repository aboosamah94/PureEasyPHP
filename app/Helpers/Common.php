<?php

function baseUrl($path = '')
{
    return \Config\Paths::baseUrl($path);
}

function generateCsrfToken()
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;

    return $token;
}

function pageNotFound()
{
    http_response_code(404);
    include (VIEW_FOLDER . '/errors/error_404.php');
    exit;
}

function isPageActive($page)
{
    $current_page = basename($_SERVER['REQUEST_URI']);
    return $current_page == $page ? 'active' : '';
}

function authLink()
{
    $authLink = \Config\APP::$authLink;
    return $authLink;
}

function adminLink()
{
    $adminLink = \Config\APP::$adminLink;
    return $adminLink;
}

function isLogin()
{
    $loginSession = $_SESSION['user_id'] ?? null;
    return $loginSession !== null;
}

function translate($key, $language = null)
{
    if (!isset($_SESSION['language'])) {
        $_SESSION['language'] = \Config\App::$defaultLanguage;
    }

    $language = $language ?? $_SESSION['language'];

    $langFilePath = APP_FOLDER . "/Languages/{$language}.php";
    if (!file_exists($langFilePath)) {
        $langFilePath = APP_FOLDER . '/Languages/en.php';
    }

    $translations = include ($langFilePath);

    return $translations['translations'][$key] ?? $key;
}

function langSetting($key, $language = null)
{
    if (!isset($_SESSION['language'])) {
        $_SESSION['language'] = \Config\App::$defaultLanguage;
    }

    $language = $language ?? $_SESSION['language'];

    $langFilePath = APP_FOLDER . "/Languages/{$language}.php";
    if (!file_exists($langFilePath)) {
        $langFilePath = APP_FOLDER . '/Languages/en.php';
    }

    $translations = include ($langFilePath);

    return $translations['settings'][$key] ?? null;
}


function currentLanguage()
{
    return $_SESSION['language'];
}
