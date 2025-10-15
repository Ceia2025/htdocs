<?php
require_once __DIR__ . '/../models/AntecedenteFamiliar.php';

class AntecedenteFamiliarController
{
    private $model;

    public function __construct() {
        $this->model = new AntecedenteFamiliar();
    }

    // Listar todos
    public function index() {
        $antecedentes = $this->model->getAll();
        require __DIR__ . '/../views/antecedentefamiliar/index.php';
    }

    // Mostrar formulario crear
    public function createForm() {
        require __DIR__ . '/../views/antecedentefamiliar/create.php';
    }

    // Guardar nuevo
    public function create($data) {
        if (!empty($data['alumno_id'])) {
            $this->model->create(
                $data['alumno_id'],
                $data['padre'] ?? 'Desconocido',
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? 'Desconocido',
                $data['nivel_ciclo_m'] ?? null
            );
        }
        header("Location: index.php?action=antecedentefamiliar");
        exit;
    }

    // Editar por ID
    public function edit($id) {
        $antecedente = $this->model->getById($id);
        require __DIR__ . '/../views/antecedentefamiliar/edit.php';
    }

    // Actualizar
    public function update($id, $data) {
        if (!empty($data['alumno_id'])) {
            $this->model->update(
                $id,
                $data['alumno_id'],
                $data['padre'] ?? 'Desconocido',
                $data['nivel_ciclo_p'] ?? null,
                $data['madre'] ?? 'Desconocido',
                $data['nivel_ciclo_m'] ?? null
            );
        }
        header("Location: index.php?action=antecedentefamiliar");
        exit;
    }

    // Eliminar
    public function delete($id) {
        $this->model->delete($id);
        header("Location: index.php?action=antecedentefamiliar");
        exit;
    }
}
