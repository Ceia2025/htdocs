<?php
require_once __DIR__ . '/../../config/Connection.php';

class Catalogo
{
    private $conn;
    private $table;

    public function __construct($table)
    {
        $db = new Connection();
        $this->conn = $db->open();
        $this->table = $table;
    }

    // Obtener todos los registros
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener registro por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo registro
    public function create($nombre)
    {
        $sql = "INSERT INTO {$this->table} (nombre) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":nombre" => $nombre]);
    }

    // Actualizar registro
    public function update($id, $nombre)
    {
        $sql = "UPDATE {$this->table} SET nombre = :nombre WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id, ":nombre" => $nombre]);
    }

    // Eliminar registro
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}




/*
$niveles = new Catalogo("nivel_educativo");
print_r($niveles->getAll());
$estados = new Catalogo("categorizacion");
print_r($estados->getAll());
$estados = new Catalogo("estado_conservacion");
print_r($estados->getAll());
$estados = new Catalogo("lugar_fisico");
print_r($estados->getAll());
$estados = new Catalogo("procedencia");
print_r($estados->getAll());*/