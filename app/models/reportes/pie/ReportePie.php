<?php
require_once __DIR__ . '/../../../config/Connection.php';

class ReportePie
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
       DETALLE — solo alumnos con pie = 'Si'
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
                    ae.prob_apren,
                    ae.eva_psico
                FROM matriculas2 m
                JOIN alumnos2 al            ON al.id = m.alumno_id
                JOIN cursos2  c             ON c.id  = m.curso_id
                JOIN antecedente_escolar ae ON ae.alumno_id = al.id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'
                  AND ae.pie = 'Si'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " ORDER BY c.nombre ASC, al.apepat ASC, al.apemat ASC, al.nombre ASC";

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
       RESUMEN POR CURSO — solo cursos con al
       menos un alumno con pie = 'Si'
    ========================================== */
    public function getResumenPorCurso(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    c.id,
                    c.nombre AS curso,
                    COUNT(DISTINCT m.id) AS total_matriculados,
                    COUNT(DISTINCT CASE WHEN ae.pie = 'Si' THEN m.id END) AS total_pie
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

        $sql .= " GROUP BY c.id, c.nombre
                  HAVING total_pie > 0
                  ORDER BY c.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$r) {
            $r['total_matriculados'] = (int) $r['total_matriculados'];
            $r['total_pie'] = (int) $r['total_pie'];
            $r['porcentaje'] = $r['total_matriculados'] > 0
                ? $this->redondear($r['total_pie'] / $r['total_matriculados'] * 100, 1)
                : 0.0;
        }
        return $rows;
    }

    /* ==========================================
       RESUMEN POR CATEGORÍA — distribución real
       de valores de pie (No / Si / No se sabe).
       Esto sigue igual, es solo informativo.
    ========================================== */
    public function getResumenPorCategoria(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    ae.pie AS categoria,
                    COUNT(DISTINCT al.id) AS total
                FROM matriculas2 m
                JOIN alumnos2 al            ON al.id = m.alumno_id
                JOIN cursos2  c             ON c.id  = m.curso_id
                JOIN antecedente_escolar ae ON ae.alumno_id = al.id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'
                  AND ae.pie IS NOT NULL AND ae.pie != ''";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " GROUP BY ae.pie ORDER BY total DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       RESUMEN GLOBAL — solo pie = 'Si'
    ========================================== */
    public function getResumenGlobal(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                    COUNT(DISTINCT m.id) AS total_matriculados,
                    COUNT(DISTINCT CASE WHEN ae.pie = 'Si' THEN m.id END) AS total_pie
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

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_matriculados' => 0, 'total_pie' => 0];

        $totalMat = (int) $row['total_matriculados'];
        $totalPie = (int) $row['total_pie'];

        return [
            'total_matriculados' => $totalMat,
            'total_pie' => $totalPie,
            'porcentaje' => $totalMat > 0 ? $this->redondear($totalPie / $totalMat * 100, 1) : 0.0,
        ];
    }

    private function redondear(float $valor, int $decimales = 1): float
    {
        $factor = 10 ** $decimales;
        return round($valor * $factor + 1e-9) / $factor;
    }

    /* ==========================================
   NOTAS PIE — promedio general por alumno
========================================== */
    public function getNotasPie(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT
                c.nombre        AS curso,
                al.run,
                al.apepat,
                al.apemat,
                al.nombre,
                al.sexo,
                COUNT(n.id)     AS total_notas,
                AVG(n.nota)     AS promedio
            FROM matriculas2 m
            JOIN alumnos2 al            ON al.id = m.alumno_id
            JOIN cursos2  c             ON c.id  = m.curso_id
            JOIN antecedente_escolar ae ON ae.alumno_id = al.id
            LEFT JOIN alum_notas2 n     ON n.matricula_id = m.id
            WHERE m.anio_id = :anio_id
              AND al.deleted_at IS NULL
              AND c.nombre != 'Test para cursos'
              AND ae.pie = 'Si'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " GROUP BY m.id, c.nombre, al.run, al.apepat, al.apemat, al.nombre, al.sexo
              ORDER BY c.nombre ASC, al.apepat ASC, al.apemat ASC, al.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar por curso y aplicar redondeo correcto
        $resultado = [];
        foreach ($rows as $row) {
            $row['promedio'] = $row['promedio'] !== null
                ? $this->redondear((float) $row['promedio'], 1)
                : null;
            $resultado[$row['curso']][] = $row;
        }
        return $resultado;
    }
}