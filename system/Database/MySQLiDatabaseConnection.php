<?php

namespace Pureeasyphp\Database;

use mysqli;
use Exception;

class MySQLiDatabaseConnection implements DatabaseConnectionInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect(array $config)
    {
        $conn = new mysqli($this->config['hostname'], $this->config['username'], $this->config['password'], $this->config['database']);

        if ($conn->connect_error) {
            throw new Exception("MySQLi connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}
