<?php
require_once __DIR__ . '/../models/ControlModificacionAlumno.php';

class ControlModificacionAlumnoController
{
    private ControlModificacionAlumno $modelo;

    public function __construct()
    {
        $this->modelo = new ControlModificacionAlumno();
    }

    /**
     * Método central que usan los otros controladores para registrar cambios.
     * Toma el usuario_id directo de la sesión activa.
     */
    public function registrar(int $alumnoId, string $tabla, string $accion, string $detalle = ''): void
    {
        $usuarioId = $_SESSION['user']['id'] ?? null;

        if (!$usuarioId) {
            error_log("⚠️ ControlModificacionAlumno: intento de registrar sin sesión activa.");
            return;
        }

        $ok = $this->modelo->registrar($alumnoId, (int)$usuarioId, $tabla, $accion, $detalle);

        if (!$ok) {
            error_log("❌ ControlModificacionAlumno: no se pudo registrar acción '$accion' para alumno $alumnoId");
        }
    }

    /**
     * Retorna el historial completo para mostrar en una vista o endpoint JSON.
     */
    public function historial(int $alumnoId): array
    {
        return $this->modelo->getByAlumno($alumnoId);
    }

    /**
     * Retorna solo la última modificación (para el perfil del alumno).
     */
    public function ultimaModificacion(int $alumnoId): ?array
    {
        return $this->modelo->getUltimaModificacion($alumnoId);
    }
}