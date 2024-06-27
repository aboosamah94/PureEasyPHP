<?php

namespace Pureeasyphp;

use Config\Filters;
use Config\App;

class Router
{
    public static function route()
    {
        try {
            // Filter for CSRF protection - if in app true
            if (App::$activeCSRF) {
                Filters::checkCsrf();
            }

            // Get and validate the route from the request URI
            $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $route = self::validateRoute($route);

            // Load the appropriate content based on the route
            self::loadContent($route);
        } catch (\Exception $e) {
            self::handleException($e);
        }
    }

    private static function validateRoute($route)
    {
        $allowedCharacters = '/^[a-zA-Z0-9\/\-_]+$/';
        if (!preg_match($allowedCharacters, $route)) {
            throw new \Exception('Invalid characters in the route.');
        }

        return $route;
    }

    private static function loadContent($route)
    {
        try {
            // Load Database configuration
            require_once (Paths::getAppFolderPath() . '/Config/Database.php');

            $basePath = str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_NAME']));
            $path = str_replace($basePath, '', $route);
            $routeParts = explode('/', $path);

            // Define paths
            $mainPath = Paths::getViewFolderPath() . '/main/';
            $adminPath = Paths::getViewFolderPath() . '/admin/';
            $authPath = Paths::getViewFolderPath() . '/auth/';

            // Determine the base path based on the route
            $basePath = self::determineBasePath($routeParts, $adminPath, $authPath, $mainPath);

            // Check for Api
            if (App::$activeAPI) {
                if (isset($routeParts[1]) && $routeParts[1] === App::$apiLink) {
                    require_once 'Api.php';
                    exit();
                }
            }

            // Check for admin or auth routes
            if (isset($routeParts[1]) && $routeParts[1] === App::$adminLink) {
                Filters::checkLogin();
            }

            if (isset($routeParts[1]) && $routeParts[1] === App::$authLink) {
                Filters::checkLoginDone();
            }

            $folderPath = '';
            $file = (count($routeParts) <= 1) ? 'index' : '';

            // Traverse the route parts to find the correct file path
            foreach ($routeParts as $index => $part) {
                if ($index === 1 && in_array($part, [App::$adminLink, App::$authLink])) {
                    continue;
                }

                if (!empty($part)) {
                    $potentialPath = $basePath . $folderPath . $part . '/';
                    if (is_dir($potentialPath)) {
                        $folderPath .= $part . '/';
                    } else {
                        $file = $part;
                    }
                }
            }

            // Determine the file path to include
            $file = (is_dir($basePath . $folderPath) && empty($file)) ? 'index.php' : $file;
            $filePath = $basePath . $folderPath . $file . ((pathinfo($file, PATHINFO_EXTENSION) === '') ? '.php' : '');
            $indexPath = $basePath . 'index.php';

            // Include the content file or handle errors
            if (is_file($filePath)) {
                ob_start();
                include_once $filePath;
                $content = ob_get_clean();
                $templatePath = $basePath . 'template.php';

                if (is_file($templatePath)) {
                    include $templatePath;
                } else {
                    throw new \Exception("Error: 'template.php' is missing in the folder: $basePath");
                }
            } elseif (!is_file($indexPath)) {
                throw new \Exception("Error: 'index.php' is missing in the folder: $basePath");
            } else {
                self::handle404Error();
            }

        } catch (\Exception $e) {
            self::handleException($e);
        }
    }

    private static function determineBasePath($routeParts, $adminPath, $authPath, $mainPath)
    {
        if (isset($routeParts[1]) && in_array($routeParts[1], [App::$adminLink, App::$authLink])) {
            return ($routeParts[1] === App::$adminLink) ? $adminPath : $authPath;
        }

        return $mainPath;
    }

    private static function handle404Error()
    {
        pageNotFound();
    }

    private static function handleException(\Exception $e)
    {
        error_log('Exception: ' . $e->getMessage());
        http_response_code(500);
        include (Paths::getViewPath('errors/error_500.php'));
        exit;
    }
}