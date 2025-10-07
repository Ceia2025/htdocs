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
                   ind.nombre AS individualizacion,
                   ind.codigo_general,
                   ind.codigo_especifico,
                   cat.descripcion AS categorizacion,
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

    public function filter($filters)
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['nivel_id'])) {
            $conditions[] = "inv.nivel_id = :nivel_id";
            $params[':nivel_id'] = $filters['nivel_id'];
        }

        if (!empty($filters['busqueda_individualizacion'])) {
            // Combina búsqueda por nombre o código general (sin tildes, insensible a mayúsculas)
            $conditions[] = "(
            LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(ind.nombre, 'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u')) 
                LIKE LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(:busqueda,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u'))
            OR UPPER(ind.codigo_general) LIKE UPPER(:busqueda)
        )";
            $params[':busqueda'] = '%' . $filters['busqueda_individualizacion'] . '%';
        }

        if (!empty($filters['categorizacion_id'])) {
            $conditions[] = "inv.categorizacion_id = :categorizacion_id";
            $params[':categorizacion_id'] = $filters['categorizacion_id'];
        }

        if (!empty($filters['estado_id'])) {
            $conditions[] = "inv.estado_id = :estado_id";
            $params[':estado_id'] = $filters['estado_id'];
        }

        if (!empty($filters['lugar_id'])) {
            $conditions[] = "inv.lugar_id = :lugar_id";
            $params[':lugar_id'] = $filters['lugar_id'];
        }

        $where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $sql = "SELECT inv.*, 
                   ne.nombre AS nivel_educativo,
                   ind.nombre AS individualizacion,
                   ind.codigo_general,
                   ind.codigo_especifico,
                   cat.descripcion AS categorizacion,
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
            $where
            ORDER BY inv.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
            (nivel_id, individualizacion_id, categorizacion_id, cantidad, estado_id, lugar_id, procedencia_id)
            VALUES 
            (:nivel_id, :individualizacion_id, :categorizacion_id, :cantidad, :estado_id, :lugar_id, :procedencia_id)";
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
