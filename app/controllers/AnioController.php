<?php
require_once __DIR__ . '/../models/Anio.php';

class AnioController
{
    private $anioModel;

    public function __construct() {
        $this->anioModel = new Anio();
    }

    // Listar todos los años
    public function index() {
        $anios = $this->anioModel->getAll();
        require __DIR__ . '/../views/anio/index.php';
    }

    // Crear nuevo año
    public function create($data) {
        if (!empty($data['anio'])) {
            $this->anioModel->create($data['anio'], $data['descripcion'] ?? null);
        }
        header("Location: index.php?action=anios");
        exit;
    }

    // Editar año por ID
    public function edit($id) {
        $anio = $this->anioModel->getById($id);
        require __DIR__ . '/../views/anio/edit.php';
    }

    // Actualizar año
    public function update($id, $data) {
        if (!empty($data['anio'])) {
            $this->anioModel->update($id, $data['anio'], $data['descripcion'] ?? null);
        }
        header("Location: index.php?action=anios");
        exit;
    }

    // Eliminar año
    public function delete($id) {
        $this->anioModel->delete($id);
        header("Location: index.php?action=anios");
        exit;
    }
}
