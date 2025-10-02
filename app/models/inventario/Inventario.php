<?php
require_once __DIR__ . '/../../config/Connection.php';

class Inventario
{
    private $conn;
    private $table = "inventario";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAll()
    {
        $sql = "SELECT inv.*, 
                       ne.nombre AS nivel_educativo,
                       ind.descripcion AS individualizacion,
                       cat.nombre AS categorizacion,
                       ec.nombre AS estado,
                       lf.nombre AS lugar,
                       p.tipo AS procedencia_tipo,
                       p.donador_fondo,
                       p.fecha_adquisicion
                FROM {$this->table} inv
                JOIN nivel_educativo ne ON inv.nivel_id = ne.id
                JOIN individualizacion ind ON inv.individualizacion_id = ind.id
                JOIN categorizacion cat ON inv.categorizacion_id = cat.id
                JOIN estado_conservacion ec ON inv.estado_id = ec.id
                JOIN lugar_fisico lf ON inv.lugar_id = lf.id
                JOIN procedencia p ON inv.procedencia_id = p.id
                ORDER BY inv.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (nivel_id, individualizacion_id, categorizacion_id, cantidad, estado_id, lugar_id, procedencia_id, codigo_general, codigo_especifico)
                VALUES 
                (:nivel_id, :individualizacion_id, :categorizacion_id, :cantidad, :estado_id, :lugar_id, :procedencia_id, :codigo_general, :codigo_especifico)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    nivel_id = :nivel_id,
                    individualizacion_id = :individualizacion_id,
                    categorizacion_id = :categorizacion_id,
                    cantidad = :cantidad,
                    estado_id = :estado_id,
                    lugar_id = :lugar_id,
                    procedencia_id = :procedencia_id,
                    codigo_general = :codigo_general,
                    codigo_especifico = :codigo_especifico
                WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
