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

    // Obtener todos
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear
    public function create($nombre, $codigo_general, $codigo_especifico)
    {
        $sql = "INSERT INTO {$this->table} (nombre, codigo_general, codigo_especifico) 
                VALUES (:nombre, :codigo_general, :codigo_especifico)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":nombre" => $nombre,
            ":codigo_general" => strtoupper($codigo_general),
            ":codigo_especifico" => $codigo_especifico
        ]);
    }

    public function searchByName($term)
    {
        $sql = "SELECT nombre, codigo_general FROM {$this->table} WHERE nombre LIKE :term ORDER BY nombre ASC LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':term' => "%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getNextCodigoEspecifico($codigo_general)
    {
        $sql = "SELECT MAX(codigo_especifico) as max_codigo FROM {$this->table} WHERE codigo_general = :codigo_general";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':codigo_general' => $codigo_general]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['max_codigo'] ? intval($row['max_codigo']) + 1 : 1;
    }

    // Actualizar
    public function update($id, $nombre, $codigo_general, $codigo_especifico)
    {
        $sql = "UPDATE {$this->table} 
                SET nombre = :nombre, codigo_general = :codigo_general, codigo_especifico = :codigo_especifico 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":nombre" => $nombre,
            ":codigo_general" => strtoupper($codigo_general),
            ":codigo_especifico" => $codigo_especifico
        ]);
    }

    // Eliminar
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    // Buscar para autocompletado
    public function buscarPorNombre($term)
    {
        $sql = "SELECT nombre, codigo_general 
                FROM {$this->table} 
                WHERE nombre LIKE :term 
                GROUP BY nombre, codigo_general
                LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':term' => "%{$term}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener siguiente código específico
    public function obtenerSiguienteCodigoEspecifico($codigo_general)
    {
        $sql = "SELECT MAX(codigo_especifico) AS max_especifico 
                FROM {$this->table} 
                WHERE codigo_general = :codigo_general";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':codigo_general' => $codigo_general]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['max_especifico'] ?? 0) + 1;
    }
}