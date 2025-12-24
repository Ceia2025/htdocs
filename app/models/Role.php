<?php
require_once __DIR__ . '/../config/Connection.php';

class Role
{
    private $conn;
    private $table = "roles2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos
    public function getAll()
    {
        $stmt = $this->conn->query("SELECT id, nombre FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Ordenar por ID
    public function getById($id)
    {
        $sql = "SELECT id, nombre FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear
    public function create($nombre)
    {
        $sql = "INSERT INTO {$this->table} (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":nombre" => $nombre]);
    }



    // Actualizar
    public function update($id, $nombre)
    {
        $sql = "UPDATE {$this->table} SET nombre = :nombre WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id, ":nombre" => $nombre]);
    }

    // Eliminar
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

}
