<?php
require_once __DIR__ . '/../models/AntecedenteEscolar.php';

class AntecedenteEscolarController
{
    private $model;

    public function __construct() {
        $this->model = new AntecedenteEscolar();
    }

    // Listar
    public function index() {
        $antecedentes = $this->model->getAll();
        require __DIR__ . '/../views/antecedente_escolar/index.php';
    }

    // Mostrar formulario crear
    public function create() {
        require __DIR__ . '/../views/antecedente_escolar/create.php';
    }

    // Guardar nuevo
    public function store($data) {
        $this->model->create($data);
        header("Location: index.php?action=antecedente_escolar");
        exit;
    }

    // Editar
    public function edit($id) {
        $antecedente = $this->model->getById($id);
        require __DIR__ . '/../views/antecedente_escolar/edit.php';
    }

    // Actualizar
    public function update($id, $data) {
        $this->model->update($id, $data);
        header("Location: index.php?action=antecedente_escolar");
        exit;
    }

    // Eliminar
    public function delete($id) {
        $this->model->delete($id);
        header("Location: index.php?action=antecedente_escolar");
        exit;
    }
}
