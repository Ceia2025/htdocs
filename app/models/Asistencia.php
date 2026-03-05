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

    /* ==========================================
       OBTENER ALUMNOS POR CURSO (PARA MASIVA)
    ========================================== */
    public function getAlumnosPorCurso($cursoId, $anioId)
    {
        $sql = "SELECT 
                    m.id as matricula_id,
                    a.nombre,
                    a.apepat,
                    a.apemat
                FROM matriculas2 m
                JOIN alumnos2 a ON a.id = m.alumno_id
                WHERE m.curso_id = :curso_id
                AND m.anio_id = :anio_id
                AND a.deleted_at IS NULL
                ORDER BY a.apepat, a.apemat";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       GUARDAR ASISTENCIA MASIVA
    ========================================== */
    public function guardarAsistenciaMasiva($cursoId, $anioId, $fecha, $asistencias)
    {
        $sql = "SELECT id FROM matriculas2 
                WHERE curso_id = :curso_id 
                AND anio_id = :anio_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId
        ]);

        $matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($matriculas as $m) {

            $matriculaId = $m['id'];
            $presente = $asistencias[$matriculaId] ?? 0;

            $insert = "INSERT INTO {$this->table}
                       (matricula_id, fecha, presente)
                       VALUES (:matricula_id, :fecha, :presente)
                       ON DUPLICATE KEY UPDATE presente = VALUES(presente)";

            $stmtInsert = $this->conn->prepare($insert);
            $stmtInsert->execute([
                ':matricula_id' => $matriculaId,
                ':fecha' => $fecha,
                ':presente' => $presente
            ]);
        }
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

    public function guardarAsistencia($alumnoId, $cursoId, $anioId, $fecha, $presente)
    {
        $sql = "INSERT INTO alum_asistencia2 
            (alumno_id, curso_id, anio_id, fecha, presente)
            VALUES (:alumno_id, :curso_id, :anio_id, :fecha, :presente)
            ON DUPLICATE KEY UPDATE presente = :presente";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':alumno_id' => $alumnoId,
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':fecha' => $fecha,
            ':presente' => $presente
        ]);
    }
}