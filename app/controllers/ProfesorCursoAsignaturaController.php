<?php
require_once __DIR__ . '/../models/ProfesorCursoAsignatura.php';
require_once __DIR__ . '/../models/Profesor.php';
require_once __DIR__ . '/../models/Cursos.php';
require_once __DIR__ . '/../models/Asignaturas.php';
require_once __DIR__ . '/../models/Anio.php';
require_once __DIR__ . '/../models/CursoAsignatura.php';

class ProfesorCursoAsignaturaController
{
    private $model;

    public function __construct()
    {
        $this->model = new ProfesorCursoAsignatura();
    }

    /* ===========================
       LISTADO
    ============================ */
    public function index()
    {
        $asignaciones = $this->model->getAll();
        require __DIR__ . '/../views/profesorCursoAsignatura/index.php';
    }

    /* ===========================
       CREAR
    ============================ */
    public function create()
    {
        $profesorModel = new Profesor();
        $cursoModel = new Cursos();
        $anioModel = new Anio();

        // Lista de profesores activos
        $profesores = $profesorModel->getActivos();
        $cursos = $cursoModel->getAll();
        $anios = $anioModel->getAll();

        require __DIR__ . '/../views/profesorCursoAsignatura/create.php';
    }

    /* ===========================
       GUARDAR
    ============================ */
    public function store($data)
    {
        if (empty($data['asignaturas']) || !is_array($data['asignaturas'])) {
            echo "<script>alert('Debes seleccionar al menos una asignatura.'); history.back();</script>";
            exit;
        }

        foreach ($data['asignaturas'] as $asigId) {
            $this->model->create([
                'profesor_id'     => $data['profesor_id'],
                'curso_id'        => $data['curso_id'],
                'asignatura_id'   => $asigId,
                'anio_id'         => $data['anio_id'],
                'fecha_inicio'    => $data['fecha_inicio'] ?? null,
                'fecha_fin'       => $data['fecha_fin'] ?? null,
                'horas_semanales' => $data['horas_semanales'] ?? null,
                'es_jefe_curso'   => !empty($data['es_jefe_curso']) ? 1 : 0,
            ]);
        }

        header("Location: index.php?action=profesor_curso_asignatura");
        exit;
    }

    /* ===========================
       AJAX: ASIGNATURAS POR CURSO
    ============================ */
    public function getAsignaturasPorCurso()
    {
        $curso_id = $_GET['curso_id'] ?? null;

        if (!$curso_id) {
            echo json_encode([]);
            exit;
        }

        $caModel = new CursoAsignatura();
        $asignaturas = $caModel->getAsignaturasPorCurso($curso_id);

        header('Content-Type: application/json');
        echo json_encode($asignaturas);
        exit;
    }

    /* ===========================
       EDITAR
    ============================ */
    public function edit($id)
    {
        $asignacion = $this->model->getById($id);

        // Se necesitan: profesor, curso y año
        $profesor_id = $asignacion['profesor_id'];
        $curso_id    = $asignacion['curso_id'];
        $anio_id     = $asignacion['anio_id'];

        $profesorModel    = new Profesor();
        $cursoModel       = new Cursos();
        $asignaturaModel  = new Asignaturas();
        $anioModel        = new Anio();
        $caModel          = new CursoAsignatura();

        $profesores          = $profesorModel->getActivos();
        $cursos              = $cursoModel->getAll();
        $anios               = $anioModel->getAll();
        $asignaturas         = $caModel->getAsignaturasPorCurso($curso_id);
        $asignaturasActuales = $this->model->getAsignacionesActuales($profesor_id, $curso_id, $anio_id);

        require __DIR__ . '/../views/profesorCursoAsignatura/edit.php';
    }

    /* ===========================
       ACTUALIZAR
    ============================ */
    public function update($id, $data)
    {
        if (empty($data['asignaturas'])) {
            echo "<script>alert('Debes seleccionar al menos una asignatura.'); history.back();</script>";
            exit;
        }

        $profesor_id = $data['profesor_id'];
        $curso_id    = $data['curso_id'];
        $anio_id     = $data['anio_id'];

        // Elimina todas las asignaturas actuales
        $this->model->deleteByProfesorCursoAnio($profesor_id, $curso_id, $anio_id);

        // Inserta las nuevas asignaciones
        foreach ($data['asignaturas'] as $asignatura_id) {
            $this->model->create([
                'profesor_id'     => $profesor_id,
                'curso_id'        => $curso_id,
                'asignatura_id'   => $asignatura_id,
                'anio_id'         => $anio_id,
                'fecha_inicio'    => $data['fecha_inicio'] ?? null,
                'fecha_fin'       => $data['fecha_fin'] ?? null,
                'horas_semanales' => $data['horas_semanales'] ?? null,
                'es_jefe_curso'   => !empty($data['es_jefe_curso']) ? 1 : 0,
            ]);
        }

        header("Location: index.php?action=profesor_curso_asignatura");
        exit;
    }

    /* ===========================
       ELIMINAR
    ============================ */
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=profesor_curso_asignatura");
        exit;
    }
}
