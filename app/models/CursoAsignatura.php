<?php
require_once __DIR__ . '/../config/Connection.php';

class CursoAsignatura
{
    private $conn;
    private $table = "curso_asignaturas2"; // tu tabla pivote

    public function __construct()
    {
        $database = new Connection();
        $this->conn = $database->open();
    }

    // Crear relación curso - asignatura
    public function create($curso_id, $asignatura_id)
    {
        $sql = "INSERT INTO $this->table (curso_id, asignatura_id) 
                VALUES (:curso_id, :asignatura_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":curso_id" => $curso_id,
            ":asignatura_id" => $asignatura_id,
        ]);
    }

    //Obtener asignaturas por curso
    public function getAsignaturasPorCurso($curso_id)
    {
        $sql = "SELECT a.id, a.nombre 
            FROM curso_asignaturas2 ca
            INNER JOIN asignaturas2 a ON ca.asignatura_id = a.id
            WHERE ca.curso_id = :curso_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las relaciones
    public function getAll(): array
    {
        $sql = "
            SELECT ca.id, 
                   c.nombre AS curso, 
                   a.nombre AS asignatura
            FROM $this->table ca
            JOIN cursos2 c ON c.id = ca.curso_id
            JOIN asignaturas2 a ON a.id = ca.asignatura_id
            ORDER BY ca.id
        ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCurso($curso_id): array
    {
        $sql = "
        SELECT ca.id, 
               c.nombre AS curso, 
               a.nombre AS asignatura
        FROM $this->table ca
        JOIN cursos2 c ON c.id = ca.curso_id
        JOIN asignaturas2 a ON a.id = ca.asignatura_id
        WHERE ca.curso_id = :curso_id
        ORDER BY ca.id
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":curso_id" => $curso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Obtener una relación por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar relación
    public function update($id, $curso_id, $asignatura_id)
    {
        $sql = "UPDATE $this->table 
                SET curso_id = :curso_id, asignatura_id = :asignatura_id
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":curso_id" => $curso_id,
            ":asignatura_id" => $asignatura_id,
        ]);
    }

    // Eliminar relación
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
