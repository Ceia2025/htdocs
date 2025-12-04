<?php
/**
 * Modelo para la gestión de Asistencias (Attendance) de los alumnos.
 * Interactúa con la tabla 'alum_asistencia2'.
 */
require_once __DIR__ . '/../config/Connection.php';

class PerfilAcademicoAsistencia
{
    private $conn;
    private $table = "alum_asistencia2";

    public function __construct()
    {
        // Se asume que la clase Connection existe y funciona correctamente
        $db = new Connection();
        $this->conn = $db->open();
    }

    /**
     * Obtiene un registro de asistencia por su ID, incluyendo detalles del alumno.
     * @param int $id ID del registro de asistencia.
     * @return array|false Registro de asistencia o false si no se encuentra.
     */
    public function getById($id)
    {
        $sql = "SELECT a.*, 
                       m.alumno_id, m.curso_id, m.anio_id,
                       al.nombre AS alumno_nombre, al.apepat, al.apemat
                FROM {$this->table} a
                JOIN matriculas2 m ON a.matricula_id = m.id
                JOIN alumnos2 al ON m.alumno_id = al.id
                WHERE a.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Listado general de asistencias con detalles del curso y alumno.
     * @return array Lista de registros de asistencia.
     */
    public function getAll(): array
    {
        $sql = "SELECT a.*, 
                       al.nombre AS alumno_nombre,
                       al.apepat AS alumno_apepat, al.apemat AS alumno_apemat,
                       c.nombre AS curso_nombre,
                       an.anio AS anio_escolar
                FROM {$this->table} a
                JOIN matriculas2 m ON a.matricula_id = m.id
                JOIN alumnos2 al ON m.alumno_id = al.id
                JOIN cursos2 c ON m.curso_id = c.id
                JOIN anios2 an ON m.anio_id = an.id
                ORDER BY a.fecha DESC, an.anio DESC, c.nombre, al.apepat";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los alumnos de un curso y año específicos. Útil para cargar el formulario de asistencia.
     * @param int $curso_id ID del curso.
     * @param int $anio_id ID del año escolar.
     * @return array Lista de alumnos con su matricula_id.
     */
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
    
    /**
     * Obtiene las asistencias para una matrícula específica dentro de un rango de fechas.
     * @param int $matricula_id ID de la matrícula.
     * @param string $start_date Fecha de inicio (YYYY-MM-DD).
     * @param string $end_date Fecha de fin (YYYY-MM-DD).
     * @return array Lista de asistencias.
     */
    public function getByMatriculaAndDateRange($matricula_id, $start_date, $end_date): array
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE matricula_id = :matricula_id 
                  AND fecha BETWEEN :start_date AND :end_date
                ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':matricula_id' => $matricula_id, 
            ':start_date' => $start_date, 
            ':end_date' => $end_date
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un único registro de asistencia.
     * @param array $data Datos del registro (matricula_id, fecha, presente, observaciones).
     * @return bool Resultado de la operación.
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (matricula_id, fecha, presente, observaciones)
                VALUES (:matricula_id, :fecha, :presente, :observaciones)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':matricula_id' => $data['matricula_id'],
            ':fecha' => $data['fecha'],
            ':presente' => $data['presente'], // 1 para presente, 0 para ausente
            ':observaciones' => $data['observaciones'] ?? null
        ]);
    }
    
    /**
     * Crea múltiples registros de asistencia para un grupo de alumnos en una fecha específica.
     * @param string $fecha La fecha de la asistencia (YYYY-MM-DD).
     * @param array $asistencias Array de datos: [['matricula_id' => 1, 'presente' => 1, 'observaciones' => ''], ...]
     * @return bool|array True si se completó sin errores, array con errores si falló.
     */
    public function createMultiple($fecha, $asistencias)
    {
        $sql = "INSERT INTO {$this->table} (matricula_id, fecha, presente, observaciones)
                VALUES (:matricula_id, :fecha, :presente, :observaciones)";
        $stmt = $this->conn->prepare($sql);
        
        $this->conn->beginTransaction();
        $errors = [];

        foreach ($asistencias as $a) {
            try {
                // Verificar si ya existe un registro de asistencia para esta matrícula y fecha
                $check_sql = "SELECT id FROM {$this->table} WHERE matricula_id = :matricula_id AND fecha = :fecha LIMIT 1";
                $check_stmt = $this->conn->prepare($check_sql);
                $check_stmt->execute([':matricula_id' => $a['matricula_id'], ':fecha' => $fecha]);

                if ($check_stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Si existe, se podría optar por actualizar o simplemente ignorar
                    // Aquí se opta por ignorar y registrar como error/advertencia.
                    $errors[] = "Ya existe asistencia para Matrícula ID {$a['matricula_id']} en la fecha {$fecha}";
                    continue; 
                }

                $ok = $stmt->execute([
                    ':matricula_id' => $a['matricula_id'],
                    ':fecha' => $fecha,
                    ':presente' => $a['presente'], // 1 o 0 (true o false)
                    ':observaciones' => $a['observaciones'] ?? null
                ]);

                if (!$ok) {
                    $errors[] = "Error al insertar para matricula ID {$a['matricula_id']}: {$stmt->errorInfo()[2]}";
                }
            } catch (PDOException $e) {
                $this->conn->rollBack();
                error_log("Error de BD en createMultiple: " . $e->getMessage());
                return false;
            }
        }

        if (empty($errors)) {
            $this->conn->commit();
            return true;
        } else {
            $this->conn->commit(); // Aunque hubo errores individuales, si se usó continue, el resto se insertó.
            return ['errors' => $errors];
        }
    }

    /**
     * Actualiza un registro de asistencia existente.
     * @param int $id ID del registro.
     * @param array $data Nuevos datos (fecha, presente, observaciones).
     * @return bool Resultado de la operación.
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table}
                SET fecha = :fecha, presente = :presente, observaciones = :observaciones
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':fecha' => $data['fecha'],
            ':presente' => $data['presente'],
            ':observaciones' => $data['observaciones'] ?? null,
            ':id' => $id
        ]);
    }

    /**
     * Elimina un registro de asistencia.
     * @param int $id ID del registro.
     * @return bool Resultado de la operación.
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}