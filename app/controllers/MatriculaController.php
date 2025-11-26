<?php
require_once __DIR__ . '/../models/Matricula.php';
require_once __DIR__ . '/../models/Alumno.php';
require_once __DIR__ . '/../models/Cursos.php';
require_once __DIR__ . '/../models/Anio.php';

class MatriculaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Matricula();
    }

    // Listar matrículas
    public function index()
    {
        $nombre = $_GET['nombre'] ?? null;
        $rut = $_GET['rut'] ?? null;
        $anio = $_GET['anio'] ?? null;
        $curso = $_GET['curso'] ?? null;

        $matriculas = $this->model->buscarMatriculas($nombre, $rut, $anio, $curso);

        // 🔹 Cargar selects
        $anioModel = new Anio();
        $cursoModel = new Cursos();

        $anios = $anioModel->getAll();
        $cursos = $cursoModel->getAll();

        include '../views/matriculas/index.php';
    }

    //require __DIR__ . '/../views/matriculas/index.php';
    //Index de busqueda de matricula
    public function indexBusquedaMatricula()
    {
        $nombre = $_GET['nombre'] ?? null;
        $rut = $_GET['rut'] ?? null;
        $anio = $_GET['anio'] ?? null;
        $curso = $_GET['curso'] ?? null;

        $matriculas = $this->model->buscarMatriculas($nombre, $rut, $anio, $curso);
        include 'views/matriculas/index.php';
    }

    // Formulario crear
    public function create()
    {
        $alumnoModel = new Alumno();
        $cursoModel = new Cursos();
        $anioModel = new Anio();

        $alumnos = $alumnoModel->getAll();
        $cursos = $cursoModel->getAll();
        $anios = $anioModel->getAll();

        require __DIR__ . '/../views/matriculas/create.php';
    }

    // Guardar nueva matrícula
    public function store($data)
    {
        $this->model->create($data);
        header("Location: index.php?action=matriculas");
        exit;
    }

    // Formulario editar
    public function edit($id)
    {
        $matricula = $this->model->getById($id);
        $alumnoModel = new Alumno();
        $cursoModel = new Cursos();
        $anioModel = new Anio();

        $alumnos = $alumnoModel->getAll();
        $cursos = $cursoModel->getAll();
        $anios = $anioModel->getAll();

        require __DIR__ . '/../views/matriculas/edit.php';
    }

    // Actualizar
    public function update($id, $data)
    {
        $this->model->update($id, $data);
        header("Location: index.php?action=matriculas");
        exit;
    }

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=matriculas");
        exit;
    }
}
