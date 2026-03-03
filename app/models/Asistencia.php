<?php

class Asistencia
{
    private $conn;
    private $table = "alum_asistencia2";

    public function __construct($db)
    {
        $this->conn = $db;
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
            ':anio_id'  => $anioId
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
            ':anio_id'  => $anioId
        ]);

        $matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($matriculas as $m) {

            $matriculaId = $m['id'];
            $presente    = $asistencias[$matriculaId] ?? 0;

            $insert = "INSERT INTO {$this->table}
                       (matricula_id, fecha, presente)
                       VALUES (:matricula_id, :fecha, :presente)
                       ON DUPLICATE KEY UPDATE presente = VALUES(presente)";

            $stmtInsert = $this->conn->prepare($insert);
            $stmtInsert->execute([
                ':matricula_id' => $matriculaId,
                ':fecha'        => $fecha,
                ':presente'     => $presente
            ]);
        }
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
            ':anio_id'  => $anioId,
            ':inicio'   => $fechaInicio,
            ':fin'      => $fechaFin
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data || $data['total_clases'] == 0) {
            return 0;
        }

        return round(($data['total_presentes'] / $data['total_clases']) * 100, 2);
    }
}