<?php
require_once __DIR__ . '/../config/Connection.php';

class Retiro
{
    private $conn;
    private $table = 'retiros2';

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // ─── LECTURA ────────────────────────────────────────────────────────────────

    public function getAll(array $filtros = []): array
    {
        $sql = "SELECT r.*,
                       a.nombre, a.apepat, a.apemat, a.run,
                       c.nombre AS curso,
                       an.anio
                FROM   {$this->table} r
                JOIN   matriculas2 m  ON m.id  = r.matricula_id
                JOIN   alumnos2   a  ON a.id   = m.alumno_id
                JOIN   cursos2    c  ON c.id   = m.curso_id
                JOIN   anios2     an ON an.id  = m.anio_id
                WHERE  r.deleted_at IS NULL";

        $params = [];

        if (!empty($filtros['anio_id'])) {
            $sql .= " AND m.anio_id = :anio_id";
            $params[':anio_id'] = $filtros['anio_id'];
        }
        if (!empty($filtros['curso_id'])) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $filtros['curso_id'];
        }
        if (!empty($filtros['alumno_id'])) {
            $sql .= " AND m.alumno_id = :alumno_id";
            $params[':alumno_id'] = $filtros['alumno_id'];
        }
        if (!empty($filtros['semestre'])) {
            $sql .= " AND r.semestre = :semestre";
            $params[':semestre'] = $filtros['semestre'];
        }
        if (isset($filtros['justificado']) && $filtros['justificado'] !== '') {
            $sql .= " AND r.justificado = :justificado";
            $params[':justificado'] = $filtros['justificado'];
        }
        if (isset($filtros['extraordinario']) && $filtros['extraordinario'] !== '') {
            $sql .= " AND r.extraordinario = :extraordinario";
            $params[':extraordinario'] = $filtros['extraordinario'];
        }

        $sql .= " ORDER BY r.fecha_retiro DESC, r.hora_retiro DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        $sql = "SELECT r.*,
                       a.nombre, a.apepat, a.apemat, a.run,
                       c.nombre AS curso,
                       an.anio,
                       m.alumno_id, m.curso_id, m.anio_id
                FROM   {$this->table} r
                JOIN   matriculas2 m  ON m.id  = r.matricula_id
                JOIN   alumnos2   a  ON a.id   = m.alumno_id
                JOIN   cursos2    c  ON c.id   = m.curso_id
                JOIN   anios2     an ON an.id  = m.anio_id
                WHERE  r.id = :id AND r.deleted_at IS NULL";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ─── ESTADÍSTICAS ───────────────────────────────────────────────────────────

