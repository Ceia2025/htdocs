<?php
require_once __DIR__ . '/../config/Connection.php';

class Atraso
{
    private $conn;

    // Rangos de semestres (ajusta si cambian las fechas)
    const SEMESTRES = [
        1 => ['inicio' => '-03-04', 'fin' => '-06-18'],
        2 => ['inicio' => '-07-06', 'fin' => '-11-24'],
    ];

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /* ==========================================
       DETECTAR SEMESTRE SEGÚN FECHA
    ========================================== */
    public function getSemestreDeFecha(string $fecha): int
    {
        $anio = date('Y', strtotime($fecha));
        $d = new DateTime($fecha);

        $s1inicio = new DateTime($anio . self::SEMESTRES[1]['inicio']);
        $s1fin = new DateTime($anio . self::SEMESTRES[1]['fin']);
        $s2inicio = new DateTime($anio . self::SEMESTRES[2]['inicio']);
        $s2fin = new DateTime($anio . self::SEMESTRES[2]['fin']);

        if ($d >= $s1inicio && $d <= $s1fin)
            return 1;
        if ($d >= $s2inicio && $d <= $s2fin)
            return 2;

        return 1; // fallback
    }

    /* ==========================================
       REGISTRAR ATRASO
    ========================================== */
    public function registrar(int $matriculaId, string $fecha, string $horaLlegada, int $justificado = 0, string $observacion = '', ?int $registradoPor = null): bool
    {
        $semestre = $this->getSemestreDeFecha($fecha);

        $sql = "INSERT INTO alum_atrasos
                    (matricula_id, semestre, fecha, hora_llegada, justificado, observacion, registrado_por)
                VALUES
                    (:matricula_id, :semestre, :fecha, :hora_llegada, :justificado, :observacion, :registrado_por)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':matricula_id' => $matriculaId,
            ':semestre' => $semestre,
            ':fecha' => $fecha,
            ':hora_llegada' => $horaLlegada,
            ':justificado' => $justificado,
            ':observacion' => $observacion ?: null,
            ':registrado_por' => $registradoPor,
        ]);
    }

    /* ==========================================
       LISTAR ATRASOS DE UN ALUMNO (por matrícula)
    ========================================== */
    public function getByMatricula(int $matriculaId): array
    {
        $sql = "SELECT
                    aa.id,
                    aa.fecha,
                    aa.hora_llegada,
                    aa.semestre,
                    aa.justificado,
                    aa.observacion,
                    aa.created_at
                FROM alum_atrasos aa
                WHERE aa.matricula_id = :matricula_id
                ORDER BY aa.fecha DESC, aa.hora_llegada DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':matricula_id' => $matriculaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       LISTAR ATRASOS DE UN CURSO (para libro)
    ========================================== */
    public function getByCurso(int $cursoId, int $anioId, ?int $semestre = null): array
    {
        $sql = "SELECT
                    aa.id,
                    aa.fecha,
                    aa.hora_llegada,
                    aa.semestre,
                    aa.justificado,
                    aa.observacion,
                    m.id        AS matricula_id,
                    al.nombre,
                    al.apepat,
                    al.apemat,
                    al.run
                FROM alum_atrasos aa
                JOIN matriculas2 m  ON m.id  = aa.matricula_id
                JOIN alumnos2    al ON al.id = m.alumno_id
                WHERE m.curso_id = :curso_id
                  AND m.anio_id  = :anio_id";

        $params = [':curso_id' => $cursoId, ':anio_id' => $anioId];

        if ($semestre !== null) {
            $sql .= " AND aa.semestre = :semestre";
            $params[':semestre'] = $semestre;
        }

        $sql .= " ORDER BY aa.fecha DESC, al.apepat, al.apemat";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       ATRASOS DEL DÍA (para portería / registro rápido)
    ========================================== */
    public function getDelDia(?string $fecha = null): array
    {
        $fecha = $fecha ?? date('Y-m-d');

        $sql = "SELECT
                    aa.id,
                    aa.hora_llegada,
                    aa.justificado,
                    aa.observacion,
                    al.nombre,
                    al.apepat,
                    al.apemat,
                    al.run,
                    c.nombre    AS curso,
                    an.anio
                FROM alum_atrasos aa
                JOIN matriculas2 m  ON m.id   = aa.matricula_id
                JOIN alumnos2    al ON al.id  = m.alumno_id
                JOIN cursos2     c  ON c.id   = m.curso_id
                JOIN anios2      an ON an.id  = m.anio_id
                WHERE aa.fecha = :fecha
                ORDER BY aa.hora_llegada DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':fecha' => $fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       RESUMEN POR ALUMNO (conteo total / semestre)
    ========================================== */
    public function getResumenAlumno(int $matriculaId): array
    {
        $sql = "SELECT
                    semestre,
                    COUNT(*)                                   AS total,
                    SUM(justificado = 1)                       AS justificados,
                    SUM(justificado = 0)                       AS injustificados
                FROM alum_atrasos
                WHERE matricula_id = :matricula_id
                GROUP BY semestre
                ORDER BY semestre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':matricula_id' => $matriculaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       ELIMINAR ATRASO
    ========================================== */
    public function eliminar(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM alum_atrasos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /* ==========================================
       OBTENER MATRÍCULA DE UN ALUMNO EN EL AÑO ACTUAL
    ========================================== */
    public function getMatriculaActual(int $alumnoId): ?array
    {
        $sql = "SELECT m.id AS matricula_id, m.curso_id, m.anio_id, c.nombre AS curso
                FROM matriculas2 m
                JOIN cursos2 c  ON c.id  = m.curso_id
                JOIN anios2  an ON an.id = m.anio_id
                WHERE m.alumno_id = :alumno_id
                  AND an.anio     = YEAR(CURDATE())
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /* ==========================================
       BUSCAR ALUMNO POR RUN (para registro rápido)
    ========================================== */
    public function buscarAlumnoPorRun(string $run): ?array
    {
        // Limpiar el RUN: quitar puntos y guión para comparar
        $runLimpio = strtoupper(trim($run));

        $sql = "SELECT
                al.id,
                al.run,
                al.codver,
                al.nombre,
                al.apepat,
                al.apemat,
                m.id        AS matricula_id,
                c.nombre    AS curso,
                an.anio
            FROM alumnos2    al
            JOIN matriculas2 m  ON m.alumno_id = al.id
            JOIN cursos2     c  ON c.id        = m.curso_id
            JOIN anios2      an ON an.id        = m.anio_id
            WHERE (
                al.run = :run1
                OR CONCAT(al.run, '-', al.codver) = :run2
                OR CONCAT(al.run, al.codver)      = :run3
            )
            AND al.deleted_at IS NULL
            ORDER BY an.anio DESC
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':run1' => $runLimpio,
            ':run2' => $runLimpio,
            ':run3' => $runLimpio,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function buscarAlumnosPorTermino(string $termino): array
    {
        $like = '%' . $termino . '%';

        $sql = "SELECT
                al.id,
                al.run,
                al.codver,
                al.nombre,
                al.apepat,
                al.apemat,
                m.id        AS matricula_id,
                c.nombre    AS curso,
                an.anio
            FROM alumnos2    al
            JOIN matriculas2 m  ON m.alumno_id = al.id
            JOIN cursos2     c  ON c.id        = m.curso_id
            JOIN anios2      an ON an.id        = m.anio_id
            WHERE al.deleted_at IS NULL
              AND (
                    al.run    LIKE :like1
                OR  al.nombre LIKE :like2
                OR  al.apepat LIKE :like3
                OR  al.apemat LIKE :like4
                OR  CONCAT(al.apepat, ' ', al.apemat, ' ', al.nombre) LIKE :like5
              )
            ORDER BY an.anio DESC, al.apepat, al.apemat
            LIMIT 10";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':like1' => $like,
            ':like2' => $like,
            ':like3' => $like,
            ':like4' => $like,
            ':like5' => $like,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ==========================================
       CURSOS QUE TIENEN ATRASOS REGISTRADOS
       (para poblar el selector de la vista lista_curso)
    ========================================== */
    public function getCursosConAtrasos(int $anioId): array
    {
        $sql = "SELECT DISTINCT c.id, c.nombre
                FROM cursos2 c
                JOIN matriculas2 m   ON m.curso_id  = c.id
                JOIN alum_atrasos aa ON aa.matricula_id = m.id
                WHERE m.anio_id = :anio_id
                ORDER BY c.nombre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
       AÑO ACTUAL
    ========================================== */
    public function getAnioActualId(): ?int
    {
        $sql = "SELECT id FROM anios2 WHERE anio = YEAR(CURDATE()) LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null;
    }

    public function getByCursoFiltrado(
        ?int $cursoId,
        int $anioId,
        ?int $semestre = null,
        ?string $fechaDesde = null,
        ?string $fechaHasta = null
    ): array {
        $sql = "SELECT
                aa.id,
                aa.fecha,
                aa.hora_llegada,
                aa.semestre,
                aa.justificado,
                aa.observacion,
                m.id        AS matricula_id,
                m.curso_id,
                al.nombre,
                al.apepat,
                al.apemat,
                al.run,
                c.nombre    AS curso
            FROM alum_atrasos aa
            JOIN matriculas2 m  ON m.id   = aa.matricula_id
            JOIN alumnos2    al ON al.id  = m.alumno_id
            JOIN cursos2     c  ON c.id   = m.curso_id
            WHERE m.anio_id = :anio_id
              AND al.deleted_at IS NULL";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        if ($semestre) {
            $sql .= " AND aa.semestre = :semestre";
            $params[':semestre'] = $semestre;
        }

        if ($fechaDesde) {
            $sql .= " AND aa.fecha >= :fecha_desde";
            $params[':fecha_desde'] = $fechaDesde;
        }

        if ($fechaHasta) {
            $sql .= " AND aa.fecha <= :fecha_hasta";
            $params[':fecha_hasta'] = $fechaHasta;
        }

        $sql .= " ORDER BY aa.fecha DESC, al.apepat, al.apemat";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // También necesitas este método para poblar el selector de cursos con TODOS los del año
    public function getCursosConMatricula(int $anioId): array
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

    public function getResumenGeneral(int $anioId, ?int $cursoId = null, ?int $semestre = null, ?string $fechaDesde = null, ?string $fechaHasta = null): array
    {
        $where = "WHERE m.anio_id = :anio_id AND al.deleted_at IS NULL";
        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $where .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }
        if ($semestre) {
            $where .= " AND aa.semestre = :semestre";
            $params[':semestre'] = $semestre;
        }
        if ($fechaDesde) {
            $where .= " AND aa.fecha >= :fecha_desde";
            $params[':fecha_desde'] = $fechaDesde;
        }
        if ($fechaHasta) {
            $where .= " AND aa.fecha <= :fecha_hasta";
            $params[':fecha_hasta'] = $fechaHasta;
        }

        // Totales generales
        $sqlTotales = "SELECT
                COUNT(*)                    AS total,
                SUM(aa.justificado = 1)     AS justificados,
                SUM(aa.justificado = 0)     AS injustificados,
                COUNT(DISTINCT aa.fecha)    AS dias_con_atrasos,
                COUNT(DISTINCT m.id)        AS alumnos_afectados,
                COUNT(DISTINCT m.curso_id)  AS cursos_afectados,
                MIN(aa.fecha)               AS primera_fecha,
                MAX(aa.fecha)               AS ultima_fecha
            FROM alum_atrasos aa
            JOIN matriculas2 m  ON m.id  = aa.matricula_id
            JOIN alumnos2    al ON al.id = m.alumno_id
            $where";

        $stmt = $this->conn->prepare($sqlTotales);
        $stmt->execute($params);
        $totales = $stmt->fetch(PDO::FETCH_ASSOC);

        // Top 5 alumnos con más atrasos
        $sqlTopAlumnos = "SELECT
                al.apepat, al.apemat, al.nombre, al.run,
                c.nombre AS curso,
                COUNT(*) AS total,
                SUM(aa.justificado = 0) AS injustificados
            FROM alum_atrasos aa
            JOIN matriculas2 m  ON m.id  = aa.matricula_id
            JOIN alumnos2    al ON al.id = m.alumno_id
            JOIN cursos2     c  ON c.id  = m.curso_id
            $where
            GROUP BY m.id
            ORDER BY total DESC
            LIMIT 5";

        $stmt = $this->conn->prepare($sqlTopAlumnos);
        $stmt->execute($params);
        $topAlumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Atrasos por curso (para ranking de cursos)
        $sqlPorCurso = "SELECT
                c.nombre AS curso,
                COUNT(*) AS total,
                SUM(aa.justificado = 0) AS injustificados,
                COUNT(DISTINCT m.id)    AS alumnos
            FROM alum_atrasos aa
            JOIN matriculas2 m  ON m.id  = aa.matricula_id
            JOIN alumnos2    al ON al.id = m.alumno_id
            JOIN cursos2     c  ON c.id  = m.curso_id
            $where
            GROUP BY m.curso_id
            ORDER BY total DESC";

        $stmt = $this->conn->prepare($sqlPorCurso);
        $stmt->execute($params);
        $porCurso = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Atrasos por semana (para el gráfico de tendencia)
        $sqlPorSemana = "SELECT
                YEARWEEK(aa.fecha, 1)       AS semana_key,
                MIN(aa.fecha)               AS semana_inicio,
                COUNT(*)                    AS total
            FROM alum_atrasos aa
            JOIN matriculas2 m  ON m.id  = aa.matricula_id
            JOIN alumnos2    al ON al.id = m.alumno_id
            $where
            GROUP BY YEARWEEK(aa.fecha, 1)
            ORDER BY semana_key ASC";

        $stmt = $this->conn->prepare($sqlPorSemana);
        $stmt->execute($params);
        $porSemana = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'totales' => $totales,
            'topAlumnos' => $topAlumnos,
            'porCurso' => $porCurso,
            'porSemana' => $porSemana,
        ];
    }
}