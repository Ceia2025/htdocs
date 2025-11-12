<?php
require_once __DIR__ . '/../config/Connection.php';

class Matricula
{
    private $conn;
    private $table = "matriculas2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    //Busca Por matricula
    public function buscarMatriculas($nombre = null, $rut = null, $anio = null, $curso = null)
    {
        $sql = "
            SELECT m.id, 
                   a.run, 
                   CONCAT(a.nombre, ' ', a.apepat, ' ', a.apemat) AS nombre_completo, 
                   c.nombre AS curso, 
                   an.anio AS anio, 
                   m.fecha_matricula
            FROM matriculas2 m
            INNER JOIN alumnos2 a ON m.alumno_id = a.id
            INNER JOIN cursos2 c ON m.curso_id = c.id
            INNER JOIN anios2 an ON m.anio_id = an.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($nombre)) {
            $sql .= " AND (a.nombre LIKE :nombre OR a.apepat LIKE :nombre OR a.apemat LIKE :nombre)";
            $params[':nombre'] = "%$nombre%";
        }

        if (!empty($rut)) {
            $sql .= " AND a.run LIKE :rut";
            $params[':rut'] = "%$rut%";
        }

        if (!empty($anio)) {
            $sql .= " AND an.anio = :anio";
            $params[':anio'] = $anio;
        }

        if (!empty($curso)) {
            $sql .= " AND c.nombre LIKE :curso";
            $params[':curso'] = "%$curso%";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las matrículas (con JOINs)
    public function getAll()
    {
        $sql = "SELECT 
                    m.id,
                    a.id AS alumno_id,
                    CONCAT(a.nombre, ' ', a.apepat, ' ', a.apemat) AS alumno_nombre,
                    c.nombre AS curso_nombre,
                    an.anio AS anio_escolar,
                    m.fecha_matricula
                FROM {$this->table} m
                INNER JOIN alumnos2 a ON m.alumno_id = a.id
                INNER JOIN cursos2 c ON m.curso_id = c.id
                INNER JOIN anios2 an ON m.anio_id = an.id
                ORDER BY an.anio DESC, c.nombre ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener matrícula por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener matrículas de un alumno
    public function getByAlumno($alumno_id)
    {
        $sql = "SELECT 
                    m.*, 
                    c.nombre AS curso_nombre,
                    an.anio AS anio_escolar
                FROM {$this->table} m
                INNER JOIN cursos2 c ON m.curso_id = c.id
                INNER JOIN anios2 an ON m.anio_id = an.id
                WHERE m.alumno_id = :alumno_id
                ORDER BY an.anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear matrícula
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (alumno_id, curso_id, anio_id, fecha_matricula)
                VALUES (:alumno_id, :curso_id, :anio_id, :fecha_matricula)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':alumno_id' => $data['alumno_id'],
            ':curso_id' => $data['curso_id'],
            ':anio_id' => $data['anio_id'],
            ':fecha_matricula' => $data['fecha_matricula'] ?? date('Y-m-d')
        ]);
    }

    // Actualizar matrícula
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET alumno_id = :alumno_id,
                    curso_id = :curso_id,
                    anio_id = :anio_id,
                    fecha_matricula = :fecha_matricula
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':alumno_id' => $data['alumno_id'],
            ':curso_id' => $data['curso_id'],
            ':anio_id' => $data['anio_id'],
            ':fecha_matricula' => $data['fecha_matricula'] ?? date('Y-m-d')
        ]);
    }

    // 🔹 Obtener matrícula según alumno, curso y año
    public function getByAlumnoYCurso($alumno_id, $curso_id, $anio_id)
    {
        $sql = "SELECT * FROM matriculas2
            WHERE alumno_id = :alumno_id 
              AND curso_id = :curso_id 
              AND anio_id = :anio_id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':alumno_id' => $alumno_id,
            ':curso_id' => $curso_id,
            ':anio_id' => $anio_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar matrícula
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
