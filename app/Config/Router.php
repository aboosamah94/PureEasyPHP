<?php

namespace Config;

class Router
{
    public static function route()
    {
        try {
            // Filters for Csrf
            Filters::checkCsrf();

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
            // Call for Databse
            require_once(Paths::getAppFolderPath() . '/config/Database.php');

            $basePath = str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_NAME']));
            $path = str_replace($basePath, '', $route);
            $routeParts = explode('/', $path);

            $mainPath = Paths::getViewFolderPath() . '/main/';
            $adminPath = Paths::getViewFolderPath() . '/admin/';
            $authPath = Paths::getViewFolderPath() . '/auth/';

            $file = (count($routeParts) <= 1) ? 'index' : '';
            $basePath = self::determineBasePath($routeParts, $adminPath, $authPath, $mainPath);

            if (isset($routeParts[1]) && $routeParts[1] === App::$adminLink) {
                Filters::checkLogin();
            }

            if (isset($routeParts[1]) && $routeParts[1] === App::$authLink) {
                Filters::checkLoginDone();
            }

            $folderPath = '';

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

            $file = (is_dir($basePath . $folderPath) && empty($file)) ? 'index.php' : $file;
            $filePath = $basePath . $folderPath . $file . ((pathinfo($file, PATHINFO_EXTENSION) === '') ? '.php' : '');
            $indexPath = $basePath . 'index.php';

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
        include(Paths::getViewPath('errors/error_500.php'));
        exit;
    }
}