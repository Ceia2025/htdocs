<?php
require_once __DIR__ . '/../../config/Connection.php';

class Individualizacion
{
    private $conn;
    private $table = "individualizacion";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY descripcion ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($descripcion)
    {
        $sql = "INSERT INTO {$this->table} (descripcion) VALUES (:descripcion)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":descripcion" => $descripcion]);
    }

    public function update($id, $descripcion)
    {
        $sql = "UPDATE {$this->table} SET descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id, ":descripcion" => $descripcion]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
