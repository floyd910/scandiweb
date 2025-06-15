<?php
namespace User\Scandiweb;

use PDO;
use PDOException;

class Database {
    private string $host = "localhost";
    private string $db_name = "your_database_name";
    private string $username = "root";
    private string $password = "";
    private PDO $conn;

    public function getConnection(): PDO {
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conn;
    }
}
