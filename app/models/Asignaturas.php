<?php
require_once __DIR__ . '/../config/Connection.php';

class Asignaturas
{
    private $conn;
    private $table = "asignaturas2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos los cursos
    public function getAll()
    {
        $sql = "SELECT id, abreviatura , nombre, descp 
                FROM {$this->table}
                ORDER BY nombre ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener curso por ID
    public function getById($id)
    {
        $sql = "SELECT id, abreviatura, nombre, descp 
                FROM {$this->table} 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear curso
    public function create($abreviatura, $nombre, $descp)
    {
        $sql = "INSERT INTO {$this->table} (abreviatura, nombre, descp) 
                VALUES (:abreviatura, :nombre, :descp)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":abreviatura" => $abreviatura, ":nombre" => $nombre, ":descp" => $descp]);
    }

    // Actualizar curso
    public function update($id,$abreviatura, $nombre, $descp) {
    $sql = "UPDATE {$this->table} 
            SET abreviatura = :abreviatura,
                nombre = :nombre, 
                descp = :descp
            WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([":id" => $id,":abreviatura" => $abreviatura, ":nombre" => $nombre, ":descp" => $descp]);
}

    // Eliminar curso
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
