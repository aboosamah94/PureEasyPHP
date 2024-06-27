<?php

namespace Pureeasyphp\Database;

use PDO;
use PDOException;

class PDODatabaseConnection implements DatabaseConnectionInterface
{
    public function connect(array $config)
    {
        try {
            $dsn = 'mysql:host=' . $config['hostname'] . ';dbname=' . $config['database'] . ';charset=utf8';
            $conn = new PDO($dsn, $config['username'], $config['password']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    private function handleDatabaseError(PDOException $e)
    {
        http_response_code(500);
        include (VIEW_FOLDER . '/errors/error_500.php');
        exit;
    }
}
