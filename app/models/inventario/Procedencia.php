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
                FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Devuelve el ID recien creado (para el AJAX de la ventana flotante)
    public function create($tipo, $donador_fondo = null, $fecha_adquisicion = null)
    {
        $sql = "INSERT INTO {$this->table} (tipo, donador_fondo, fecha_adquisicion)
                VALUES (:tipo, :donador_fondo, :fecha_adquisicion)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":tipo"              => $tipo ?: 'Sin información',
            ":donador_fondo"     => !empty($donador_fondo) ? trim($donador_fondo) : null,
            ":fecha_adquisicion" => !empty($fecha_adquisicion) ? $fecha_adquisicion : null,
        ]);
        return (int) $this->conn->lastInsertId();
    }

    public function update($id, $tipo, $donador_fondo = null, $fecha_adquisicion = null)
    {
        $sql = "UPDATE {$this->table}
                SET tipo = :tipo, donador_fondo = :donador_fondo, fecha_adquisicion = :fecha_adquisicion
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id"                => $id,
            ":tipo"              => $tipo ?: 'Sin información',
            ":donador_fondo"     => !empty($donador_fondo) ? trim($donador_fondo) : null,
            ":fecha_adquisicion" => !empty($fecha_adquisicion) ? $fecha_adquisicion : null,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    // Etiqueta legible para mostrar en selects / tablas
    public static function etiqueta($row)
    {
        if (empty($row)) return 'Sin procedencia';
        $partes = [$row['tipo']];
        if (!empty($row['donador_fondo']))     $partes[] = $row['donador_fondo'];
        if (!empty($row['fecha_adquisicion']))  $partes[] = date('d/m/Y', strtotime($row['fecha_adquisicion']));
        return implode(' - ', $partes);
    }
}