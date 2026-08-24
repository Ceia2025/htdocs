<?php
require_once __DIR__ . '/../../../config/Connection.php';

class ReporteProcedenciaAlumnos
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
       DETALLE DE ALUMNOS AGRUPADOS POR PROCEDENCIA
       (colegio de origen). Incluye tanto matriculados
       activos como retirados, según filtro de estado.
       $estado: null (todos) | 'matriculados' | 'retirados'
    ========================================== */
    public function getReporteGeneral(int $anioId, ?int $cursoId = null, ?string $estado = null): array
    {
        $sql = "SELECT
                    COALESCE(NULLIF(TRIM(ae.procedencia_colegio), ''), 'Sin registro') AS procedencia_colegio,
                    COALESCE(NULLIF(TRIM(ae.comuna), ''), 'Sin registro')              AS comuna,
                    c.id            AS curso_id,
                    c.nombre        AS curso,
                    al.id           AS alumno_id,
                    al.run,
                    al.apepat,
                    al.apemat,
                    al.nombre,
                    al.sexo,
                    m.numero_lista,
                    m.fecha_matricula,
                    m.fecha_retiro,
                    CASE WHEN m.fecha_retiro IS NULL THEN 'Matriculado' ELSE 'Retirado' END AS estado
                FROM matriculas2 m
                JOIN alumnos2 al ON al.id = m.alumno_id
                JOIN cursos2  c  ON c.id  = m.curso_id
                LEFT JOIN antecedente_escolar ae ON ae.alumno_id = al.id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        if ($estado === 'matriculados') {
            $sql .= " AND m.fecha_retiro IS NULL";
        } elseif ($estado === 'retirados') {
            $sql .= " AND m.fecha_retiro IS NOT NULL";
        }

        $sql .= " ORDER BY procedencia_colegio ASC,
                    c.nombre ASC,
                    CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
                    m.numero_lista ASC,
                    al.apepat ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];
        foreach ($rows as $row) {
            $resultado[$row['procedencia_colegio']][] = $row;
        }
        return $resultado;
    }

    /* ==========================================
       RESUMEN POR PROCEDENCIA — total, matriculados,
       retirados y % de matrícula activa por colegio
       de origen
    ========================================== */
    public function getResumenPorProcedencia(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    COALESCE(NULLIF(TRIM(ae.procedencia_colegio), ''), 'Sin registro') AS procedencia_colegio,
                    COUNT(m.id) AS total,
                    SUM(CASE WHEN m.fecha_retiro IS NULL THEN 1 ELSE 0 END) AS total_matriculados,
                    SUM(CASE WHEN m.fecha_retiro IS NOT NULL THEN 1 ELSE 0 END) AS total_retirados
                FROM matriculas2 m
                JOIN alumnos2 al ON al.id = m.alumno_id
                JOIN cursos2  c  ON c.id  = m.curso_id
                LEFT JOIN antecedente_escolar ae ON ae.alumno_id = al.id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " GROUP BY procedencia_colegio ORDER BY total DESC, procedencia_colegio ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $r['total'] = (int) $r['total'];
            $r['total_matriculados'] = (int) $r['total_matriculados'];
            $r['total_retirados'] = (int) $r['total_retirados'];
            $r['porcentaje'] = $r['total'] > 0
                ? $this->redondear($r['total_matriculados'] / $r['total'] * 100, 1)
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
                    COUNT(m.id) AS total,
                    SUM(CASE WHEN m.fecha_retiro IS NULL THEN 1 ELSE 0 END) AS total_matriculados,
                    SUM(CASE WHEN m.fecha_retiro IS NOT NULL THEN 1 ELSE 0 END) AS total_retirados
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
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'total_matriculados' => 0, 'total_retirados' => 0];

        $total = (int) $row['total'];
        $matric = (int) $row['total_matriculados'];
        $retirado = (int) $row['total_retirados'];

        return [
            'total' => $total,
            'total_matriculados' => $matric,
            'total_retirados' => $retirado,
            'porcentaje' => $total > 0 ? $this->redondear($matric / $total * 100, 1) : 0.0,
        ];
    }

    private function redondear(float $valor, int $decimales = 1): float
    {
        $factor = 10 ** $decimales;
        return round($valor * $factor + 1e-9) / $factor;
    }
}