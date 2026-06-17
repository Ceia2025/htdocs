<?php
require_once __DIR__ . '/../config/Connection.php';

class Nota
{
    private $conn;
    private $table = "alum_notas2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public static function redondearNota(float $valor, int $decimales = 1): float
    {
        $factor = 10 ** $decimales;
        // El +1e-9 compensa el ruido de precisión binaria sin afectar
        // ningún valor real de nota (la diferencia mínima entre notas es 0.01)
        return round($valor * $factor + 1e-9) / $factor;
    }

    // Obtener nota por ID
    public function getById($id)
    {
        $sql = "SELECT n.*, 
                   n.asignatura_id,
                   a.nombre AS asignatura_nombre,
                   m.alumno_id, m.curso_id, m.anio_id,
                   al.nombre AS alumno_nombre, al.apepat, al.apemat
            FROM {$this->table} n
            JOIN asignaturas2 a  ON n.asignatura_id = a.id
            JOIN matriculas2 m   ON n.matricula_id = m.id
            JOIN alumnos2 al     ON m.alumno_id = al.id
            WHERE n.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listado general
    public function getAll(): array
    {
        $sql = "SELECT n.*, a.nombre AS asignatura_nombre,
                       al.nombre AS alumno_nombre,
                       al.apepat AS alumno_apepat, al.apemat AS alumno_apemat,
                       m.id AS matricula_id, c.nombre AS curso_nombre,
                       an.anio AS anio_escolar
                FROM {$this->table} n
                JOIN asignaturas2 a ON n.asignatura_id = a.id
                JOIN matriculas2 m ON n.matricula_id = m.id
                JOIN alumnos2 al ON m.alumno_id = al.id
                JOIN cursos2 c ON m.curso_id = c.id
                JOIN anios2 an ON m.anio_id = an.id
                ORDER BY an.anio DESC, c.nombre, al.apepat";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Notas por matrícula y semestre
    public function getByMatriculaAndSemestre($matricula_id, $semestre): array
    {
        $sql = "SELECT n.*, a.nombre AS asignatura_nombre
            FROM {$this->table} n
            JOIN asignaturas2 a ON n.asignatura_id = a.id
            WHERE n.matricula_id = :matricula_id AND n.semestre = :semestre
            ORDER BY a.nombre, n.fecha ASC, n.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':matricula_id' => $matricula_id, ':semestre' => $semestre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Notas por matrícula (todas)
    public function getByMatricula($matricula_id): array
    {
        $sql = "SELECT n.*, a.nombre AS asignatura_nombre
                FROM {$this->table} n
                JOIN asignaturas2 a ON n.asignatura_id = a.id
                WHERE n.matricula_id = :matricula_id
                ORDER BY a.nombre, n.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':matricula_id' => $matricula_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Obtener alumnos del curso/año
    public function getByCursoYAnio($curso_id, $anio_id)
    {
        $sql = "SELECT 
                m.id AS matricula_id,
                m.numero_lista,
                m.fecha_retiro,
                al.id AS alumno_id,
                al.nombre,
                al.apepat,
                al.apemat,
                al.deleted_at
            FROM matriculas2 m
            JOIN alumnos2 al ON m.alumno_id = al.id
            WHERE m.curso_id = :curso_id 
            AND m.anio_id = :anio_id
            ORDER BY 
                CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
                m.numero_lista ASC,
                al.apepat ASC,
                al.apemat ASC,
                al.nombre ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id, ':anio_id' => $anio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear múltiples notas (hasta 10 por alumno/asignatura/semestre)
    public function createMultiple($curso_id, $anio_id, $asignatura_id, $fecha, $notas, $semestre)
    {
        $sql = "INSERT INTO {$this->table} (matricula_id, asignatura_id, semestre, nota, fecha)
        VALUES (:matricula_id, :asignatura_id, :semestre, :nota, :fecha)";
        $stmt = $this->conn->prepare($sql);

        foreach ($notas as $n) {

            // Determinar si el alumno está retirado
            $estaRetirado = !empty($n['deleted_at']);
            $notaValor = $estaRetirado ? 0 : floatval($n['nota']);

            // Si no está retirado, validar rango 1.0 - 7.0
            if (!$estaRetirado && ($notaValor < 1.0 || $notaValor > 7.0)) {
                continue;
            }

            // Insertar nota
            $ok = $stmt->execute([
                ':matricula_id' => $n['matricula_id'],
                ':asignatura_id' => $asignatura_id,
                ':semestre' => $semestre,
                ':nota' => $notaValor,
                ':fecha' => $fecha
            ]);

            if (!$ok) {
                error_log("Error al insertar nota para matricula {$n['matricula_id']} ({$stmt->errorInfo()[2]})");
            }
        }
    }


    // Actualizar
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET nota = :nota, fecha = :fecha, semestre = :semestre
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nota' => $data['nota'],
            ':fecha' => $data['fecha'],
            ':semestre' => $data['semestre'],
            ':id' => $id
        ]);
    }

    // 🟣 Eliminar
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getNotasPorCursoAsignaturaSemestre(
        int $cursoId,
        int $anioId,
        int $asignaturaId,
        int $semestre
    ): array {
        $sql = "SELECT n.id, n.nota, n.fecha, n.semestre,
               n.matricula_id,
               al.nombre, al.apepat, al.apemat,
               m.numero_lista,
               m.fecha_retiro
        FROM {$this->table} n
        JOIN matriculas2 m  ON m.id = n.matricula_id
        JOIN alumnos2 al    ON al.id = m.alumno_id
        WHERE m.curso_id      = :curso_id
        AND   m.anio_id       = :anio_id
        AND   n.asignatura_id = :asignatura_id
        AND   n.semestre      = :semestre
        ORDER BY 
            CASE WHEN m.numero_lista IS NULL THEN 1 ELSE 0 END,
            m.numero_lista ASC,
            al.apepat ASC,
            al.apemat ASC,
            n.fecha ASC,
            n.id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $cursoId,
            ':anio_id' => $anioId,
            ':asignatura_id' => $asignaturaId,
            ':semestre' => $semestre,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function insertarNota(
        int $matriculaId,
        int $asignaturaId,
        int $semestre,
        float $nota,
        string $fecha
    ): int {
        $sql = "INSERT INTO {$this->table}
                (matricula_id, asignatura_id, semestre, nota, fecha)
            VALUES
                (:matricula_id, :asignatura_id, :semestre, :nota, :fecha)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':matricula_id' => $matriculaId,
            ':asignatura_id' => $asignaturaId,
            ':semestre' => $semestre,
            ':nota' => $nota,
            ':fecha' => $fecha,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    // ── Actualizar la fecha de un conjunto de notas (columna) ────
    public function actualizarFechaNotas(array $ids, string $fecha): bool
    {
        if (empty($ids))
            return false;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE {$this->table} SET fecha = ? WHERE id IN ($placeholders)";

        $params = array_merge([$fecha], $ids);
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
}
