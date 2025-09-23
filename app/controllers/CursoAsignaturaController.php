<?php
require_once __DIR__ . '/../models/CursoAsignatura.php';
require_once __DIR__ . '/../models/Cursos.php';        // Para listar cursos
require_once __DIR__ . '/../models/Asignaturas.php';   // Para listar asignaturas

class CursoAsignaturaController
{
    private $model;

    public function __construct()
    {
        $this->model = new CursoAsignatura();
    }

    // Mostrar lista

    public function index()
    {
        $cursoModel = new Cursos();
        $cursos = $cursoModel->getAll(); // para llenar el select

        $curso_id = $_GET['curso_id'] ?? null;

        if ($curso_id) {
            $cursoAsignaturas = $this->model->getByCurso((int) $curso_id);
        } else {
            $cursoAsignaturas = $this->model->getAll();
        }

        require __DIR__ . '/../views/curso_asignaturas/index.php';
    }

    // Mostrar formulario de crear
    public function create()
    {
        $cursoModel = new Cursos();
        $asignaturaModel = new Asignaturas();

        $cursos = $cursoModel->getAll();
        $asignaturas = $asignaturaModel->getAll();

        require __DIR__ . '/../views/curso_asignaturas/create.php';
    }

    // Guardar nuevo
    public function store($data)
    {
        $this->model->create(
            (int) $data['curso_id'],
            (int) $data['asignatura_id']
        );
        header("Location: index.php?action=curso_asignaturas");
    }

    // Mostrar formulario editar
    public function edit($id)
    {
        $cursoAsignatura = $this->model->getById($id);

        $cursoModel = new Cursos();
        $asignaturaModel = new Asignaturas();

        $cursos = $cursoModel->getAll();
        $asignaturas = $asignaturaModel->getAll();

        require __DIR__ . '/../views/curso_asignaturas/edit.php';
    }

    // Guardar cambios
    public function update($id, $data)
    {
        $this->model->update(
            $id,
            (int) $data['curso_id'],
            (int) $data['asignatura_id']
        );
        header("Location: index.php?action=curso_asignaturas");
    }

    // Eliminar
    public function destroy($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=curso_asignaturas");
    }
}
