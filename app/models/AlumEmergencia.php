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
        $sql = "SELECT e.*, a.nombre AS alumno_nombre, a.apepat AS ape_paterno, a.apemat AS ape_materno, a.run
                FROM $this->table e
                JOIN alumnos2 a ON a.id = e.alumno_id
                ORDER BY e.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔍 Buscar contactos de emergencia por nombre o RUN del alumno
    public function searchByAlumno($term): array
    {
        // Normalizamos el término buscado: quitamos puntos y guiones
        $termNormalized = str_replace(['.', '-'], '', $term);

        $sql = "SELECT e.*, 
                   a.nombre AS alumno_nombre, 
                   a.apepat AS ape_paterno, 
                   a.apemat AS ape_materno, 
                   a.run
            FROM $this->table e
            JOIN alumnos2 a ON a.id = e.alumno_id
            WHERE a.nombre LIKE :term
               OR a.apepat LIKE :term
               OR a.apemat LIKE :term
               OR REPLACE(REPLACE(a.run, '.', ''), '-', '') LIKE :termNormalized
            ORDER BY e.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':term' => "%$term%",
            ':termNormalized' => "%$termNormalized%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //
    public function findByAlumno($alumno_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM alum_emergencia2 WHERE alumno_id = ?");
        $stmt->execute([$alumno_id]);
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