<?php

namespace Pureeasyphp;

use Config\App;

class Api
{
    private static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
        // Add more methods if needed
    ];

    public function get($path, $handler)
    {
        self::$routes['GET']['/' . trim($path, '/')] = $handler;
        return $this;
    }

    public function post($path, $handler)
    {
        self::$routes['POST']['/' . trim($path, '/')] = $handler;
        return $this;
    }

    public function put($path, $handler)
    {
        self::$routes['PUT']['/' . trim($path, '/')] = $handler;
        return $this;
    }

    public function delete($path, $handler)
    {
        self::$routes['DELETE']['/' . trim($path, '/')] = $handler;
        return $this;
    }

    public function patch($path, $handler)
    {
        self::$routes['PATCH']['/' . trim($path, '/')] = $handler;
        return $this;
    }

    public static function handleRequest()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $apiBasePath = str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['SCRIPT_NAME'])) . '/' . App::$apiLink;
            $relativePath = str_replace($apiBasePath, '', $path);

            if ($relativePath === '' || $relativePath === '/') {
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Api Link Work']);
                return;
            }

            $response = self::routeRequest($method, $relativePath);

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (\Exception $e) {
            self::handleException($e);
        }
    }


    private static function routeRequest($method, $path)
    {
        if (isset(self::$routes[$method][$path])) {
            $className = self::$routes[$method][$path];
            $methodName = strtolower($method);

            $filePath = Paths::getApiFilePath($className);
            if ($filePath && file_exists($filePath)) {
                require_once (Paths::getAppFolderPath() . '/Config/Database.php');
                include_once $filePath;
                if (class_exists($className) && method_exists($className, $methodName)) {
                    return call_user_func([$className, $methodName]);
                } else {
                    throw new \Exception('Method not found', 404);
                }
            } else {
                throw new \Exception('File not found', 404);
            }
        } else {
            throw new \Exception('Route not found', 404);
        }
    }

    private static function handleException(\Exception $e)
    {
        if (!headers_sent()) {
            http_response_code($e->getCode() ?: 500);
        }
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

// Load routes
require_once (Paths::getAppFolderPath() . '/Config/RoutesApi.php');

// Handle the request
Api::handleRequest();
