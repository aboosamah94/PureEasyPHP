<?php

declare(strict_types=1);

define('MIN_PHP_VERSION', '7.4');

try {

    checkPhpVersion(MIN_PHP_VERSION);

    require __DIR__ . '/../vendor/autoload.php';

    defineConstants();

    // Locate and load the framework's bootstrap file
    $bootstrapFile = SYSTEM_FOLDER . DIRECTORY_SEPARATOR . 'bootstrap.php';
    if (!file_exists($bootstrapFile)) {
        throw new Exception('Bootstrap file not found: ' . $bootstrapFile);
    }
    require $bootstrapFile;

} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    die('Startup error: ' . $e->getMessage());
}

function checkPhpVersion(string $minPhpVersion): void
{
    if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
        throw new Exception(sprintf('Your PHP version must be %s or higher. Current version: %s', $minPhpVersion, PHP_VERSION));
    }
}

function defineConstants(): void
{
    define('BASE_PATH', realpath(__DIR__ . '/..')); // Root directory of the project
    define('PUBLIC_FOLDER', realpath(BASE_PATH . '/public')); // Public folder
    define('APP_FOLDER', realpath(BASE_PATH . '/app')); // Application folder
    define('SYSTEM_FOLDER', realpath(BASE_PATH . '/system')); // Framework system folder
}