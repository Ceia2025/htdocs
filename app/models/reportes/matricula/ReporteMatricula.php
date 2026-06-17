<?php
require_once __DIR__ . '/../../../config/Connection.php';

class ReporteMatricula
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAnios(): array
    {
        $sql = "SELECT id, anio FROM anios2 WHERE anio >= 2025 ORDER BY anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCursos(int $anioId): array
    {
        $sql = "SELECT DISTINCT c.id, c.nombre
                FROM cursos2 c
                JOIN matriculas2 m ON m.curso_id = c.id
                WHERE m.anio_id = :anio_id
                ORDER BY c.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       DETALLE DE ALUMNOS CON MATRÍCULA ACTIVA
    ========================================== */
    public function getReporteGeneral(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    c.id            AS curso_id,
                    c.nombre        AS curso,
                    al.id           AS alumno_id,
                    al.run,
                    al.apepat,
                    al.apemat,
                    al.nombre,
                    al.sexo,
                    m.numero_lista,
                    m.fecha_matricula
                FROM matriculas2 m
                JOIN alumnos2 al ON al.id = m.alumno_id
                JOIN cursos2  c  ON c.id  = m.curso_id
                WHERE m.anio_id = :anio_id
                  AND m.fecha_retiro IS NULL
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " ORDER BY c.nombre ASC,
                    CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
                    m.numero_lista ASC,
                    al.apepat ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];
        foreach ($rows as $row) {
            $resultado[$row['curso']][] = $row;
        }
        return $resultado;
    }

    /* ==========================================
       RESUMEN POR CURSO — total matriculados,
       activos y porcentaje
    ========================================== */
    public function getResumenPorCurso(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    c.id,
                    c.nombre AS curso,
                    COUNT(m.id) AS total_matriculados,
                    SUM(CASE WHEN m.fecha_retiro IS NULL THEN 1 ELSE 0 END) AS total_activos
                FROM matriculas2 m
                JOIN alumnos2 al ON al.id = m.alumno_id
                JOIN cursos2  c  ON c.id  = m.curso_id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " GROUP BY c.id, c.nombre ORDER BY c.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $r['total_matriculados'] = (int) $r['total_matriculados'];
            $r['total_activos']      = (int) $r['total_activos'];
            $r['porcentaje'] = $r['total_matriculados'] > 0
                ? $this->redondear($r['total_activos'] / $r['total_matriculados'] * 100, 1)
                : 0.0;
        }
        return $rows;
    }

    /* ==========================================
       RESUMEN GLOBAL
    ========================================== */
    public function getResumenGlobal(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    COUNT(m.id) AS total_matriculados,
                    SUM(CASE WHEN m.fecha_retiro IS NULL THEN 1 ELSE 0 END) AS total_activos
                FROM matriculas2 m
                JOIN alumnos2 al ON al.id = m.alumno_id
                JOIN cursos2  c  ON c.id  = m.curso_id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_matriculados' => 0, 'total_activos' => 0];

        $totalMat = (int) $row['total_matriculados'];
        $totalAct = (int) $row['total_activos'];

        return [
            'total_matriculados' => $totalMat,
            'total_activos'      => $totalAct,
            'porcentaje' => $totalMat > 0 ? $this->redondear($totalAct / $totalMat * 100, 1) : 0.0,
        ];
    }

    private function redondear(float $valor, int $decimales = 1): float
    {
        $factor = 10 ** $decimales;
        return round($valor * $factor + 1e-9) / $factor;
    }
}