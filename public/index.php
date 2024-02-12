<?php

initializeEnvironment();
defineConstants();
requireFiles();
initializeApp();

function initializeEnvironment()
{
    session_start();
    checkPhpVersion('7.4');
}

function checkPhpVersion($minPhpVersion)
{
    if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
        throw new Exception(sprintf('Your PHP version must be %s or higher. Current version: %s', $minPhpVersion, PHP_VERSION));
    }
}

function defineConstants()
{
    $appFolderName = 'app';

    define('BASE_PATH', realpath(__DIR__ . '/..'));
    define('APP_FOLDER', realpath(BASE_PATH . '/' . $appFolderName));
}

function requireFiles()
{
    $requiredFiles = [
        '/config/App.php',
        '/config/Paths.php',
        '/config/Filters.php',
        '/config/Router.php',
        '/config/Helper.php',
    ];

    foreach ($requiredFiles as $file) {
        require_once APP_FOLDER . $file;
    }
}

function initializeApp()
{
    \Config\App::initialize();
    \Config\Paths::definePaths();
    \Config\Router::route();
}
