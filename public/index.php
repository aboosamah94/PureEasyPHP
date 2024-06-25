<?php

require __DIR__ . '/../vendor/autoload.php';

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
    define('CONFIG_FOLDER', APP_FOLDER . '/Config');
}

function requireFiles()
{
    $requiredFiles = [
        '/App.php',
        '/Filters.php',
    ];

    foreach ($requiredFiles as $file) {
        $filePath = CONFIG_FOLDER . $file;
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            throw new Exception("Required configuration file not found: $filePath");
        }
    }
}

function initializeApp()
{
    \Config\App::initialize();
    Pureeasyphp\Paths::definePaths();
    Pureeasyphp\Router::route();
}
