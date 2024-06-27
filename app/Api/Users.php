<?php

use Pureeasyphp\Paths;

class Users
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect()
    {
        require (Paths::getAppFolderPath() . '/Config/Database.php');
        return $conn;
    }

    public static function get()
    {
        try {
            $instance = new self();
            $data = $instance->fetchUsers();

            if ($data === false) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to fetch user data.'
                ];
            }

            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    private function fetchUsers()
    {
        $sql = "SELECT name FROM users";

        // Check if the connection is PDO or MySQLi
        if ($this->conn instanceof PDO) {
            // PDO
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($this->conn instanceof mysqli) {
            // MySQLi
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                $result = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                $result = [];
            }
        } else {
            throw new Exception('Unsupported database connection type');
        }

        // Process the result
        if (count($result) > 0) {
            $users = [];
            foreach ($result as $row) {
                $users[] = $row["name"];
            }
            return $users;
        } else {
            return false;
        }
    }


}
