<?php
require_once __DIR__ . '/../config/Connection.php';

class Suplencia
{
    private $conn;
    private $table = "suplencias2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getByRangoFechas($desde, $hasta)
    {
        $sql = "SELECT s.*, 
                       pt.nombres AS titular_nombres, pt.apepat AS titular_apepat,
                       ps.nombres AS suplente_nombres, ps.apepat AS suplente_apepat
                FROM {$this->table} s
                INNER JOIN profesores2 pt ON s.profesor_titular_id = pt.id
                INNER JOIN profesores2 ps ON s.profesor_suplente_id = ps.id
                WHERE s.fecha_desde <= :hasta AND s.fecha_hasta >= :desde
                ORDER BY s.fecha_desde DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':desde' => $desde,
            ':hasta' => $hasta,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (horario_id, profesor_titular_id, profesor_suplente_id,
                 fecha_desde, fecha_hasta, motivo)
                VALUES
                (:horario_id, :profesor_titular_id, :profesor_suplente_id,
                 :fecha_desde, :fecha_hasta, :motivo)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':horario_id'         => $data['horario_id'],
            ':profesor_titular_id'=> $data['profesor_titular_id'],
            ':profesor_suplente_id'=> $data['profesor_suplente_id'],
            ':fecha_desde'        => $data['fecha_desde'],
            ':fecha_hasta'        => $data['fecha_hasta'],
            ':motivo'             => $data['motivo'] ?? null,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
