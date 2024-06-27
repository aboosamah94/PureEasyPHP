<?php

use Config\App;
use Pureeasyphp\Paths;
use Pureeasyphp\Router;

requireFiles();
initializeApp();

function requireFiles()
{
    $requiredFiles = [
        '/App.php',
        '/Filters.php',
    ];

    foreach ($requiredFiles as $file) {
        $filePath = APP_FOLDER . '/Config' . $file;
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            throw new Exception("Required configuration file not found: $filePath");
        }
    }
}

function initializeApp()
{
    App::initialize();
    Paths::definePaths();
    Router::route();
}