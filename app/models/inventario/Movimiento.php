<?php
require_once __DIR__ . '/../../config/Connection.php';

class Movimiento
{
    private $conn;
    private $table = "inventario_movimiento";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Historial de un bien (más reciente primero)
    public function getByInventario($inventarioId)
    {
        $sql = "SELECT 
                    mov.id,
                    mov.observacion,
                    mov.fecha,
                    mov.created_at,
                    lo.nombre AS lugar_origen,
                    ld.nombre AS lugar_destino
                FROM {$this->table} mov
                LEFT JOIN lugar_fisico lo ON mov.lugar_origen_id  = lo.id
                LEFT JOIN lugar_fisico ld ON mov.lugar_destino_id = ld.id
                WHERE mov.inventario_id = :id
                ORDER BY mov.fecha DESC, mov.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $inventarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Registrar un movimiento / observación
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (inventario_id, lugar_origen_id, lugar_destino_id, observacion, fecha, usuario_id)
                VALUES
                (:inventario_id, :lugar_origen_id, :lugar_destino_id, :observacion, :fecha, :usuario_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":inventario_id"    => $data['inventario_id'],
            ":lugar_origen_id"  => !empty($data['lugar_origen_id']) ? $data['lugar_origen_id'] : null,
            ":lugar_destino_id" => !empty($data['lugar_destino_id']) ? $data['lugar_destino_id'] : null,
            ":observacion"      => !empty($data['observacion']) ? trim($data['observacion']) : null,
            ":fecha"            => !empty($data['fecha']) ? $data['fecha'] : date('Y-m-d'),
            ":usuario_id"       => $data['usuario_id'] ?? null,
        ]);
    }

    // Contar movimientos de un bien (útil para mostrar badge)
    public function contarPorInventario($inventarioId)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE inventario_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $inventarioId]);
        return (int) $stmt->fetchColumn();
    }
}