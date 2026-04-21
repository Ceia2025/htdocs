<?php
require_once __DIR__ . '/../config/Connection.php';

class CursoDocente
{
    private $conn;

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /* Asignar o reemplazar docente en un curso/año */
    public function asignar(int $cursoId, int $docenteId, int $anioId): bool
    {
        $sql = "INSERT INTO curso_docente (curso_id, docente_id, anio_id)
                VALUES (:curso_id, :docente_id, :anio_id)
                ON DUPLICATE KEY UPDATE docente_id = :docente_id2";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':curso_id'    => $cursoId,
            ':docente_id'  => $docenteId,
            ':anio_id'     => $anioId,
            ':docente_id2' => $docenteId,
        ]);
    }

    /* Docente asignado a un curso en un año */
    public function getDocenteDeCurso(int $cursoId, int $anioId): ?array
    {
        $sql = "SELECT u.id, u.nombre, u.ape_paterno, u.ape_materno, u.email,
                       cd.id AS asignacion_id
                FROM curso_docente cd
                JOIN usuarios2 u ON u.id = cd.docente_id
                WHERE cd.curso_id = :curso_id
                  AND cd.anio_id  = :anio_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $cursoId, ':anio_id' => $anioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /* Todos los cursos con su docente (o sin asignar) en un año */
    public function getAllConDocente(int $anioId): array
    {
        $sql = "SELECT c.id AS curso_id,
                       c.nombre AS curso,
                       u.id AS docente_id,
                       u.nombre AS docente_nombre,
                       u.ape_paterno,
                       u.ape_materno,
                       u.email,
                       cd.id AS asignacion_id
                FROM cursos2 c
                LEFT JOIN curso_docente cd ON cd.curso_id = c.id AND cd.anio_id = :anio_id
                LEFT JOIN usuarios2     u  ON u.id = cd.docente_id
                ORDER BY c.nombre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':anio_id' => $anioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Lista de docentes disponibles (rol_id = 6 = ROL_DOCENTE) */
    public function getDocentes(): array
    {
        $sql = "SELECT id, nombre, ape_paterno, ape_materno, email
                FROM usuarios2
                WHERE rol_id = 6
                ORDER BY ape_paterno, nombre";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Desasignar por id de la tabla curso_docente */
    public function desasignar(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM curso_docente WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /* Años disponibles (reutilizamos lógica de Atraso) */
    public function getAnios(): array
    {
        $sql = "SELECT id, anio FROM anios2 WHERE anio >= 2025 ORDER BY anio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAnioActualId(): ?int
    {
        $sql = "SELECT id FROM anios2 WHERE anio = YEAR(CURDATE()) LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null;
    }
}