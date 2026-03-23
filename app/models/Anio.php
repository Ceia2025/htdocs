<?php
require_once __DIR__ . '/../config/Connection.php';

class Anio
{
    private $conn;
    private $table = "anios2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT id, anio, descripcion, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin FROM {$this->table} ORDER BY anio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT id, anio, descripcion, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($anio, $descripcion, $sem1_inicio, $sem1_fin, $sem2_inicio, $sem2_fin)
    {
        $sql = "INSERT INTO {$this->table} (anio, descripcion, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin) 
                VALUES (:anio, :descripcion, :sem1_inicio, :sem1_fin, :sem2_inicio, :sem2_fin)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":anio" => $anio,
            ":descripcion" => $descripcion,
            ":sem1_inicio" => $sem1_inicio ?: null,
            ":sem1_fin" => $sem1_fin ?: null,
            ":sem2_inicio" => $sem2_inicio ?: null,
            ":sem2_fin" => $sem2_fin ?: null,
        ]);
    }

    public function update($id, $anio, $descripcion, $sem1_inicio, $sem1_fin, $sem2_inicio, $sem2_fin)
    {
        $sql = "UPDATE {$this->table} 
                SET anio = :anio, descripcion = :descripcion,
                    sem1_inicio = :sem1_inicio, sem1_fin = :sem1_fin,
                    sem2_inicio = :sem2_inicio, sem2_fin = :sem2_fin
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":anio" => $anio,
            ":descripcion" => $descripcion,
            ":sem1_inicio" => $sem1_inicio ?: null,
            ":sem1_fin" => $sem1_fin ?: null,
            ":sem2_inicio" => $sem2_inicio ?: null,
            ":sem2_fin" => $sem2_fin ?: null,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}