    public function getResumenPorSemestre(array $filtros = []): array
    {
        $sql = "SELECT r.semestre,
                       COUNT(*)                                    AS total,
                       SUM(r.justificado = 'Si')                  AS justificados,
                       SUM(r.justificado = 'No')                  AS injustificados,
                       SUM(r.extraordinario = 'Si')               AS extraordinarios,
                       ROUND(COUNT(*) * 100.0 /
                             NULLIF(SUM(COUNT(*)) OVER (), 0), 1) AS porcentaje
                FROM   {$this->table} r
                JOIN   matriculas2 m ON m.id = r.matricula_id
                WHERE  r.deleted_at IS NULL";

        $params = [];
        if (!empty($filtros['anio_id'])) {
            $sql .= " AND m.anio_id = :anio_id";
            $params[':anio_id'] = $filtros['anio_id'];
        }
        if (!empty($filtros['curso_id'])) {
            $sql .= " AND m.curso_id = :curso_id";
            $params[':curso_id'] = $filtros['curso_id'];
        }

        $sql .= " GROUP BY r.semestre ORDER BY r.semestre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResumenPorMotivo(array $filtros = []): array
    {
        $sql = "SELECT r.motivo,
                       COUNT(*) AS total,
                       SUM(r.justificado = 'Si')    AS justificados,
                       SUM(r.justificado = 'No')    AS injustificados,
                       SUM(r.extraordinario = 'Si') AS extraordinarios
                FROM   {$this->table} r
                JOIN   matriculas2 m ON m.id = r.matricula_id
                WHERE  r.deleted_at IS NULL";

        $params = [];
        if (!empty($filtros['anio_id'])) {
            $sql .= " AND m.anio_id = :anio_id";
            $params[':anio_id'] = $filtros['anio_id'];
        }
        if (!empty($filtros['semestre'])) {
            $sql .= " AND r.semestre = :semestre";
            $params[':semestre'] = $filtros['semestre'];
        }

        $sql .= " GROUP BY r.motivo ORDER BY total DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResumenPorCurso(array $filtros = []): array
    {
        $sql = "SELECT c.id AS curso_id, c.nombre AS curso,
                       COUNT(*) AS total,
                       SUM(r.justificado = 'Si')    AS justificados,
                       SUM(r.justificado = 'No')    AS injustificados,
                       SUM(r.extraordinario = 'Si') AS extraordinarios
                FROM   {$this->table} r
                JOIN   matriculas2 m ON m.id  = r.matricula_id
                JOIN   cursos2    c  ON c.id  = m.curso_id
                WHERE  r.deleted_at IS NULL";

        $params = [];
        if (!empty($filtros['anio_id'])) {
            $sql .= " AND m.anio_id = :anio_id";
            $params[':anio_id'] = $filtros['anio_id'];
        }
        if (!empty($filtros['semestre'])) {
            $sql .= " AND r.semestre = :semestre";
            $params[':semestre'] = $filtros['semestre'];
        }

        $sql .= " GROUP BY c.id, c.nombre ORDER BY total DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopAlumnos(array $filtros = [], int $limit = 0): array
    {
        $sql = "SELECT m.alumno_id,
                       a.nombre, a.apepat, a.apemat, a.run,
                       c.nombre AS curso,
                       COUNT(*)                     AS total,
                       SUM(r.justificado = 'No')    AS injustificados,
                       SUM(r.extraordinario = 'Si') AS extraordinarios
                FROM   {$this->table} r
                JOIN   matriculas2 m ON m.id  = r.matricula_id
                JOIN   alumnos2   a  ON a.id  = m.alumno_id
                JOIN   cursos2    c  ON c.id  = m.curso_id
                WHERE  r.deleted_at IS NULL";

        $params = [];
        if (!empty($filtros['anio_id'])) {
            $sql .= " AND m.anio_id = :anio_id";
            $params[':anio_id'] = $filtros['anio_id'];
        }
        if (!empty($filtros['semestre'])) {
            $sql .= " AND r.semestre = :semestre";
            $params[':semestre'] = $filtros['semestre'];
        }

        $sql .= " GROUP BY m.alumno_id, a.nombre, a.apepat, a.apemat, a.run, c.nombre
                  ORDER BY total DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMediaRetiros(int $anio_id): float
    {
        $sql = "SELECT AVG(sub.total) AS media
                FROM (
                    SELECT m.alumno_id, COUNT(*) AS total
                    FROM   {$this->table} r
                    JOIN   matriculas2 m ON m.id = r.matricula_id
                    WHERE  m.anio_id = :anio_id AND r.deleted_at IS NULL
                    GROUP  BY m.alumno_id
                ) sub";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anio_id]);
        return (float) ($stmt->fetchColumn() ?? 0);
    }

    // ─── ESCRITURA ───────────────────────────────────────────────────────────────

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table}
                    (matricula_id, fecha_retiro, hora_retiro, motivo, observacion,
                     justificado, extraordinario, quien_retira, semestre, registrado_por)
                VALUES
                    (:matricula_id, :fecha_retiro, :hora_retiro, :motivo, :observacion,
                     :justificado, :extraordinario, :quien_retira, :semestre, :registrado_por)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':matricula_id' => $data['matricula_id'],
            ':fecha_retiro' => $data['fecha_retiro'],
            ':hora_retiro' => $data['hora_retiro'],
            ':motivo' => $data['motivo'],
            ':observacion' => $data['observacion'] ?? null,
            ':justificado' => $data['justificado'],
            ':extraordinario' => $data['extraordinario'],
            ':quien_retira' => $data['quien_retira'] ?? null,
            ':semestre' => $data['semestre'],
            ':registrado_por' => $data['registrado_por'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET    fecha_retiro   = :fecha_retiro,
                       hora_retiro    = :hora_retiro,
                       motivo         = :motivo,
                       observacion    = :observacion,
                       justificado    = :justificado,
                       extraordinario = :extraordinario,
                       quien_retira   = :quien_retira,
                       semestre       = :semestre
                WHERE  id = :id AND deleted_at IS NULL";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':fecha_retiro' => $data['fecha_retiro'],
            ':hora_retiro' => $data['hora_retiro'],
            ':motivo' => $data['motivo'],
            ':observacion' => $data['observacion'] ?? null,
            ':justificado' => $data['justificado'],
            ':extraordinario' => $data['extraordinario'],
            ':quien_retira' => $data['quien_retira'] ?? null,
            ':semestre' => $data['semestre'],
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    // ─── AUXILIARES ──────────────────────────────────────────────────────────────

    public function getMatriculaId(int $alumno_id, int $anio_id): int|false
    {
        $sql = "SELECT id FROM matriculas2
                 WHERE  alumno_id = :alumno_id AND anio_id = :anio_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id, ':anio_id' => $anio_id]);
        return $stmt->fetchColumn();
    }

    public function calcularSemestre(string $fecha, array $anio): int
    {
        $d = strtotime($fecha);
        if (
            $anio['sem1_inicio'] && $anio['sem1_fin']
            && $d >= strtotime($anio['sem1_inicio'])
            && $d <= strtotime($anio['sem1_fin'])
        ) {
            return 1;
        }
        return 2;
    }

    public function getContactosEmergencia(int $alumno_id): array
    {
        $sql = "SELECT id, nombre_contacto, relacion, telefono
            FROM   alum_emergencia2
            WHERE  alumno_id = :alumno_id
            ORDER  BY relacion";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}