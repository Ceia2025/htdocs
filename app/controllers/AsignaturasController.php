<?php
require_once __DIR__ . '/../models/Asignaturas.php';

class AsignaturasController
{
    private $asignaturasModel;

    public function __construct() {
        $this->asignaturasModel = new Asignaturas();
    }

    // Mostrar todos las Asignaturas
    public function index() {
        $asignaturas = $this->asignaturasModel->getAll();
        require __DIR__ . '/../views/asignaturas/index.php';
    }

    // Formulario de creación
    public function create() {
        require __DIR__ . '/../views/asignaturas/create.php';
    }

    // Guardar curso nuevo
    public function store($data) {
        if (!empty($data['nombre'])) {
            $this->asignaturasModel->create($data['nombre'], $data['descp']);
        }
        header("Location: index.php?action=asignaturas");
        exit;
    }

    // Formulario de edición
    public function edit($id) {
        $asignatura = $this->asignaturasModel->getById($id);
        require __DIR__ . '/../views/asignaturas/edit.php';
    }

    // Actualizar curso
    public function update($id, $data) {
        if (!empty($data['nombre'])) {
            $this->asignaturasModel->update($id, $data['nombre'], $data['descp']);
        }
        header("Location: index.php?action=asignaturas");
        exit;
    }

    // Eliminar curso
    public function delete($id) {
        $this->asignaturasModel->delete($id);
        header("Location: index.php?action=asignaturas");
        exit;
    }
}
