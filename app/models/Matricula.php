<?php
require_once __DIR__ . '/../config/Connection.php';

class Matricula
{
    private $conn;
    private $table = "matriculas2";

    // Cache de columnas reales de la tabla, para no asumir que todas existen
    private $columnas = null;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    // Devuelve true si la columna existe físicamente en la tabla matriculas2
    private function tieneColumna($nombre)
    {
        if ($this->columnas === null) {
            $this->columnas = [];
            try {
                $stmt = $this->conn->query("SHOW COLUMNS FROM {$this->table}");
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
                    $this->columnas[$col['Field']] = true;
                }
            } catch (Exception $e) {
                // Si esto falla, dejamos $columnas vacío: todo se tratará como "no existe"
                $this->columnas = [];
            }
        }
        return isset($this->columnas[$nombre]);
    }

    //Busca Por matricula
    public function buscarMatriculas($nombre = null, $rut = null, $anio = null, $curso = null, $numeroMatricula = null)
    {
        $tieneNumMatricula = $this->tieneColumna('numero_matricula');
        $tieneNumLista = $this->tieneColumna('numero_lista');
        $tieneFechaRetiro = $this->tieneColumna('fecha_retiro');

        $selectNumMatricula = $tieneNumMatricula ? "m.numero_matricula" : "NULL AS numero_matricula";
        $selectNumLista = $tieneNumLista ? "m.numero_lista" : "NULL AS numero_lista";
        $selectFechaRetiro = $tieneFechaRetiro ? "m.fecha_retiro" : "NULL AS fecha_retiro";

        $sql = "
    SELECT m.id,
       m.alumno_id,
       m.curso_id,
       m.anio_id,
       $selectNumLista,
       $selectNumMatricula,
       $selectFechaRetiro,
       a.run,
       CONCAT(a.apepat, ' ', a.apemat, ', ', a.nombre) AS nombre_completo,
       c.nombre AS curso,
       an.anio AS anio,
       m.fecha_matricula
    FROM matriculas2 m
    INNER JOIN alumnos2 a ON m.alumno_id = a.id
    INNER JOIN cursos2 c ON m.curso_id = c.id
    INNER JOIN anios2 an ON m.anio_id = an.id
    WHERE 1=1
    ";

        $params = [];

        if (!empty($nombre)) {
            $sql .= " AND (a.nombre LIKE :nombre OR a.apepat LIKE :nombre OR a.apemat LIKE :nombre)";
            $params[':nombre'] = "%$nombre%";
        }

        if (!empty($rut)) {
            $sql .= " AND a.run LIKE :rut";
            $params[':rut'] = "%$rut%";
        }

        if (!empty($anio)) {
            $sql .= " AND an.anio = :anio";
            $params[':anio'] = $anio;
        }

        if (!empty($curso)) {
            $sql .= " AND c.nombre LIKE :curso";
            $params[':curso'] = "%$curso%";
        }

        if (!empty($numeroMatricula) && $tieneNumMatricula) {
            $sql .= " AND m.numero_matricula LIKE :numero_matricula";
            $params[':numero_matricula'] = "%$numeroMatricula%";
        }

        // ORDER BY siempre al final, después de todos los filtros
        if ($tieneNumLista) {
            $sql .= " ORDER BY
    CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
    m.numero_lista ASC,
    a.apepat ASC";
        } else {
            $sql .= " ORDER BY a.apepat ASC";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las matrículas (con JOINs)
    public function getAll()
    {
        $sql = "SELECT
                    m.id,
                    a.id AS alumno_id,
                    CONCAT(a.nombre, ' ', a.apepat, ' ', a.apemat) AS alumno_nombre,
                    c.nombre AS curso_nombre,
                    an.anio AS anio_escolar,
                    m.fecha_matricula
                FROM {$this->table} m
                INNER JOIN alumnos2 a ON m.alumno_id = a.id
                INNER JOIN cursos2 c ON m.curso_id = c.id
                INNER JOIN anios2 an ON m.anio_id = an.id
                ORDER BY an.anio DESC, c.nombre ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener matrícula por ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener matrículas de un alumno
    public function getByAlumno($alumno_id)
    {
        $sql = "SELECT
                    m.*,
                    " . ($this->tieneColumna('fecha_retiro') ? "m.fecha_retiro," : "NULL AS fecha_retiro,") . "
                    c.nombre AS curso_nombre,
                    an.anio AS anio_escolar
                FROM {$this->table} m
                INNER JOIN cursos2 c ON m.curso_id = c.id
                INNER JOIN anios2 an ON m.anio_id = an.id
                WHERE m.alumno_id = :alumno_id
                ORDER BY an.anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear matrícula
    public function create($data)
    {
        $campos = ['alumno_id', 'curso_id', 'anio_id', 'fecha_matricula'];
        $valores = [
            ':alumno_id' => $data['alumno_id'],
            ':curso_id' => $data['curso_id'],
            ':anio_id' => $data['anio_id'],
            ':fecha_matricula' => $data['fecha_matricula'] ?? date('Y-m-d'),
        ];

        if ($this->tieneColumna('numero_matricula')) {
            $campos[] = 'numero_matricula';
            $valores[':numero_matricula'] = !empty($data['numero_matricula']) ? $data['numero_matricula'] : null;
        }

        $placeholders = implode(', ', array_map(fn($c) => ':' . $c, $campos));
        $columnas = implode(', ', $campos);

        $sql = "INSERT INTO {$this->table} ($columnas) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($valores);
    }

    // Actualizar matrícula
    public function update($id, $data)
    {
        $sets = [
            "alumno_id = :alumno_id",
            "curso_id = :curso_id",
            "anio_id = :anio_id",
            "fecha_matricula = :fecha_matricula",
        ];
        $valores = [
            ':id' => $id,
            ':alumno_id' => $data['alumno_id'],
            ':curso_id' => $data['curso_id'],
            ':anio_id' => $data['anio_id'],
            ':fecha_matricula' => !empty($data['fecha_matricula']) ? $data['fecha_matricula'] : null,
        ];

        if ($this->tieneColumna('numero_lista')) {
            $sets[] = "numero_lista = :numero_lista";
            $valores[':numero_lista'] = ($data['numero_lista'] ?? '') !== '' ? $data['numero_lista'] : null;
        }

        if ($this->tieneColumna('numero_matricula')) {
            $sets[] = "numero_matricula = :numero_matricula";
            $valores[':numero_matricula'] = !empty($data['numero_matricula']) ? $data['numero_matricula'] : null;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($valores);
    }

    // Obtener alumnos de un curso con su número de lista
    public function getAlumnosConNumeroLista($cursoId, $anioId)
    {
        $selectNumLista = $this->tieneColumna('numero_lista') ? "m.numero_lista," : "NULL AS numero_lista,";
        $selectFechaRetiro = $this->tieneColumna('fecha_retiro') ? "m.fecha_retiro," : "NULL AS fecha_retiro,";
        $orderBy = $this->tieneColumna('numero_lista')
            ? "CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END, m.numero_lista ASC, a.apepat ASC"
            : "a.apepat ASC";

        $sql = "SELECT
                m.id as matricula_id,
                $selectNumLista
                $selectFechaRetiro
                a.nombre,
                a.apepat,
                a.apemat,
                a.run
            FROM matriculas2 m
            JOIN alumnos2 a ON a.id = m.alumno_id
            WHERE m.curso_id = :curso_id
            AND m.anio_id   = :anio_id
            AND a.deleted_at IS NULL
            ORDER BY $orderBy";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $cursoId, ':anio_id' => $anioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar número de lista de una matrícula
    public function updateNumeroLista($matriculaId, $numero)
    {
        if (!$this->tieneColumna('numero_lista')) {
            // La columna no existe en esta base: no hay nada que guardar, pero no rompemos el flujo
            return false;
        }

        $sql = "UPDATE {$this->table}
            SET numero_lista = :numero
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':numero' => $numero ?: null,
            ':id' => $matriculaId
        ]);
    }

    // 🔹 Obtener matrícula según alumno, curso y año
    public function getByAlumnoYCurso($alumno_id, $curso_id, $anio_id)
    {
        $sql = "SELECT * FROM matriculas2
            WHERE alumno_id = :alumno_id
              AND curso_id = :curso_id
              AND anio_id = :anio_id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':alumno_id' => $alumno_id,
            ':curso_id' => $curso_id,
            ':anio_id' => $anio_id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar todas las matrículas de un alumno
    public function deleteByAlumno($alumno_id)
    {
        $sql = "DELETE FROM {$this->table} WHERE alumno_id = :alumno_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':alumno_id' => $alumno_id]);
    }

    // Eliminar matrícula
    public function delete($id)
    {
        // Primero verificar si tiene asistencia registrada
        $sql = "SELECT COUNT(*) FROM alum_asistencia2 WHERE matricula_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $tieneAsistencia = (int) $stmt->fetchColumn();

        if ($tieneAsistencia > 0) {
            // Tiene asistencia: soft delete (marcar como retirado hoy)
            return $this->retirar($id, date('Y-m-d'));
        }

        // Sin asistencia: eliminar físicamente
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Nuevo método: marcar como retirado
    public function retirar($id, $fechaRetiro)
    {
        if (!$this->tieneColumna('fecha_retiro')) {
            // Sin esta columna no se puede registrar el retiro; evitamos el fatal error
            return false;
        }

        $sql = "UPDATE {$this->table}
            SET fecha_retiro = :fecha_retiro
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':fecha_retiro' => $fechaRetiro
        ]);
    }

    // Nuevo método: verificar si está retirado
    public function estaRetirado($id): bool
    {
        if (!$this->tieneColumna('fecha_retiro')) {
            return false;
        }

        $sql = "SELECT fecha_retiro FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($row['fecha_retiro']);
    }

    // Obtener matrícula activa del alumno (la más reciente sin retiro)
    public function getActivaByAlumno($alumno_id)
    {
        $condicionRetiro = $this->tieneColumna('fecha_retiro') ? "AND m.fecha_retiro IS NULL" : "";

        $sql = "SELECT
                m.id,
                " . ($this->tieneColumna('numero_lista') ? "m.numero_lista," : "NULL AS numero_lista,") . "
                m.fecha_matricula,
                " . ($this->tieneColumna('fecha_retiro') ? "m.fecha_retiro," : "NULL AS fecha_retiro,") . "
                c.nombre AS curso_nombre,
                an.anio  AS anio_escolar
            FROM matriculas2 m
            INNER JOIN cursos2 c  ON m.curso_id = c.id
            INNER JOIN anios2 an  ON m.anio_id  = an.id
            WHERE m.alumno_id = :alumno_id
              $condicionRetiro
            ORDER BY an.anio DESC
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumno_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function reintegrar($id)
    {
        if (!$this->tieneColumna('fecha_retiro')) {
            return false;
        }

        $sql = "UPDATE {$this->table}
            SET fecha_retiro = NULL
            WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}