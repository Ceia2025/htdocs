<?php
/**
 * Controlador para la gestión de Asistencias.
 * Asume que el modelo PerfilAcademicoAsistencia está cargado/disponible.
 */
require_once __DIR__ . '/../models/PerfilAcademicoAsistencia.php';
// Nota: Se asume que también existen controladores para Cursos, Años, etc.

class PerfilAcademicoAsistenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new PerfilAcademicoAsistencia();
    }

    /**
     * Muestra la lista general de asistencias o el formulario de selección de curso/año.
     */
    public function index()
    {
        // Ejemplo: Mostrar todas las asistencias. En una aplicación real, se filtrarían.
        $asistencias = $this->model->getAll();
        // Simular la carga de la vista (que debería estar en app/views/perfil_academico/componentasistencia.php)
        require_once __DIR__ . '/../views/perfil_academico/component/asistencia/index.php';
    }

    public function getAsistenciaByAlumnoId($alumnoId)
    {
        // Llama al modelo para obtener las asistencias de un alumno
        // ASUMIMOS que el modelo tiene el método getByAlumnoId($alumnoId)
        $asistenciasAlumno = $this->model->getById($alumnoId);

        // Carga la vista del componente de asistencia para el perfil del alumno
        require_once __DIR__ . '/../views/perfil_academico/component/asistencia.php';
    }

    /**
     * Muestra la lista de alumnos para tomar asistencia en un curso y fecha específica.
     */
    public function showCreateForm()
    {
        // Los datos de curso_id y anio_id vendrían por GET o POST
        $curso_id = $_GET['curso_id'] ?? null;
        $anio_id = $_GET['anio_id'] ?? null;
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        if ($curso_id && $anio_id) {
            $alumnos = $this->model->getByCursoYAnio($curso_id, $anio_id);
            // Cargar la vista de formulario para tomar asistencia
            require_once __DIR__ . '/../views/asistencia/create.php';
        } else {
            // Redirigir o mostrar un mensaje para seleccionar curso/año
        }
    }

    /**
     * Procesa la inserción de múltiples registros de asistencia para un curso/fecha.
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /asistencia/create-form'); // Redirigir a la vista del formulario
            return;
        }

        // Asumiendo que se reciben los siguientes datos del formulario:
        $fecha = $_POST['fecha'] ?? null;
        $asistencias = $_POST['asistencias'] ?? []; // Array con [matricula_id, presente, observaciones]

        if (!$fecha || empty($asistencias)) {
            // Manejar error: datos incompletos
            // include 'error_message.php';
            return;
        }

        $result = $this->model->createMultiple($fecha, $asistencias);

        if ($result === true) {
            // Éxito total
            // Redirigir con mensaje de éxito
            // header('Location: /asistencia?msg=Asistencias guardadas exitosamente.');
        } elseif (is_array($result) && isset($result['errors'])) {
            // Éxito parcial con advertencias/errores
            // Mostrar mensaje de advertencia con los errores específicos
            // include 'warning_message.php';
        } else {
            // Fallo de transacción (ej. error de conexión)
            // Mostrar error genérico
            // include 'error_message.php';
        }
    }

    /**
     * Muestra el formulario para editar un registro de asistencia.
     * @param int $id ID del registro a editar.
     */
    public function edit($id)
    {
        $asistencia = $this->model->getById($id);

        if (!$asistencia) {
            // Manejar: registro no encontrado
            // include '404.php';
            return;
        }

        // Cargar la vista de edición con los datos de $asistencia
        // require_once __DIR__ . '/../views/asistencia/edit.php';
    }

    /**
     * Procesa la actualización de un registro de asistencia.
     * @param int $id ID del registro a actualizar.
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /asistencia/edit/' . $id);
            return;
        }

        $data = [
            'fecha' => $_POST['fecha'],
            'presente' => $_POST['presente'] ?? 0, // Asume 0 si no está marcado (checkbox)
            'observaciones' => $_POST['observaciones']
        ];

        if ($this->model->update($id, $data)) {
            // Éxito
            // header('Location: /asistencia/view/' . $id . '?msg=Asistencia actualizada.');
        } else {
            // Error
            // include 'error_message.php';
        }
    }

    /**
     * Elimina un registro de asistencia.
     * @param int $id ID del registro a eliminar.
     */
    public function delete($id)
    {
        if ($this->model->delete($id)) {
            // Éxito
            // header('Location: /asistencia?msg=Asistencia eliminada.');
        } else {
            // Error
            // include 'error_message.php';
        }
    }
}