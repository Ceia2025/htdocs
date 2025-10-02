<?php
require_once __DIR__ . '/../../config/Connection.php';

class Procedencia
{
    private $conn;
    private $table = "procedencia";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAll()
    {
        $sql = "SELECT id, tipo, donador_fondo, fecha_adquisicion 
                FROM {$this->table}
                ORDER BY tipo ASC, donador_fondo ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT id, tipo, donador_fondo, fecha_adquisicion
                FROM {$this->table} 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($tipo, $donador_fondo, $fecha_adquisicion)
    {
        $sql = "INSERT INTO {$this->table} (tipo, donador_fondo, fecha_adquisicion)
                VALUES (:tipo, :donador_fondo, :fecha_adquisicion)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":tipo" => $tipo,
            ":donador_fondo" => $donador_fondo,
            ":fecha_adquisicion" => $fecha_adquisicion
        ]);
    }

    public function update($id, $tipo, $donador_fondo, $fecha_adquisicion)
    {
        $sql = "UPDATE {$this->table} 
                SET tipo = :tipo,
                    donador_fondo = :donador_fondo,
                    fecha_adquisicion = :fecha_adquisicion
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":tipo" => $tipo,
            ":donador_fondo" => $donador_fondo,
            ":fecha_adquisicion" => $fecha_adquisicion
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}