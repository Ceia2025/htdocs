<?php
require_once __DIR__ . '/../config/Connection.php';

class Anotacion
{
    private $conn;
    private $table = "anotaciones2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Obtener años con matrículas (para el selector inicial)
    public function getAnios()
    {
        $sql = "SELECT DISTINCT an.id, an.anio
                FROM anios2 an
                INNER JOIN matriculas2 m ON m.anio_id = an.id
                ORDER BY an.anio DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cursos de un año
    public function getCursosByAnio($anio_id)
    {
        $sql = "SELECT DISTINCT c.id, c.nombre
                FROM cursos2 c
                INNER JOIN matriculas2 m ON m.curso_id = c.id
                WHERE m.anio_id = :anio_id
                ORDER BY c.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener asignaturas de un curso
    public function getAsignaturasByCurso($curso_id)
    {
        $sql = "SELECT a.id, a.nombre, a.abreviatura
                FROM asignaturas2 a
                INNER JOIN curso_asignaturas2 ca ON ca.asignatura_id = a.id
                WHERE ca.curso_id = :curso_id
                ORDER BY a.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener alumnos con anotaciones en un curso y año
    public function getAlumnosConAnotaciones($anio_id, $curso_id, $semestre = null)
    {
        $sql = "SELECT DISTINCT
                    a.id, a.run, a.codver, a.nombre, a.apepat, a.apemat,
                    m.id AS matricula_id,
                    COUNT(an.id) AS total_anotaciones,
                    SUM(an.tipo = 'Positiva')  AS positivas,
                    SUM(an.tipo = 'Leve')      AS leves,
                    SUM(an.tipo = 'Grave')     AS graves,
                    SUM(an.tipo = 'Gravísima') AS gravisimas
                FROM alumnos2 a
                INNER JOIN matriculas2 m ON m.alumno_id = a.id
                LEFT JOIN anotaciones2 an ON an.matricula_id = m.id
                    " . ($semestre ? "AND an.semestre = :semestre" : "") . "
                WHERE m.anio_id = :anio_id AND m.curso_id = :curso_id
                GROUP BY a.id, m.id
                ORDER BY a.apepat ASC, a.apemat ASC, a.nombre ASC";

        $params = [':anio_id' => $anio_id, ':curso_id' => $curso_id];
        if ($semestre)
            $params[':semestre'] = $semestre;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener anotaciones de un alumno en una matrícula
    public function getByMatricula($matricula_id, $semestre = null)
    {
        $sql = "SELECT an.*, 
                    asig.nombre AS asignatura_nombre,
                    asig.abreviatura
                FROM anotaciones2 an
                INNER JOIN asignaturas2 asig ON asig.id = an.asignatura_id
                WHERE an.matricula_id = :matricula_id
                " . ($semestre ? "AND an.semestre = :semestre" : "") . "
                ORDER BY an.fecha_anotacion DESC";

        $params = [':matricula_id' => $matricula_id];
        if ($semestre)
            $params[':semestre'] = $semestre;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar alumno por RUN o nombre para el formulario de nueva anotación
    public function buscarAlumno($term, $anio_id, $porId = false)
    {
        if ($porId) {
            $sql = "SELECT a.id, a.run, a.codver, a.nombre, a.apepat, a.apemat,
                       m.id AS matricula_id, c.id AS curso_id, c.nombre AS curso_nombre
                FROM alumnos2 a
                INNER JOIN matriculas2 m ON m.alumno_id = a.id
                INNER JOIN cursos2 c ON c.id = m.curso_id
                WHERE m.anio_id = :anio_id
                AND a.id = :term
                LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':anio_id' => $anio_id, ':term' => $term]);
        } else {
            $sql = "SELECT a.id, a.run, a.codver, a.nombre, a.apepat, a.apemat,
                       m.id AS matricula_id, c.id AS curso_id, c.nombre AS curso_nombre
                FROM alumnos2 a
                INNER JOIN matriculas2 m ON m.alumno_id = a.id
                INNER JOIN cursos2 c ON c.id = m.curso_id
                WHERE m.anio_id = :anio_id
                AND a.deleted_at IS NULL
                AND (
                    a.run LIKE :term
                    OR LOWER(CONCAT(a.nombre,' ',a.apepat,' ',a.apemat)) LIKE :term_lower
                )
                ORDER BY a.apepat ASC
                LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':anio_id' => $anio_id,
                ':term' => "%$term%",
                ':term_lower' => "%" . strtolower($term) . "%"
            ]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear anotación
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (alumno_id, matricula_id, asignatura_id, contenido, tipo, semestre, fecha_anotacion, notificado_apoderado)
                VALUES
                (:alumno_id, :matricula_id, :asignatura_id, :contenido, :tipo, :semestre, :fecha_anotacion, :notificado_apoderado)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':alumno_id' => $data['alumno_id'],
            ':matricula_id' => $data['matricula_id'],
            ':asignatura_id' => $data['asignatura_id'],
            ':contenido' => $data['contenido'],
            ':tipo' => $data['tipo'],
            ':semestre' => $data['semestre'],
            ':fecha_anotacion' => $data['fecha_anotacion'] ?? date('Y-m-d'),
            ':notificado_apoderado' => $data['notificado_apoderado'] ?? 'No',
        ]);
    }

    // Eliminar anotación
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Obtener una anotación por ID
    public function getById($id)
    {
        $sql = "SELECT an.*, asig.nombre AS asignatura_nombre
                FROM {$this->table} an
                INNER JOIN asignaturas2 asig ON asig.id = an.asignatura_id
                WHERE an.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}