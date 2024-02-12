<?php

namespace Config;

$config = [
    'dbdriver' => 'mysqli', // 'mysqli' or 'pdo'
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'pureeasyphp',
];


interface DatabaseConnectionInterface
{
    public function connect(array $config);
}

class PDODatabaseConnection implements DatabaseConnectionInterface
{
    public function connect(array $config)
    {
        try {
            $dsn = 'mysql:host=' . $config['hostname'] . ';dbname=' . $config['database'] . ';charset=utf8';
            $conn = new \PDO($dsn, $config['username'], $config['password']);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (\PDOException $e) {
            $this->handleDatabaseError($e);
        }
    }

    private function handleDatabaseError(\PDOException $e)
    {
        http_response_code(500);
        include(VIEW_FOLDER . '/errors/error_500.php');
        exit;
    }
}

class MySQLiDatabaseConnection implements DatabaseConnectionInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect(array $config)
    {
        $conn = new \mysqli($this->config['hostname'], $this->config['username'], $this->config['password'], $this->config['database']);

        if ($conn->connect_error) {
            throw new \Exception("MySQLi connection failed: " . $conn->connect_error);
        }

        return $conn;
    }
}

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

try {
    $databaseConnection = null;

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
} catch (\Exception $e) {
    http_response_code(500);
    include(VIEW_FOLDER . '/errors/error_500.php');
    exit;
}
