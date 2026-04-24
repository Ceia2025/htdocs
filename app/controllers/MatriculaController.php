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


    // Ver y editar números de lista de un curso
    public function numeroLista()
    {
        //require_once __DIR__ . '/../models/Anio.php';
        //require_once __DIR__ . '/../models/Cursos.php';

        $anioModel = new Anio();
        $cursoModel = new Cursos();

        $cursoId = $_GET['curso_id'] ?? null;
        $anioId = $_GET['anio_id'] ?? null;

        if (!$cursoId || !$anioId) {
            // Mostrar selector de curso/año
            $anioModel = new Anio();
            $cursosModel = new Cursos();
            $anios = $anioModel->getAll();
            require_once __DIR__ . '/../models/Asistencia.php';
            $asistenciaModel = new Asistencia();
            $cursos = !empty($anioId) ? $asistenciaModel->getCursosConMatricula($anioId) : [];
            include __DIR__ . '/../views/matriculas/numero_lista_selector.php';
            return;
        }

        $alumnos = $this->model->getAlumnosConNumeroLista($cursoId, $anioId);

        // Datos para el encabezado
        require_once __DIR__ . '/../models/Cursos.php';
        require_once __DIR__ . '/../models/Anio.php';
        $cursosModel = new Cursos();
        $anioModel = new Anio();
        $curso = $cursosModel->getById($cursoId);
        $anio = $anioModel->getById($anioId);

        include __DIR__ . '/../views/matriculas/numero_lista.php';
    }

    // Guardar números de lista
    public function guardarNumeroLista()
    {
        $cursoId = $_POST['curso_id'] ?? null;
        $anioId = $_POST['anio_id'] ?? null;
        $listas = $_POST['numero_lista'] ?? []; // array [matricula_id => numero]

        foreach ($listas as $matriculaId => $numero) {
            $this->model->updateNumeroLista($matriculaId, $numero ?: null);
        }

        header("Location: index.php?action=matricula_numero_lista&curso_id=$cursoId&anio_id=$anioId&guardado=1");
        exit;
    }

    public function retirar($id)
    {
        // Leer id desde POST si no viene por parámetro
        $id = $id ?? $_POST['id'] ?? null;
        $fechaRetiro = $_POST['fecha_retiro'] ?? date('Y-m-d');

        if (!$id) {
            header("Location: index.php?action=matriculas");
            exit;
        }

        $this->model->retirar($id, $fechaRetiro);
        header("Location: index.php?action=matriculas&retirado=1");
        exit;
    }

    public function reintegrar($id = null)
    {
        $id = $id ?? $_POST['id'] ?? null;

        if (!$id) {
            header("Location: index.php?action=matriculas");
            exit;
        }

        $this->model->reintegrar($id);
        header("Location: index.php?action=matriculas&reintegrado=1");
        exit;
    }
}
