<?php
require_once __DIR__ . '/../models/AlumEmergencia.php';
require_once __DIR__ . '/../models/Alumno.php'; // Para traer lista de alumnos

class AlumEmergenciaController
{
    private $model;

    public function __construct()
    {
        $this->model = new AlumEmergencia();
    }

    // Listar
    public function index()
    {
        $search = $_GET['q'] ?? null;

        if ($search) {
            $emergencias = $this->model->searchByAlumno($search);
        } else {
            $emergencias = $this->model->getAll();
        }

        require __DIR__ . '/../views/alum_emergencia/index.php';
    }


    // Formulario crear
    public function create()
    {
        $alumnoModel = new Alumno();
        $alumnos = $alumnoModel->getAll();
        require __DIR__ . '/../views/alum_emergencia/create.php';
    }

    // Formulario crear desde perfil (sin mostrar selección de alumno)
    public function createProfile($alumno_id)
    {
        // Comprobar que el alumno existe
        $alumnoModel = new Alumno();
        $alumno = $alumnoModel->getById($alumno_id);
        if (!$alumno) {
            header("Location: index.php?action=alumnos");
            exit;
        }

        // Variables para la vista
        $back = "alumno_profile";
        require __DIR__ . "/../views/alum_emergencia/createProfile.php";
    }



    // Guardar nuevo
    public function store($data)
    {
        $this->model->create(
            $data['alumno_id'],
            $data['nombre_contacto'],
            $data['telefono'],
            $data['direccion'],
            $data['relacion']
        );
        header("Location: index.php?action=alum_emergencia");
    }

    // Guardar nuevo contacto desde el perfil del alumno
    public function storeProfile($data)
    {
        // Validación mínima
        if (empty($data['alumno_id'])) {
            $_SESSION['flash_error'] = "Falta el ID del alumno.";
            header("Location: index.php?action=alum_emergencia");
            exit;
        }

        // Crear registro
        $ok = $this->model->create(
            $data['alumno_id'],
            $data['nombre_contacto'] ?? null,
            $data['telefono'] ?? null,
            $data['direccion'] ?? null,
            $data['relacion'] ?? null
        );

        if ($ok) {
            $_SESSION['flash_success'] = "Contacto agregado correctamente.";
        } else {
            $_SESSION['flash_error'] = "Error al guardar el contacto.";
        }

        // Redirigir al perfil del alumno
        header("Location: index.php?action=alumno_profile&id=" . urlencode($data['alumno_id']));
        exit;
    }

    // Eliminar contacto desde perfil del alumno
    public function deleteProfile($id, $alumno_id)
    {
        if (empty($id) || empty($alumno_id)) {
            $_SESSION['flash_error'] = "Datos incompletos para eliminar el contacto.";
            header("Location: index.php?action=alum_emergencia");
            exit;
        }

        $ok = $this->model->delete($id);

        if ($ok) {
            $_SESSION['flash_success'] = "Contacto eliminado correctamente.";
        } else {
            $_SESSION['flash_error'] = "No se pudo eliminar el contacto.";
        }

        // Redirigir al perfil del alumno
        header("Location: index.php?action=alumno_profile&id=" . urlencode($alumno_id));
        exit;
    }

    // Editar
    public function edit($id)
    {
        $emergencia = $this->model->getById($id);
        if (!$emergencia) {
            // opcionalmente podrías setear un flash y redirigir
            header("Location: index.php?action=alum_emergencia");
            exit;
        }

        // Para el <select> de alumnos
        $alumnoModel = new Alumno();
        $alumnos = $alumnoModel->getAll();

        // --- NUEVO: parámetros de retorno (si vienes del perfil)
        $back = $_GET['back'] ?? 'alum_emergencia';
        $alumno_id = $_GET['alumno_id'] ?? $emergencia['alumno_id']; // fallback seguro

        require __DIR__ . '/../views/alum_emergencia/edit.php';
    }

    // Actualizar
    public function update($id, $data)
    {
        // Validación mínima
        if (empty($id)) {
            header("Location: index.php?action=alum_emergencia");
            exit;
        }

        $ok = $this->model->update(
            $id,
            $data['alumno_id'] ?? null,
            $data['nombre_contacto'] ?? '',
            $data['telefono'] ?? '',
            $data['direccion'] ?? '',
            $data['relacion'] ?? ''
        );

        // --- NUEVO: decidir a dónde volver ---
        $back = $data['back'] ?? 'alum_emergencia';
        $alumno_id = $data['alumno_id'] ?? null;

        if ($ok && $back === 'alumno_profile' && $alumno_id) {
            header("Location: index.php?action=alumno_profile&id=" . urlencode($alumno_id));
            exit;
        }

        // Fallback o al venir del listado
        header("Location: index.php?action=alum_emergencia");
        exit;
    }

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=alum_emergencia");
    }
}
