<?php

namespace Pureeasyphp\Database;

class Database
{
    private $conn;

    public function __construct(array $config, DatabaseConnectionInterface $connection)
    {
        $this->validateConfig($config);
        $this->conn = $connection->connect($config);
    }

    private function validateConfig(array $config)
    {
        $requiredKeys = ['hostname', 'username', 'password', 'database'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $config)) {
                throw new \InvalidArgumentException("Missing '$key' in the database configuration.");
            }
        }
    }

    public function getConnection()
    {
        if (!$this->conn) {
            throw new \Exception("No active database connection.");
        }
        return $this->conn;
    }
}
