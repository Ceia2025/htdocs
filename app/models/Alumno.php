<?php
require_once __DIR__ . '/../config/Connection.php';

class Alumno
{
    private $conn;
    private $table = "alumnos2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener todos los alumnos
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un alumno por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un alumno
    public function create($data)
    {
        $numerohijos = isset($data['numerohijos']) && $data['numerohijos'] !== ''
            ? (int) $data['numerohijos']
            : null;

        $sql = "INSERT INTO {$this->table} 
                (run, nombre, apepat, apemat, fechanac, mayoredad, numerohijos, telefono, email, sexo, nacionalidades, region, ciudad, cod_etnia) 
                VALUES 
                (:run, :nombre, :apepat, :apemat, :fechanac, :mayoredad, :numerohijos, :telefono, :email, :sexo, :nacionalidades, :region, :ciudad, :cod_etnia)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":run" => $data['run'],
            ":nombre" => $data['nombre'],
            ":apepat" => $data['apepat'],
            ":apemat" => $data['apemat'],
            ":fechanac" => $data['fechanac'],
            ":mayoredad" => $data['mayoredad'],
            ":numerohijos" => $numerohijos,
            ":telefono" => $data['telefono'],
            ":email" => $data['email'],
            ":sexo" => $data['sexo'],
            ":nacionalidades" => $data['nacionalidades'],
            ":region" => $data['region'],
            ":ciudad" => $data['ciudad'],
            ":cod_etnia" => $data['cod_etnia'],
        ]);
    }

    // Actualizar un alumno
    public function update($id, $data)
    {
        $numerohijos = !empty($data['numerohijos']) ? (int) $data['numerohijos'] : null;

        $sql = "UPDATE {$this->table} 
        SET run = :run, nombre = :nombre, apepat = :apepat, apemat = :apemat,
            fechanac = :fechanac, mayoredad = :mayoredad, numerohijos = :numerohijos,
            telefono = :telefono, email = :email, sexo = :sexo,
            nacionalidades = :nacionalidades, region = :region,
            ciudad = :ciudad, cod_etnia = :cod_etnia
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":run" => $data['run'],
            ":nombre" => $data['nombre'],
            ":apepat" => $data['apepat'],
            ":apemat" => $data['apemat'],
            ":fechanac" => $data['fechanac'],
            ":mayoredad" => $data['mayoredad'],
            ":numerohijos" => $numerohijos, // <- aquí ya va NULL o un número
            ":telefono" => $data['telefono'],
            ":email" => $data['email'],
            ":sexo" => $data['sexo'],
            ":nacionalidades" => $data['nacionalidades'],
            ":region" => $data['region'],
            ":ciudad" => $data['ciudad'],
            ":cod_etnia" => $data['cod_etnia']
        ]);
    }

    // Eliminar un alumno
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
