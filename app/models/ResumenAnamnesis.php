<?php
require_once __DIR__ . '/../config/Connection.php';

class ResumenAnamnesis
{
    private $conn;
    private $table = "resumen_anamnesis";

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    public function getByAlumnoYAnio($alumnoId, $anioId)
    {
        $sql = "SELECT r.*, an.anio AS anio_escolar
                FROM {$this->table} r
                JOIN anios2 an ON an.id = r.anio_id
                WHERE r.alumno_id = :alumno_id
                AND r.anio_id = :anio_id
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId, ':anio_id' => $anioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllByAlumno($alumnoId)
    {
        $sql = "SELECT r.*, an.anio AS anio_escolar
                FROM {$this->table} r
                JOIN anios2 an ON an.id = r.anio_id
                WHERE r.alumno_id = :alumno_id
                ORDER BY an.anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        // Upsert — si ya existe para ese alumno/año, actualiza
        $sql = "INSERT INTO {$this->table}
                    (alumno_id, anio_id, realizado_por, relacion, observaciones, created_by)
                VALUES
                    (:alumno_id, :anio_id, :realizado_por, :relacion, :observaciones, :created_by)
                ON DUPLICATE KEY UPDATE
                    realizado_por  = VALUES(realizado_por),
                    relacion       = VALUES(relacion),
                    observaciones  = VALUES(observaciones),
                    updated_at     = NOW()";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':alumno_id'    => $data['alumno_id'],
            ':anio_id'      => $data['anio_id'],
            ':realizado_por' => $data['realizado_por'],
            ':relacion'     => $data['relacion'] ?: null,
            ':observaciones' => $data['observaciones'] ?: null,
            ':created_by'   => $data['created_by'] ?? null,
        ]);
    }
}