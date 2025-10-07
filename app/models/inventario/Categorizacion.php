<?php
require_once __DIR__ . '/../../config/Connection.php';

class Categorizacion
{
    private $conn;
    private $table = "categorizacion";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos
    public function getAll()
    {
        $stmt = $this->conn->query("SELECT id, nombre, codigo_general, codigo_especifico FROM {$this->table} ORDER BY codigo_general, codigo_especifico");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function getById($id)
    {
        $sql = "SELECT id, nombre, codigo_general, codigo_especifico FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener el último código específico para un código general
    private function getLastCodigoEspecifico($codigo_general)
    {
        $sql = "SELECT MAX(codigo_especifico) AS max_codigo FROM {$this->table} WHERE codigo_general = :codigo_general";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":codigo_general" => $codigo_general]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_codigo'] ? (int) $result['max_codigo'] : 0;
    }

    // Crear
    public function create($nombre, $codigo_general, $codigo_especifico = null)
    {
        try {
            // Convertir a mayúsculas
            $codigo_general = strtoupper($codigo_general);

            // Si no se proporciona el código específico, se genera automáticamente
            if (empty($codigo_especifico)) {
                $codigo_especifico = $this->getLastCodigoEspecifico($codigo_general) + 1;
            }

            // Insertar
            $sql = "INSERT INTO {$this->table} (nombre, codigo_general, codigo_especifico)
                    VALUES (:nombre, :codigo_general, :codigo_especifico)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ":nombre" => $nombre,
                ":codigo_general" => $codigo_general,
                ":codigo_especifico" => $codigo_especifico
            ]);

        } catch (PDOException $e) {
            // Si el error es por clave duplicada
            if ($e->getCode() == 23000) {
                return "DUPLICATE";
            }
            throw $e;
        }
    }
    public function buscarPorNombre($term)
    {
        $sql = "SELECT nombre, codigo_general 
            FROM {$this->table} 
            WHERE nombre LIKE :term 
            GROUP BY codigo_general, nombre 
            LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':term' => "%{$term}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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

    // Actualizar
    public function update($id, $nombre, $codigo_general, $codigo_especifico)
    {
        try {
            $codigo_general = strtoupper($codigo_general);
            $sql = "UPDATE {$this->table} 
                    SET nombre = :nombre, codigo_general = :codigo_general, codigo_especifico = :codigo_especifico
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ":id" => $id,
                ":nombre" => $nombre,
                ":codigo_general" => $codigo_general,
                ":codigo_especifico" => $codigo_especifico
            ]);

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "DUPLICATE";
            }
            throw $e;
        }
    }

    // Eliminar
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
