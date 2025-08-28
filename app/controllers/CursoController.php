<?php
require_once __DIR__ . '/../models/Cursos.php';
require_once __DIR__ . '/../models/Anio.php'; // para listar años en los formularios

class CursosController
{
    private $cursosModel;
    private $anioModel;

    public function __construct() {
        $this->cursosModel = new Cursos();
        $this->anioModel   = new Anio();
    }

    // Mostrar todos los cursos
    public function index() {
        $cursos = $this->cursosModel->getAll();
        require __DIR__ . '/../views/cursos/index.php';
    }

    // Formulario de creación
    public function create() {
        $anios = $this->anioModel->getAll(); // para elegir a qué año pertenece
        require __DIR__ . '/../views/cursos/create.php';
    }

    // Guardar curso nuevo
    public function store($data) {
        if (!empty($data['anio_id']) && !empty($data['nombre'])) {
            $this->cursosModel->create($data['anio_id'], $data['nombre']);
        }
        header("Location: index.php?action=cursos");
        exit;
    }

    // Formulario de edición
    public function edit($id) {
        $curso = $this->cursosModel->getById($id);
        $anios = $this->anioModel->getAll(); // para desplegar combo de años
        require __DIR__ . '/../views/cursos/edit.php';
    }

    // Actualizar curso
    public function update($id, $data) {
        if (!empty($data['anio_id']) && !empty($data['nombre'])) {
            $this->cursosModel->update($id, $data['anio_id'], $data['nombre']);
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
