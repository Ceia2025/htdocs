<?php
require_once __DIR__ . '/../config/Connection.php';

class Asistencia
{
    private $conn;
    private $table = "alum_asistencia2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getFechasAnio($anioId)
    {
        $sql = "SELECT anio, sem1_inicio, sem1_fin, sem2_inicio, sem2_fin
            FROM anios2
            WHERE id = :id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $anioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fechas que ya tienen asistencia registrada para un curso
    public function getFechasConAsistencia($cursoId, $anioId)
    {
        $sql = "SELECT DISTINCT a.fecha
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id = :anio_id
            ORDER BY a.fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $cursoId, ':anio_id' => $anioId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'fecha');
    }

    /* ==========================================
       OBTENER ALUMNOS POR CURSO (PARA MASIVA)
    ========================================== */
    public function getAlumnosPorCurso($cursoId, $anioId)
    {
        $sql = "SELECT 
                m.id as matricula_id,
                m.fecha_matricula,
                m.numero_lista,
                a.nombre,
                a.apepat,
                a.apemat
            FROM matriculas2 m
            JOIN alumnos2 a ON a.id = m.alumno_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id = :anio_id
            AND a.deleted_at IS NULL
            ORDER BY 
                CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
                m.numero_lista ASC,
                a.apepat ASC, 
                a.apemat ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /* ==========================================
       CURSOS CON MATRÍCULAS
    ========================================== */
    public function getCursosConMatricula($anioId)
    {
        $sql = "SELECT DISTINCT c.id, c.nombre
            FROM cursos2 c
            JOIN matriculas2 m ON m.curso_id = c.id
            WHERE m.anio_id = :anio_id
            ORDER BY c.nombre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       PORCENTAJE CURSO
    ========================================== */
    public function porcentajeCurso($cursoId, $anioId, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT 
                    COUNT(*) as total_clases,
                    SUM(a.presente) as total_presentes
                FROM {$this->table} a
                JOIN matriculas2 m ON m.id = a.matricula_id
                WHERE m.curso_id = :curso_id
                AND m.anio_id = :anio_id
                AND a.fecha BETWEEN :inicio AND :fin";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':inicio' => $fechaInicio,
            ':fin' => $fechaFin
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data || $data['total_clases'] == 0) {
            return 0;
        }

        return round(($data['total_presentes'] / $data['total_clases']) * 100, 2);
    }

    public function getAnioActualId()
    {
        $anioActual = (int) date("Y");

        $sql = "SELECT id FROM anios2 WHERE anio = :anio LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio' => $anioActual]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['id'] ?? null;
    }

    public function getAnios()
    {
        $sql = "SELECT id, anio 
            FROM anios2 
            WHERE anio >= 2025
            ORDER BY anio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurso($cursoId)
    {
        $sql = "SELECT id, nombre 
            FROM cursos2
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id' => $cursoId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarAsistencia($matriculaId, $fecha, $presente)
    {
        $sql = "INSERT INTO alum_asistencia2 (matricula_id, fecha, presente)
        VALUES (:matricula_id, :fecha, :presente)
        ON DUPLICATE KEY UPDATE
        presente = :presente";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':matricula_id' => $matriculaId,
            ':fecha' => $fecha,
            ':presente' => $presente
        ]);
    }


    // LIBRO DE CLASES DIGITAL
    public function getResumenAsistenciaCurso($cursoId, $anioId)
    {
        $sql = "
    SELECT 
        m.id as matricula_id,
        al.nombre,
        al.apepat,
        al.apemat,

        COUNT(a.id) as total_clases,

        SUM(CASE 
            WHEN a.presente = 1 THEN 1 
            ELSE 0 
        END) as presentes

    FROM matriculas2 m

    JOIN alumnos2 al
    ON al.id = m.alumno_id

    LEFT JOIN alum_asistencia2 a
    ON a.matricula_id = m.id

    WHERE m.curso_id = :curso_id
    AND m.anio_id = :anio_id
    AND al.deleted_at IS NULL

    GROUP BY m.id

    ORDER BY al.apepat, al.apemat
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAsistenciaLibro($cursoId, $anioId)
    {
        $sql = "SELECT
        a.matricula_id,
        a.fecha,
        a.presente
        FROM alum_asistencia2 a

        JOIN matriculas2 m
        ON m.id = a.matricula_id

        WHERE m.curso_id = :curso
        AND m.anio_id = :anio";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':curso' => $cursoId,
            ':anio' => $anioId
        ]);

        $data = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {

            $data[$row['matricula_id']][$row['fecha']] = $row['presente'];

        }

        return $data;

    }

    public function getDetalleAsistenciaPorAnio($anioId)
    {
        $sql = "SELECT 
                m.id as matricula_id,
                al.nombre,
                al.apepat,
                al.apemat,
                c.nombre as nombre_curso,
                a.fecha,
                a.presente
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            JOIN alumnos2 al ON al.id = m.alumno_id
            JOIN cursos2 c ON c.id = m.curso_id
            WHERE m.anio_id = :anio_id
            AND al.deleted_at IS NULL
            ORDER BY c.nombre ASC, al.apepat ASC, a.fecha ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // En Asistencia.php
    public function getAsistenciaPorFecha($cursoId, $anioId, $fecha)
    {
        $sql = "SELECT a.matricula_id, a.presente
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id = :anio_id
            AND a.fecha = :fecha";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':fecha' => $fecha
        ]);

        // Retorna un mapa: matricula_id => presente (1 o 0)
        $resultado = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultado[$row['matricula_id']] = (int) $row['presente'];
        }
        return $resultado;
    }




}