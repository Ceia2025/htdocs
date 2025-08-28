<?php
require_once __DIR__ . '/../config/Connection.php';

class Cursos
{
    private $conn;
    private $table = "cursos2";

    public function __construct() {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos los cursos con el año asociado
    public function getAll() {
        $sql = "SELECT c.id, c.nombre, c.anio_id, a.anio, a.descripcion 
                FROM {$this->table} c
                INNER JOIN anios2 a ON c.anio_id = a.id
                ORDER BY a.anio DESC, c.nombre ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener curso por ID con el año asociado
    public function getById($id) {
        $sql = "SELECT c.id, c.nombre, c.anio_id, a.anio, a.descripcion 
                FROM {$this->table} c
                INNER JOIN anios2 a ON c.anio_id = a.id
                WHERE c.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear curso
    public function create($anio_id, $nombre) {
        $sql = "INSERT INTO {$this->table} (anio_id, nombre) VALUES (:anio_id, :nombre)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":anio_id" => $anio_id, ":nombre" => $nombre]);
    }

    // Actualizar curso
    public function update($id, $anio_id, $nombre) {
        $sql = "UPDATE {$this->table} 
                SET anio_id = :anio_id, nombre = :nombre 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id, ":anio_id" => $anio_id, ":nombre" => $nombre]);
    }

    // Eliminar curso
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
