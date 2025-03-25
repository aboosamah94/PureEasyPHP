<?php

namespace Pureeasyphp;

use Config\Filters;
use Config\App;

class Router
{
    private static $basePath = null;

    // Get base path dynamically
    private static function getBasePath()
    {
        if (self::$basePath === null) {
            self::$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
        }
        return self::$basePath;
    }

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
            require_once(Paths::getAppFolderPath() . '/Config/Database.php');

            // Remove base path from route
            $basePath = self::getBasePath();
            $cleanRoute = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $route);
            $routeParts = array_filter(explode('/', trim($cleanRoute, '/')));

            // Define paths
            $mainPath = Paths::getViewFolderPath() . '/main/';
            $adminPath = Paths::getViewFolderPath() . '/admin/';
            $authPath = Paths::getViewFolderPath() . '/auth/';
            $noTemplatePath = Paths::getViewFolderPath() . '/no_template/';

            $baseFolderPath = self::determineBasePath($routeParts, $adminPath, $authPath, $mainPath);

            // Check for API
            if (App::$activeAPI && !empty($routeParts[0]) && $routeParts[0] === App::$apiLink) {
                require_once 'Api.php';
                exit();
            }

            // Check for admin, auth, or no-template routes
            if (!empty($routeParts[0])) {
                if ($routeParts[0] === App::$adminLink) {
                    Filters::checkLogin();
                } elseif ($routeParts[0] === App::$authLink) {
                    Filters::checkLoginDone();
                } elseif ($routeParts[0] === App::$noTemplate) {
                    $baseFolderPath = $noTemplatePath;
                }
            }

            $folderPath = '';
            $file = empty($routeParts) ? 'index' : '';

            foreach ($routeParts as $index => $part) {
                if ($index === 0 && in_array($part, [App::$adminLink, App::$authLink, App::$noTemplate])) {
                    continue;
                }

                if (!empty($part)) {
                    $potentialPath = $baseFolderPath . $folderPath . $part . '/';
                    if (is_dir($potentialPath)) {
                        $folderPath .= $part . '/';
                    } else {
                        $file = $part;
                    }
                }
            }

            $file = (is_dir($baseFolderPath . $folderPath) && empty($file)) ? 'index.php' : $file;
            $filePath = $baseFolderPath . $folderPath . $file . ((pathinfo($file, PATHINFO_EXTENSION) === '') ? '.php' : '');
            $indexPath = $baseFolderPath . 'index.php';

            if (is_file($filePath)) {
                ob_start();
                include_once $filePath;
                $content = ob_get_clean();

                if (!empty($routeParts[0]) && $routeParts[0] === App::$noTemplate) {
                    echo $content;
                } else {
                    $templatePath = $baseFolderPath . 'template.php';
                    if (is_file($templatePath)) {
                        include $templatePath;
                    } else {
                        throw new \Exception("Error: 'template.php' is missing in the folder: $baseFolderPath");
                    }
                }
            } elseif (!is_file($indexPath)) {
                throw new \Exception("Error: 'index.php' is missing in the folder: $baseFolderPath");
            } else {
                self::handle404Error();
            }

        } catch (\Exception $e) {
            self::handleException($e);
        }
    }

    private static function determineBasePath($routeParts, $adminPath, $authPath, $mainPath)
    {
        if (!empty($routeParts[0]) && in_array($routeParts[0], [App::$adminLink, App::$authLink])) {
            return ($routeParts[0] === App::$adminLink) ? $adminPath : $authPath;
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
        include(Paths::getViewPath('errors/error_500.php'));
        exit;
    }

}