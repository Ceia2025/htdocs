<?php
require_once __DIR__ . '/../models/CursoDocente.php';

class CursoDocenteController
{
    private CursoDocente $model;

    public function __construct()
    {
        $this->model = new CursoDocente();
    }

    /* Lista todos los cursos con su docente asignado */
    public function index()
    {
        $anioId     = (int) ($_GET['anio_id'] ?? $this->model->getAnioActualId());
        $anios      = $this->model->getAnios();
        $relaciones = $this->model->getAllConDocente($anioId);

        require __DIR__ . '/../views/curso_docente/index.php';
    }

    /* Formulario asignar/cambiar docente */
    public function create()
    {
        $anioId   = (int) ($_GET['anio_id'] ?? $this->model->getAnioActualId());
        $cursoId  = (int) ($_GET['curso_id'] ?? 0);
        $docentes = $this->model->getDocentes();
        $anios    = $this->model->getAnios();

        // Pre-cargar asignación actual si ya existe
        $asignacionActual = $cursoId
            ? $this->model->getDocenteDeCurso($cursoId, $anioId)
            : null;

        // Necesitamos la lista de cursos para el select
        require_once __DIR__ . '/../models/Cursos.php';
        $cursos = (new Cursos())->getAll();

        require __DIR__ . '/../views/curso_docente/create.php';
    }

    /* Guardar asignación */
    public function store()
    {
        $cursoId   = (int) $_POST['curso_id'];
        $docenteId = (int) $_POST['docente_id'];
        $anioId    = (int) $_POST['anio_id'];

        $this->model->asignar($cursoId, $docenteId, $anioId);

        header("Location: index.php?action=curso_docente&anio_id={$anioId}");
        exit;
    }

    /* Eliminar asignación */
    public function destroy()
    {
        $id     = (int) ($_GET['id']     ?? 0);
        $anioId = (int) ($_GET['anio_id'] ?? 0);

        if ($id) $this->model->desasignar($id);

        header("Location: index.php?action=curso_docente&anio_id={$anioId}");
        exit;
    }
}