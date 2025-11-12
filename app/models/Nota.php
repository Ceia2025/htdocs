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

    // 🟣 Obtener nota por ID
    public function getById($id)
    {
        $sql = "SELECT n.*, a.nombre AS asignatura_nombre,
                       m.alumno_id, m.curso_id, m.anio_id,
                       al.nombre AS alumno_nombre, al.apepat, al.apemat
                FROM {$this->table} n
                JOIN asignaturas2 a ON n.asignatura_id = a.id
                JOIN matriculas2 m ON n.matricula_id = m.id
                JOIN alumnos2 al ON m.alumno_id = al.id
                WHERE n.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🟣 Listado general
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

    // 🟣 Notas por matrícula y semestre
    public function getByMatriculaAndSemestre($matricula_id, $semestre): array
    {
        $sql = "SELECT n.*, a.nombre AS asignatura_nombre
                FROM {$this->table} n
                JOIN asignaturas2 a ON n.asignatura_id = a.id
                WHERE n.matricula_id = :matricula_id AND n.semestre = :semestre
                ORDER BY a.nombre, n.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':matricula_id' => $matricula_id, ':semestre' => $semestre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟣 Notas por matrícula (todas)
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

    // 🟣 Obtener alumnos del curso/año
    public function getByCursoYAnio($curso_id, $anio_id)
    {
        $sql = "SELECT m.id AS matricula_id, al.id AS alumno_id,
                       al.nombre, al.apepat, al.apemat, al.deleted_at
                FROM matriculas2 m
                JOIN alumnos2 al ON m.alumno_id = al.id
                WHERE m.curso_id = :curso_id AND m.anio_id = :anio_id
                ORDER BY al.apepat, al.apemat, al.nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':curso_id' => $curso_id, ':anio_id' => $anio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟣 Crear múltiples notas (hasta 10 por alumno/asignatura/semestre)
    // 🟣 Crear múltiples notas (hasta 10 por alumno/asignatura/semestre)
    public function createMultiple($curso_id, $anio_id, $asignatura_id, $fecha, $notas, $semestre)
    {
        $sql = "INSERT INTO {$this->table} (matricula_id, asignatura_id, semestre, nota, fecha)
            VALUES (:matricula_id, :asignatura_id, :semestre, :nota, :fecha)";
        $stmt = $this->conn->prepare($sql);

        foreach ($notas as $n) {

            // Determinar si el alumno está retirado (deleted_at vacío o null = activo)
            $estaRetirado = !empty($n['deleted_at']);
            $notaValor = $estaRetirado ? 0 : floatval($n['nota']);

            // Si no está retirado, validar que la nota esté dentro del rango 1.0 - 7.0
            if (!$estaRetirado && ($notaValor < 1.0 || $notaValor > 7.0)) {
                continue;
            }

            // Validar que no tenga más de 10 notas por asignatura y semestre
            $check = $this->conn->prepare("
            SELECT COUNT(*) FROM {$this->table}
            WHERE matricula_id = :matricula_id 
              AND asignatura_id = :asignatura_id 
              AND semestre = :semestre
        ");
            $check->execute([
                ':matricula_id' => $n['matricula_id'],
                ':asignatura_id' => $asignatura_id,
                ':semestre' => $semestre
            ]);

            if ($check->fetchColumn() >= 10) {
                continue; // ignora si ya tiene 10 notas registradas
            }

            //Insertar nota
            $ok = $stmt->execute([
                ':matricula_id' => $n['matricula_id'],
                ':asignatura_id' => $asignatura_id,
                ':semestre' => $semestre,
                ':nota' => $notaValor,
                ':fecha' => $fecha
            ]);

            // Registrar en log si falla (solo durante desarrollo)
            if (!$ok) {
                error_log("Error al insertar nota para matricula {$n['matricula_id']} ({$stmt->errorInfo()[2]})");
            }
        }
    }


    // 🟣 Actualizar
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
}
