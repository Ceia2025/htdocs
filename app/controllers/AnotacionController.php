<?php
require_once __DIR__ . '/../models/Anotacion.php';

class AnotacionController
{
    private $model;

    public function __construct()
    {
        $this->model = new Anotacion();
    }

    // Vista principal: selector año → curso → listado alumnos
    public function index()
    {
        require_once __DIR__ . '/../models/Cursos.php';

        $anios = $this->model->getAnios();
        $cursos = [];
        $alumnos = [];

        $anio_id = $_GET['anio_id'] ?? null;
        $curso_id = $_GET['curso_id'] ?? null;
        $semestre = $_GET['semestre'] ?? null;

        if ($anio_id) {
            $cursos = $this->model->getCursosByAnio($anio_id);
        }
        if ($anio_id && $curso_id) {
            $alumnos = $this->model->getAlumnosConAnotaciones($anio_id, $curso_id, $semestre);
        }

        // Nombre del curso seleccionado para mostrarlo en la vista
        $cursoSeleccionado = null;
        foreach ($cursos as $c) {
            if ($c['id'] == $curso_id) {
                $cursoSeleccionado = $c;
                break;
            }
        }

        include __DIR__ . '/../views/anotaciones/index.php';
    }

    // Formulario nueva anotación
    public function create()
    {
        $anios = $this->model->getAnios();
        $anio_id = $_GET['anio_id'] ?? null;
        $curso_id = $_GET['curso_id'] ?? null;
        $alumno_id = $_GET['alumno_id'] ?? null;
        $matricula_id = $_GET['matricula_id'] ?? null;

        $asignaturas = $curso_id ? $this->model->getAsignaturasByCurso($curso_id) : [];

        // Si viene alumno preseleccionado, traer sus datos para mostrarlos
        $alumnoPreseleccionado = null;
        if ($alumno_id && $anio_id) {
            $resultados = $this->model->buscarAlumno($alumno_id, $anio_id, true); // búsqueda por ID
            if (!empty($resultados)) {
                $alumnoPreseleccionado = $resultados[0];
            }
        }

        include __DIR__ . '/../views/anotaciones/create.php';
    }

    // Guardar anotación
    public function store($data)
    {
        if (
            !empty($data['alumno_id']) && !empty($data['matricula_id']) &&
            !empty($data['asignatura_id']) && !empty($data['contenido'])
        ) {
            $this->model->create($data);
        }
        header("Location: index.php?action=anotaciones&anio_id={$data['anio_id']}&curso_id={$data['curso_id']}");
        exit;
    }

    // Ver anotaciones de un alumno en una matrícula
    public function verAlumno()
    {
        $matricula_id = $_GET['matricula_id'] ?? null;
        $semestre = $_GET['semestre'] ?? null;
        $anio_id = $_GET['anio_id'] ?? null;
        $curso_id = $_GET['curso_id'] ?? null;

        if (!$matricula_id) {
            header("Location: index.php?action=anotaciones");
            exit;
        }

        $anotaciones = $this->model->getByMatricula($matricula_id, $semestre);
        include __DIR__ . '/../views/anotaciones/ver_alumno.php';
    }

    // Eliminar anotación
    public function delete($id)
    {
        $anio_id = $_GET['anio_id'] ?? '';
        $curso_id = $_GET['curso_id'] ?? '';
        $this->model->delete($id);
        header("Location: index.php?action=anotaciones&anio_id=$anio_id&curso_id=$curso_id");
        exit;
    }

    // AJAX: buscar alumno para el formulario
    public function buscarAlumnoAjax()
    {
        header('Content-Type: application/json; charset=utf-8');
        $term = $_GET['term'] ?? '';
        $anio_id = $_GET['anio_id'] ?? null;
        if (strlen($term) < 2 || !$anio_id) {
            echo json_encode([]);
            exit;
        }
        echo json_encode($this->model->buscarAlumno($term, $anio_id));
        exit;
    }

    // AJAX: obtener asignaturas de un curso
    public function getAsignaturasAjax()
    {
        header('Content-Type: application/json; charset=utf-8');
        $curso_id = $_GET['curso_id'] ?? null;
        if (!$curso_id) {
            echo json_encode([]);
            exit;
        }
        echo json_encode($this->model->getAsignaturasByCurso($curso_id));
        exit;
    }
}