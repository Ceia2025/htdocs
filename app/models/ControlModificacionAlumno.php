<?php
require_once __DIR__ . '/../config/Connection.php';

class ControlModificacionAlumno
{
    private $conn;
    private $table = 'alumno_modificaciones';

    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->open();
    }

    /**
     * Registra una modificación en el historial.
     *
     * @param int    $alumnoId   ID del alumno afectado
     * @param int    $usuarioId  ID del usuario que realizó la acción (de $_SESSION)
     * @param string $tabla      Tabla que fue modificada
     * @param string $accion     'crear' | 'editar' | 'eliminar' | 'retirar' | 'restaurar'
     * @param string $detalle    Descripción opcional del cambio
     */
    public function registrar(int $alumnoId, int $usuarioId, string $tabla, string $accion, string $detalle = ''): bool
    {
        $sql = "INSERT INTO {$this->table} 
                    (alumno_id, usuario_id, tabla_afectada, accion, detalle)
                VALUES 
                    (:alumno_id, :usuario_id, :tabla, :accion, :detalle)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':alumno_id'  => $alumnoId,
            ':usuario_id' => $usuarioId,
            ':tabla'      => $tabla,
            ':accion'     => $accion,
            ':detalle'    => $detalle,
        ]);
    }

    /**
     * Obtiene el historial completo de un alumno, con nombre del usuario.
     */
    public function getByAlumno(int $alumnoId): array
    {
        $sql = "SELECT 
                    m.id,
                    m.tabla_afectada,
                    m.accion,
                    m.detalle,
                    m.created_at,
                    u.nombre        AS usuario_nombre,
                    u.ape_paterno   AS usuario_apepat,
                    u.username
                FROM {$this->table} m
                INNER JOIN usuarios2 u ON u.id = m.usuario_id
                WHERE m.alumno_id = :alumno_id
                ORDER BY m.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene solo la última modificación de un alumno.
     * Útil para mostrar en el perfil sin cargar todo el historial.
     */
    public function getUltimaModificacion(int $alumnoId): ?array
    {
        $sql = "SELECT 
                    m.accion,
                    m.tabla_afectada,
                    m.detalle,
                    m.created_at,
                    u.nombre        AS usuario_nombre,
                    u.ape_paterno   AS usuario_apepat,
                    u.username
                FROM {$this->table} m
                INNER JOIN usuarios2 u ON u.id = m.usuario_id
                WHERE m.alumno_id = :alumno_id
                ORDER BY m.created_at DESC
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':alumno_id' => $alumnoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}