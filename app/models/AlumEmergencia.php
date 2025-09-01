<?php
require_once __DIR__ . '/../config/Connection.php';

class AlumEmergencia
{
    private $conn;
    private $table = "alum_emergencia2";

    public function __construct()
    {
        $database = new Connection();
        $this->conn = $database->open();
    }

    // Crear contacto de emergencia
    public function create($alumno_id, $nombre_contacto, $telefono, $direccion, $relacion)
    {
        $sql = "INSERT INTO $this->table 
                (alumno_id, nombre_contacto, telefono, direccion, relacion) 
                VALUES (:alumno_id, :nombre_contacto, :telefono, :direccion, :relacion)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":alumno_id" => $alumno_id,
            ":nombre_contacto" => $nombre_contacto,
            ":telefono" => $telefono,
            ":direccion" => $direccion,
            ":relacion" => $relacion
        ]);
    }

    // Obtener todos los contactos de emergencia (con datos del alumno)
    public function getAll(): array
    {
        $sql = "SELECT e.*, a.nombre AS alumno_nombre, a.apepat AS ape_paterno, a.apemat AS ape_materno
                FROM $this->table e
                JOIN alumnos2 a ON a.id = e.alumno_id
                ORDER BY e.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar contacto
    public function update($id, $alumno_id, $nombre_contacto, $telefono, $direccion, $relacion)
    {
        $sql = "UPDATE $this->table
                SET alumno_id = :alumno_id,
                    nombre_contacto = :nombre_contacto,
                    telefono = :telefono,
                    direccion = :direccion,
                    relacion = :relacion
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":alumno_id" => $alumno_id,
            ":nombre_contacto" => $nombre_contacto,
            ":telefono" => $telefono,
            ":direccion" => $direccion,
            ":relacion" => $relacion
        ]);
    }

    // Eliminar contacto
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
