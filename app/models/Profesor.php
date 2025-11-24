<?php
require_once __DIR__ . '/../config/Connection.php';

class Profesor
{
    private $conn;
    private $table = "profesores2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY estado DESC, nombres ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivos()
    {
        $sql = "SELECT * FROM {$this->table} WHERE estado = 'activo' ORDER BY nombres ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (run, codver, nombres, apepat, apemat, email, telefono,
                 fecha_ingreso, fecha_salida, tipo, estado)
                VALUES
                (:run, :codver, :nombres, :apepat, :apemat, :email, :telefono,
                 :fecha_ingreso, :fecha_salida, :tipo, :estado)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':run'           => $data['run'],
            ':codver'        => $data['codver'] ?? null,
            ':nombres'       => $data['nombres'],
            ':apepat'        => $data['apepat'],
            ':apemat'        => $data['apemat'] ?? null,
            ':email'         => $data['email'] ?? null,
            ':telefono'      => $data['telefono'] ?? null,
            ':fecha_ingreso' => $data['fecha_ingreso'] ?? null,
            ':fecha_salida'  => $data['fecha_salida'] ?? null,
            ':tipo'          => $data['tipo'] ?? 'titular',
            ':estado'        => $data['estado'] ?? 'activo',
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    run = :run,
                    codver = :codver,
                    nombres = :nombres,
                    apepat = :apepat,
                    apemat = :apemat,
                    email = :email,
                    telefono = :telefono,
                    fecha_ingreso = :fecha_ingreso,
                    fecha_salida = :fecha_salida,
                    tipo = :tipo,
                    estado = :estado
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id'            => $id,
            ':run'           => $data['run'],
            ':codver'        => $data['codver'] ?? null,
            ':nombres'       => $data['nombres'],
            ':apepat'        => $data['apepat'],
            ':apemat'        => $data['apemat'] ?? null,
            ':email'         => $data['email'] ?? null,
            ':telefono'      => $data['telefono'] ?? null,
            ':fecha_ingreso' => $data['fecha_ingreso'] ?? null,
            ':fecha_salida'  => $data['fecha_salida'] ?? null,
            ':tipo'          => $data['tipo'] ?? 'titular',
            ':estado'        => $data['estado'] ?? 'activo',
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
