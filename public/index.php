<?php

require __DIR__ . '/../vendor/autoload.php';

initializeEnvironment();
defineConstants();

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
    define('BASE_PATH', realpath(__DIR__ . '/..'));
    define('APP_FOLDER', realpath(BASE_PATH . '/app'));
    define('SRC_FOLDER', realpath(BASE_PATH . '/src'));
}

require rtrim(SRC_FOLDER, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

exit();