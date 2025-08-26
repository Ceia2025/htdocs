<?php
class Connection {
    private $host = "localhost";
    private $dbname = "saatdevtest2";
    private $username = "root";
    private $port = "3308"; 
    private $password = "ceia2025";
    private $conn;

    public function open() {
        if ($this->conn == null) {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exception) {
                die("Error de conexión: " . $exception->getMessage());
            }
        }
        return $this->conn;
    }

    public function close() {
        $this->conn = null;
    }
}
