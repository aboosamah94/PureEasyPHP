<?php

namespace Pureeasyphp;

class Paths
{
    private static $baseUrl;

    public static function definePaths()
    {
        self::$baseUrl = self::calculateBaseUrl();
        define('VIEW_FOLDER', self::getViewFolderPath());
    }

    public static function getBasePath()
    {
        return realpath(BASE_PATH);
    }

    public static function getAppFolderPath()
    {
        return realpath(APP_FOLDER);
    }

    public static function getHelperFolderPath()
    {
        return realpath(self::getAppFolderPath() . '/Helpers');
    }

    public static function getViewPath($file)
    {
        return realpath(self::getAppFolderPath() . '/View/' . $file);
    }

    public static function getViewFolderPath()
    {
        return realpath(self::getAppFolderPath() . '/View');
    }

    private static function calculateBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['SERVER_PORT'] == '80' ? '' : (':' . $_SERVER['SERVER_PORT']);
        $script = $_SERVER['SCRIPT_NAME'];
        return rtrim("$protocol://$host$port", '/') . dirname($script);
    }

    public static function baseUrl($path = '')
    {
        return rtrim(self::$baseUrl, '/') . '/' . ltrim($path, '/');
    }

}