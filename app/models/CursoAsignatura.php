<?php
require_once __DIR__ . '/../config/Connection.php';

/**
 * Malla curricular: qué asignaturas tiene cada curso en cada año.
 * Cada fila de curso_asignaturas2 pertenece a un año (anio_id).
 * Las notas NO dependen de esta tabla (van por matrícula + asignatura),
 * por eso cambiar la malla de un año nunca borra notas.
 */
class CursoAsignatura
{
    private $conn;
    private $table = "curso_asignaturas2";

    public function __construct()
    {
        $database = new Connection();
        $this->conn = $database->open();
    }

    /* ================================================================
       AÑO
    ================================================================ */

    /** Año académico en curso: el de este año calendario, o el último anterior que exista */
    public function anioActualId(): ?int
    {
        $sql = "SELECT id FROM anios2 WHERE anio <= YEAR(CURDATE()) ORDER BY anio DESC LIMIT 1";
        $id = $this->conn->query($sql)->fetchColumn();
        if ($id === false) {
            $id = $this->conn->query("SELECT id FROM anios2 ORDER BY anio ASC LIMIT 1")->fetchColumn();
        }
        return $id === false ? null : (int) $id;
    }

    /* ================================================================
       LECTURA
    ================================================================ */

    /**
     * Asignaturas de un curso en un año.
     * Si no se indica el año, usa el año académico en curso
     * (compatibilidad con los módulos que aún no envían el año).
     */
    public function getAsignaturasPorCurso($curso_id, $anio_id = null)
    {
        $anio_id = $anio_id ?: $this->anioActualId();

        $sql = "SELECT a.id, a.nombre
                FROM curso_asignaturas2 ca
                INNER JOIN asignaturas2 a ON ca.asignatura_id = a.id
                WHERE ca.curso_id = :curso_id
                  AND ca.anio_id = :anio_id
                ORDER BY a.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => (int) $curso_id, ':anio_id' => (int) $anio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Malla de un año (opcionalmente de un solo curso), con cuántas notas y
     * planificaciones tiene cada asignatura. Esos contadores protegen el borrado.
     */
    public function getMalla(int $anio_id, ?int $curso_id = null): array
    {
        $sql = "SELECT ca.id, ca.curso_id, ca.asignatura_id, ca.anio_id,
                       c.nombre AS curso,
                       a.nombre AS asignatura,
                       (SELECT COUNT(*)
                          FROM alum_notas2 n
                          JOIN matriculas2 m ON m.id = n.matricula_id
                         WHERE m.curso_id = ca.curso_id
                           AND m.anio_id = ca.anio_id
                           AND n.asignatura_id = ca.asignatura_id) AS total_notas,
                       (SELECT COUNT(*)
                          FROM planificaciones2 p
                         WHERE p.curso_asignatura_id = ca.id) AS total_planificaciones
                FROM {$this->table} ca
                JOIN cursos2 c ON c.id = ca.curso_id
                JOIN asignaturas2 a ON a.id = ca.asignatura_id
                WHERE ca.anio_id = :anio_id";
        $params = [':anio_id' => $anio_id];

        if ($curso_id) {
            $sql .= " AND ca.curso_id = :curso_id";
            $params[':curso_id'] = $curso_id;
        }

        $sql .= " ORDER BY c.nombre ASC, a.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Cantidad de filas de malla por año: [anio_id => total] */
    public function contarPorAnio(): array
    {
        $sql = "SELECT anio_id, COUNT(*) AS total FROM {$this->table} WHERE anio_id IS NOT NULL GROUP BY anio_id";
        $out = [];
        foreach ($this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $out[(int) $r['anio_id']] = (int) $r['total'];
        }
        return $out;
    }

    // Se mantienen por compatibilidad: ahora devuelven la malla del año en curso
    public function getAll(): array
    {
        $anio = $this->anioActualId();
        return $anio ? $this->getMalla($anio) : [];
    }

    public function getByCurso($curso_id): array
    {
        $anio = $this->anioActualId();
        return $anio ? $this->getMalla($anio, (int) $curso_id) : [];
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** ¿La asignatura ya está en la malla de ese curso y año? */
    public function existe(int $curso_id, int $asignatura_id, int $anio_id, ?int $exceptoId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}
                WHERE curso_id = :curso_id AND asignatura_id = :asignatura_id AND anio_id = :anio_id";
        $params = [':curso_id' => $curso_id, ':asignatura_id' => $asignatura_id, ':anio_id' => $anio_id];
        if ($exceptoId) {
            $sql .= " AND id <> :id";
            $params[':id'] = $exceptoId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Notas y planificaciones que dependen de una fila de malla */
    public function usoDe(int $id): array
    {
        $sql = "SELECT
                    (SELECT COUNT(*)
                       FROM alum_notas2 n
                       JOIN matriculas2 m ON m.id = n.matricula_id
                      WHERE m.curso_id = ca.curso_id
                        AND m.anio_id = ca.anio_id
                        AND n.asignatura_id = ca.asignatura_id) AS total_notas,
                    (SELECT COUNT(*) FROM planificaciones2 p WHERE p.curso_asignatura_id = ca.id) AS total_planificaciones
                FROM {$this->table} ca
                WHERE ca.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_notas' => 0, 'total_planificaciones' => 0];
        return ['notas' => (int) $r['total_notas'], 'planificaciones' => (int) $r['total_planificaciones']];
    }

    /* ================================================================
       ESCRITURA
    ================================================================ */

    /** Agrega una asignatura a la malla. Devuelve false si ya estaba. */
    public function create($curso_id, $asignatura_id, $anio_id)
    {
        if ($this->existe((int) $curso_id, (int) $asignatura_id, (int) $anio_id)) {
            return false;
        }
        $sql = "INSERT INTO {$this->table} (curso_id, asignatura_id, anio_id)
                VALUES (:curso_id, :asignatura_id, :anio_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":curso_id"      => (int) $curso_id,
            ":asignatura_id" => (int) $asignatura_id,
            ":anio_id"       => (int) $anio_id,
        ]);
    }

    /** Cambia la asignatura de una fila (el curso y el año no cambian) */
    public function update($id, $asignatura_id)
    {
        $sql = "UPDATE {$this->table} SET asignatura_id = :asignatura_id WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id"            => (int) $id,
            ":asignatura_id" => (int) $asignatura_id,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    /**
     * Copia la malla de un año a otro (todos los cursos o uno solo).
     * Solo agrega lo que falta en el destino: no borra ni duplica.
     * Devuelve cuántas filas se agregaron.
     */
    public function copiarMalla(int $anioOrigen, int $anioDestino, ?int $curso_id = null): int
    {
        $sql = "INSERT INTO {$this->table} (curso_id, asignatura_id, anio_id)
                SELECT o.curso_id, o.asignatura_id, :destino
                FROM {$this->table} o
                WHERE o.anio_id = :origen
                  AND NOT EXISTS (
                      SELECT 1 FROM (SELECT curso_id, asignatura_id, anio_id FROM {$this->table}) d
                      WHERE d.curso_id = o.curso_id
                        AND d.asignatura_id = o.asignatura_id
                        AND d.anio_id = :destino_chk
                  )";
        $params = [':destino' => $anioDestino, ':origen' => $anioOrigen, ':destino_chk' => $anioDestino];

        if ($curso_id) {
            $sql .= " AND o.curso_id = :curso_id";
            $params[':curso_id'] = $curso_id;
        }

        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $agregadas = $stmt->rowCount();
            $this->conn->commit();
            return $agregadas;
        } catch (Throwable $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
