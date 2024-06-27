<?php

namespace Config;

use Pureeasyphp\Database\PDODatabaseConnection;
use Pureeasyphp\Database\MySQLiDatabaseConnection;
use Pureeasyphp\Database\Database;

$config = [
    'useDB' => true, // true or false - Set to false to run locally without using a database
    'dbdriver' => 'mysqli', // 'mysqli' or 'pdo'

    // Database Connection
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'PureEasyPHP',
];

try {
    $databaseConnection = null;

    if ($config['useDB']) {
        switch ($config['dbdriver']) {
            case 'pdo':
                $databaseConnection = new PDODatabaseConnection();
                break;

            case 'mysqli':
                $databaseConnection = new MySQLiDatabaseConnection($config);
                break;

            default:
                throw new \InvalidArgumentException("Invalid database driver specified");
        }

        $database = new Database($config, $databaseConnection);
        $conn = $database->getConnection();
    }

} catch (\Exception $e) {
    http_response_code(500);
    include (VIEW_FOLDER . '/errors/error_500.php');
    exit;
}