<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new User();
    }

    // Mostrar lista
    public function index() {
        $users = $this->model->getAll();
        require __DIR__ . '/../views/users/index.php';
    }

    // Mostrar formulario de crear
    public function create() {
        require __DIR__ . '/../views/users/create.php';
    }

    // Guardar nuevo
    public function store($data) {
        $this->model->create($data['nombre'], $data['email'], $data['password'], $data['rol']);
        header("Location: index.php?action=users");
    }

    // Mostrar formulario editar
    public function edit($id) {
        $user = $this->model->getById($id);
        require __DIR__ . '/../views/users/edit.php';
    }

    // Guardar cambios
    public function update($id, $data) {
        $this->model->update($id, $data['nombre'], $data['email'], $data['rol'], $data['password'] ?? null);
        header("Location: index.php?action=users");
    }

    // Eliminar
    public function destroy($id) {
        $this->model->delete($id);
        header("Location: index.php?action=users");
    }
}
