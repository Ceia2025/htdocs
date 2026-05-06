<?php
require_once __DIR__ . '/../../config/Connection.php';

class ReporteAsistencia
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /* ==========================================
       AÑOS DISPONIBLES
    ========================================== */
    public function getAnios(): array
    {
        $sql = "SELECT id, anio FROM anios2 WHERE anio >= 2025 ORDER BY anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       CURSOS CON MATRÍCULA EN UN AÑO
    ========================================== */
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
       MESES CON ASISTENCIA REGISTRADA EN UN CURSO
    ========================================== */
    public function getMesesConAsistencia(int $anioId, ?int $cursoId = null): array
    {
        $sql = "SELECT DISTINCT DATE_FORMAT(a.fecha, '%Y-%m') AS mes_key,
                   DATE_FORMAT(a.fecha, '%Y')             AS anio,
                   DATE_FORMAT(a.fecha, '%m')             AS mes_num
            FROM alum_asistencia2 a
            JOIN matriculas2 m ON m.id = a.matricula_id
            WHERE m.anio_id = :anio_id";
        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        $sql .= " ORDER BY mes_key ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $mesesES = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];

        $resultado = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $resultado[] = [
                'mes_key' => $row['mes_key'],
                'mes_nombre' => $mesesES[$row['mes_num']] . ' ' . $row['anio'],
            ];
        }

        return $resultado;
    }

    /* ==========================================
       REPORTE POR CURSO: asistencia mensual + acumulada
       Devuelve un array con cada alumno, su % mensual y acumulado
    ========================================== */
    public function getReportePorCurso(int $anioId, int $cursoId, ?string $mesKey = null): array
    {
        // Asistencia acumulada del año
        $sqlAcum = "SELECT
                        m.id        AS matricula_id,
                        al.apepat,
                        al.apemat,
                        al.nombre,
                        al.run,
                        c.nombre    AS curso,
                        COUNT(a.id)            AS total_clases,
                        SUM(a.presente = 1)    AS presentes_acum
                    FROM matriculas2 m
                    JOIN alumnos2 al ON al.id = m.alumno_id
                    JOIN cursos2  c  ON c.id  = m.curso_id
                    LEFT JOIN alum_asistencia2 a ON a.matricula_id = m.id
                        AND (m.fecha_matricula IS NULL OR a.fecha >= m.fecha_matricula)
                    WHERE m.anio_id  = :anio_id
                      AND m.curso_id = :curso_id
                      AND al.deleted_at IS NULL
                    GROUP BY m.id
                    ORDER BY al.apepat ASC, al.apemat ASC, al.nombre ASC";

        $stmt = $this->conn->prepare($sqlAcum);
        $stmt->execute([':anio_id' => $anioId, ':curso_id' => $cursoId]);
        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si se seleccionó un mes, calcular asistencia mensual
        $asistenciaMes = [];
        if ($mesKey) {
            $sqlMes = "SELECT
                            a.matricula_id,
                            COUNT(a.id)         AS total_mes,
                            SUM(a.presente = 1) AS presentes_mes
                       FROM alum_asistencia2 a
                       JOIN matriculas2 m ON m.id = a.matricula_id
                       WHERE m.anio_id   = :anio_id
                         AND m.curso_id  = :curso_id
                         AND DATE_FORMAT(a.fecha, '%Y-%m') = :mes
                       GROUP BY a.matricula_id";
            $stmtMes = $this->conn->prepare($sqlMes);
            $stmtMes->execute([
                ':anio_id' => $anioId,
                ':curso_id' => $cursoId,
                ':mes' => $mesKey,
            ]);
            foreach ($stmtMes->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $asistenciaMes[$row['matricula_id']] = $row;
            }
        }

        // Combinar datos
        foreach ($alumnos as &$al) {
            $mid = $al['matricula_id'];

            // Acumulado
            $al['pct_acumulado'] = $al['total_clases'] > 0
                ? round(($al['presentes_acum'] / $al['total_clases']) * 100, 1)
                : null;

            // Mensual
            if ($mesKey && isset($asistenciaMes[$mid])) {
                $m = $asistenciaMes[$mid];
                $al['total_mes'] = $m['total_mes'];
                $al['presentes_mes'] = $m['presentes_mes'];
                $al['pct_mes'] = $m['total_mes'] > 0
                    ? round(($m['presentes_mes'] / $m['total_mes']) * 100, 1)
                    : null;
            } else {
                $al['total_mes'] = null;
                $al['presentes_mes'] = null;
                $al['pct_mes'] = null;
            }
        }

        return $alumnos;
    }

    /* ==========================================
       REPORTE GENERAL: todos los cursos
    ========================================== */
    public function getReporteGeneral(int $anioId, ?string $mesKey = null): array
    {
        $cursos = $this->getCursos($anioId);
        $resultado = [];

        foreach ($cursos as $curso) {
            $alumnos = $this->getReportePorCurso($anioId, $curso['id'], $mesKey);
            if (!empty($alumnos)) {
                $resultado[$curso['nombre']] = $alumnos;
            }
        }

        return $resultado;
    }

    /* ==========================================
       RESUMEN POR CURSO (para la vista general)
    ========================================== */
    public function getResumenCursos(int $anioId, ?string $mesKey = null): array
    {
        $sql = "SELECT
                    c.id,
                    c.nombre AS curso,
                    COUNT(DISTINCT m.id)        AS total_alumnos,
                    COUNT(a.id)                 AS total_clases,
                    SUM(a.presente = 1)         AS total_presentes
                FROM cursos2 c
                JOIN matriculas2 m     ON m.curso_id = c.id AND m.anio_id = :anio_id
                JOIN alumnos2 al       ON al.id = m.alumno_id AND al.deleted_at IS NULL
                LEFT JOIN alum_asistencia2 a ON a.matricula_id = m.id";

        $params = [':anio_id' => $anioId];

        if ($mesKey) {
            $sql .= " AND DATE_FORMAT(a.fecha, '%Y-%m') = :mes";
            $params[':mes'] = $mesKey;
        }

        $sql .= " GROUP BY c.id ORDER BY c.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as &$row) {
            $row['pct'] = $row['total_clases'] > 0
                ? round(($row['total_presentes'] / $row['total_clases']) * 100, 1)
                : null;
        }

        return $rows;
    }
}