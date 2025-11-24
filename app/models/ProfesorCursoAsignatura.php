<?php
require_once __DIR__ . '/../config/Connection.php';

class ProfesorCursoAsignatura
{
    private $conn;
    private $table = "profesor_curso_asignatura2";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getAll()
    {
        $sql = "SELECT 
                pca.*,
                CONCAT(p.nombres, ' ', p.apepat, ' ', p.apemat) AS profesor_nombre,
                c.nombre AS curso_nombre,
                a.nombre AS asignatura_nombre,
                an.anio AS anio
            FROM {$this->table} pca
            INNER JOIN profesores2 p ON pca.profesor_id = p.id
            INNER JOIN cursos2 c ON pca.curso_id = c.id
            INNER JOIN asignaturas2 a ON pca.asignatura_id = a.id
            INNER JOIN anios2 an ON pca.anio_id = an.id
            ORDER BY an.anio DESC, c.nombre ASC, a.nombre ASC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    public function getByCursoYAnio($curso_id, $anio_id)
    {
        $sql = "SELECT 
                    pca.*,
                    p.nombres, p.apepat, p.apemat,
                    a.nombre AS asignatura_nombre
                FROM {$this->table} pca
                INNER JOIN profesores2 p ON pca.profesor_id = p.id
                INNER JOIN asignaturas2 a ON pca.asignatura_id = a.id
                WHERE pca.curso_id = :curso_id AND pca.anio_id = :anio_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':curso_id' => $curso_id,
            ':anio_id' => $anio_id,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table}
                (profesor_id, curso_id, asignatura_id, anio_id,
                 fecha_inicio, fecha_fin, horas_semanales, es_jefe_curso)
                VALUES
                (:profesor_id, :curso_id, :asignatura_id, :anio_id,
                 :fecha_inicio, :fecha_fin, :horas_semanales, :es_jefe_curso)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':profesor_id' => $data['profesor_id'],
            ':curso_id' => $data['curso_id'],
            ':asignatura_id' => $data['asignatura_id'],
            ':anio_id' => $data['anio_id'],
            ':fecha_inicio' => $data['fecha_inicio'] ?? null,
            ':fecha_fin' => $data['fecha_fin'] ?? null,
            ':horas_semanales' => $data['horas_semanales'] !== "" ? $data['horas_semanales'] : null,
            ':es_jefe_curso' => !empty($data['es_jefe_curso']) ? 1 : 0,
        ]);
    }

    public function getAsignacionesActuales($profesor_id, $curso_id, $anio_id)
    {
        $sql = "SELECT asignatura_id 
            FROM {$this->table}
            WHERE profesor_id = :profesor_id
            AND curso_id = :curso_id
            AND anio_id = :anio_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':profesor_id' => $profesor_id,
            ':curso_id' => $curso_id,
            ':anio_id' => $anio_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function deleteByProfesorCursoAnio($profesor_id, $curso_id, $anio_id)
    {
        $sql = "DELETE FROM {$this->table}
            WHERE profesor_id = :profesor_id
            AND curso_id = :curso_id
            AND anio_id = :anio_id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':profesor_id' => $profesor_id,
            ':curso_id' => $curso_id,
            ':anio_id' => $anio_id
        ]);
    }




    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    profesor_id = :profesor_id,
                    curso_id = :curso_id,
                    asignatura_id = :asignatura_id,
                    anio_id = :anio_id,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    horas_semanales = :horas_semanales,
                    es_jefe_curso = :es_jefe_curso
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':profesor_id' => $data['profesor_id'],
            ':curso_id' => $data['curso_id'],
            ':asignatura_id' => $data['asignatura_id'],
            ':anio_id' => $data['anio_id'],
            ':fecha_inicio' => $data['fecha_inicio'] ?? null,
            ':fecha_fin' => $data['fecha_fin'] ?? null,
            ':horas_semanales' => !empty($data['horas_semanales']) ? $data['horas_semanales'] : null,
            ':es_jefe_curso' => !empty($data['es_jefe_curso']) ? 1 : 0,
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
