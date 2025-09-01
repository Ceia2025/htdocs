<?php
require_once __DIR__ . '/../models/Cursos.php';

class CursosController
{
    private $cursosModel;

    public function __construct() {
        $this->cursosModel = new Cursos();
    }

    // Mostrar todos los cursos
    public function index() {
        $cursos = $this->cursosModel->getAll();
        require __DIR__ . '/../views/cursos/index.php';
    }

    // Formulario de creación
    public function create() {
        require __DIR__ . '/../views/cursos/create.php';
    }

    // Guardar curso nuevo
    public function store($data) {
        if (!empty($data['nombre'])) {
            $this->cursosModel->create($data['nombre']);
        }
        header("Location: index.php?action=cursos");
        exit;
    }

    // Formulario de edición
    public function edit($id) {
        $curso = $this->cursosModel->getById($id);
        require __DIR__ . '/../views/cursos/edit.php';
    }

    // Actualizar curso
    public function update($id, $data) {
        if (!empty($data['nombre'])) {
            $this->cursosModel->update($id, $data['nombre']);
        }
        header("Location: index.php?action=cursos");
        exit;
    }

    // Eliminar curso
    public function delete($id) {
        $this->cursosModel->delete($id);
        header("Location: index.php?action=cursos");
        exit;
    }
}
