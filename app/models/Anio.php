<?php
require_once __DIR__ . '/../config/Connection.php';

class Anio
{
    private $conn;
    private $table = "anios2";

    public function __construct() {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos
    public function getAll() {
        $stmt = $this->conn->query("SELECT id, anio, descripcion FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Ordenar por ID
    public function getById($id) {
        $sql = "SELECT id, anio, descripcion FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear
    public function create($anio, $descripcion) {
        $sql = "INSERT INTO {$this->table} (anio, descripcion) VALUES (:anio, :descripcion)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":anio" => $anio, ":descripcion" => $descripcion]);
    }

    // Actualizar
    public function update($id, $anio, $descripcion) {
        $sql = "UPDATE {$this->table} SET anio = :anio, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id, ":anio" => $anio, ":descripcion" => $descripcion]);
    }

    // Eliminar
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

}
