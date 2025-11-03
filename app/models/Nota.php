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

    // Obtener por ID
    public function getById($id)
    {
        $sql = "SELECT n.*,
                   a.nombre AS asignatura_nombre,
                   m.alumno_id, m.curso_id, m.anio_id,
                   al.nombre AS alumno_nombre, al.apepat, al.apemat
            FROM {$this->table} n
            JOIN asignaturas2 a ON n.asignatura_id = a.id
            JOIN matriculas2 m   ON n.matricula_id = m.id
            JOIN alumnos2 al     ON m.alumno_id = al.id
            WHERE n.id = :id
            LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Obtener todas las notas (vista general)
    public function getAll(): array
    {
        $sql = "SELECT n.*, 
                       a.nombre AS asignatura_nombre,
                       al.nombre AS alumno_nombre,
                       al.apepat AS alumno_apepat,
                       al.apemat AS alumno_apemat,
                       m.id AS matricula_id,
                       c.nombre AS curso_nombre,
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

    // ✅ Obtener notas por matrícula (para perfil académico)
    public function getByMatricula($matricula_id):array
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

    // ✅ Obtener notas por curso y año (para ingreso masivo)
    public function getByCursoYAnio($curso_id, $anio_id)
    {
        $sql = "SELECT m.id AS matricula_id, al.id AS alumno_id, al.nombre, al.apepat, al.apemat, al.deleted_at
                FROM matriculas2 m
                JOIN alumnos2 al ON m.alumno_id = al.id
                WHERE m.curso_id = :curso_id AND m.anio_id = :anio_id
                ORDER BY al.apepat, al.apemat, al.nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id, ':anio_id' => $anio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Crear múltiples notas (una por alumno)
    public function createMultiple($curso_id, $anio_id, $asignatura_id, $fecha, $notas)
    {
        $sql = "INSERT INTO {$this->table} (matricula_id, asignatura_id, nota, fecha)
                VALUES (:matricula_id, :asignatura_id, :nota, :fecha)";
        $stmt = $this->conn->prepare($sql);

        foreach ($notas as $n) {
            // Si el alumno está retirado, la nota se marca en 0
            $notaValor = ($n['deleted_at'] !== null) ? 0 : $n['nota'];
            // Validar rango 1.0 - 7.0 si el alumno no está retirado
            if ($n['deleted_at'] === null && ($notaValor < 1.0 || $notaValor > 7.0)) {
                continue; // ignora notas inválidas
            }

            $stmt->execute([
                ':matricula_id' => $n['matricula_id'],
                ':asignatura_id' => $asignatura_id,
                ':nota' => $notaValor,
                ':fecha' => $fecha
            ]);
        }
    }

    // ✅ Actualizar una nota
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET nota = :nota, fecha = :fecha
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nota' => $data['nota'],
            ':fecha' => $data['fecha'],
            ':id' => $id
        ]);
    }

    // ✅ Eliminar una nota
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
