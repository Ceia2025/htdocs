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

    // Editar
    public function edit($id)
    {
        $emergencia = $this->model->getById($id);
        $alumnoModel = new Alumno();
        $alumnos = $alumnoModel->getAll();
        require __DIR__ . '/../views/alum_emergencia/edit.php';
    }

    // Actualizar
    public function update($id, $data)
    {
        $this->model->update(
            $id,
            $data['alumno_id'],
            $data['nombre_contacto'],
            $data['telefono'],
            $data['direccion'],
            $data['relacion']
        );
        header("Location: index.php?action=alum_emergencia");
    }

    // Eliminar
    public function delete($id)
    {
        $this->model->delete($id);
        header("Location: index.php?action=alum_emergencia");
    }
}
