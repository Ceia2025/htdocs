<?php
require_once __DIR__ . '/../../../config/Connection.php';

class ReporteEtnia
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
       ETNIAS SIN PUEBLO ORIGINARIO (para excluir)
    ========================================== */
    public const SIN_ETNIA = [
        'No pertenece a ningún Pueblo Originario',
        'No Registra',
    ];

    /* ==========================================
       LISTA DE ETNIAS DISPONIBLES (valores del ENUM)
       Solo pueblos originarios reales
    ========================================== */
    public function getEtnias(): array
    {
        return [
            'Aymara',
            'Likanantai( Atacameño )',
            'Colla',
            'Diaguita',
            'Quechua',
            'Rapa Nui',
            'Mapuche',
            'Kawésqar',
            'Yagán',
            'Otro',
        ];
    }

    /* ==========================================
       REPORTE POR ETNIA — separado por curso
       Devuelve: [ 'NombreCurso' => [ alumno, ... ], ... ]
       Cada alumno incluye cod_etnia
       Filtro opcional por curso_id y/o etnia
    ========================================== */
    public function getReporteGeneral(int $anioId, ?int $cursoId = null, ?string $etnia = null): array
    {
        $sql = "SELECT
                    c.id        AS curso_id,
                    c.nombre    AS curso,
                    al.id       AS alumno_id,
                    al.run,
                    al.apepat,
                    al.apemat,
                    al.nombre,
                    al.sexo,
                    al.cod_etnia
                FROM matriculas2 m
                JOIN alumnos2 al  ON al.id = m.alumno_id
                JOIN cursos2  c   ON c.id  = m.curso_id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'
                  ";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        if ($etnia !== null && $etnia !== '') {
            $sql .= " AND al.cod_etnia = :etnia";
            $params[':etnia'] = $etnia;
        } else {
            // Excluir siempre los que no tienen pueblo originario
            $sql .= " AND al.cod_etnia NOT IN ('No pertenece a ningún Pueblo Originario', 'No Registra')";
        }

        $sql .= " ORDER BY c.nombre ASC, al.apepat ASC, al.apemat ASC, al.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar por curso
        $resultado = [];
        foreach ($rows as $row) {
            $resultado[$row['curso']][] = $row;
        }

        return $resultado;
    }

    /* ==========================================
       RESUMEN POR CURSO — conteo de alumnos por etnia
    ========================================== */
    public function getResumenPorCurso(int $anioId, ?int $cursoId = null, ?string $etnia = null): array
    {
        $sql = "SELECT
                    c.id,
                    c.nombre AS curso,
                    al.cod_etnia,
                    COUNT(al.id) AS total
                FROM matriculas2 m
                JOIN alumnos2 al ON al.id = m.alumno_id
                JOIN cursos2  c  ON c.id  = m.curso_id
                WHERE m.anio_id = :anio_id
                  AND al.deleted_at IS NULL
                  AND c.nombre != 'Test para cursos'
                  ";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        if ($etnia !== null && $etnia !== '') {
            $sql .= " AND al.cod_etnia = :etnia";
            $params[':etnia'] = $etnia;
        }

        $sql .= " GROUP BY c.id, al.cod_etnia
                  ORDER BY c.nombre ASC, total DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Estructurar como [ curso => [ etnia => total, ... ], ... ]
        $resultado = [];
        foreach ($rows as $row) {
            $resultado[$row['curso']][$row['cod_etnia']] = (int) $row['total'];
        }

        return $resultado;
    }

    /* ==========================================
       RESUMEN GLOBAL — total de alumnos por etnia (todos los cursos)
       Excluye alumnos sin pueblo originario
    ========================================== */
    public function getResumenGlobal(int $anioId, ?int $cursoId = null, ?string $etnia = null): array
    {
        $sql = "SELECT
                al.cod_etnia,
                COUNT(al.id) AS total
            FROM matriculas2 m
            JOIN alumnos2 al  ON al.id = m.alumno_id
            JOIN cursos2  c   ON c.id  = m.curso_id
            WHERE m.anio_id = :anio_id
              AND al.deleted_at IS NULL
              AND al.cod_etnia NOT IN ('No pertenece a ningún Pueblo Originario', 'No Registra')
              AND c.nombre != 'Test para cursos'";

        $params = [':anio_id' => $anioId];

        if ($cursoId) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $cursoId;
        }

        if ($etnia !== null && $etnia !== '') {
            $sql .= " AND al.cod_etnia = :etnia";
            $params[':etnia'] = $etnia;
        }

        $sql .= " GROUP BY al.cod_etnia ORDER BY total DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}