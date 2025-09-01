<?php
require_once __DIR__ . '/../models/Alumno.php';

class AlumnosController
{
    private $alumnoModel;

    public function __construct() {
        $this->alumnoModel = new Alumno();
    }

    // Mostrar todos los alumnos
    public function index() {
        $alumnos = $this->alumnoModel->getAll();
        require __DIR__ . '/../views/alumnos/index.php';
    }

    // Formulario de creación
    public function create() {
        require __DIR__ . '/../views/alumnos/create.php';
    }

    // Guardar alumno nuevo
    public function store($data) {
        if (!empty($data['run']) && !empty($data['nombre']) && !empty($data['apepat'])) {
            $this->alumnoModel->create($data);
        }
        header("Location: index.php?action=alumnos");
        exit;
    }

    // Formulario de edición
    public function edit($id) {
        $alumno = $this->alumnoModel->getById($id);
        require __DIR__ . '/../views/alumnos/edit.php';
    }

    // Actualizar alumno
    public function update($id, $data) {
        if (!empty($id) && !empty($data['run']) && !empty($data['nombre']) && !empty($data['apepat'])) {
            $this->alumnoModel->update($id, $data);
        }
        header("Location: index.php?action=alumnos");
        exit;
    }

    // Eliminar alumno
    public function delete($id) {
        if (!empty($id)) {
            $this->alumnoModel->delete($id);
        }
        header("Location: index.php?action=alumnos");
        exit;
    }
}
